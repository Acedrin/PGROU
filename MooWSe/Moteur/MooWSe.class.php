<?php

//classe d'Elliot
require "generateWSDL.php";
//classe de Quentin
require "dataBaseCall.php";
//classe de Giano
require "Loggeur/Logger.php";

//fichier avec data de configuration
//$settings = include("settings.php"); //---> Insert giano

class MooWSe {

    private $_client_name; //nom du client
    private $_client_access; //son acces batch ou human
    private $_clientAuthenticated = false; //intialement le client n'est pas authentifiee
    private $_tokenChecked = false; //son token n'est pas correct non plus 
    private $_tokenTimeToLive = 1; //duree de vie d'un token
    private $_logger; //permet de stocker en base les informations sur les actions menees via la classe Logger dans Logger.php

    //contruction d'un Logger

    public function __construct() {
        //require "settings.php'";
        $this->_logger = new Logger();
    }

    // La fonction Security est toujours appelle via les headers des SOAP associés aux objets Moowse dans les clients.
    public function Security($Security) {
        //Premiere possibilite : le client envoie on header avec les champs username, password encrypte, sel : il veut se connecter et obtenir un token
        //On verifie que le client envoie bien ces informations et que celles ci existent
        if (isset($Security->UsernameToken->Username) && isset($Security->UsernameToken->Password) && isset($Security->UsernameToken->Nonce) && isset($Security->UsernameToken->Created)) {
            //recuperation nom et acces
            list($client_name, $client_access) = explode(",", $Security->UsernameToken->Username);
            $client_password_digest = $Security->UsernameToken->Password; // est le mot de passe encrypte recu
            $client_nonce = $Security->UsernameToken->Nonce; //chaine aleatoire
            $client_created = $Security->UsernameToken->Created; //date courante au moment du hash par le client
            $client_IP = $_SERVER["REMOTE_ADDR"]; //recuperation de l'ip du server appelant = IP client
            //on regarde si le client est enregistre, appel de base
            //$checkingDatas = new dataBaseCall('localhost', 'moowse', 'utf8', 'root', '');
            //$checkingDatas = new dataBaseCall($dbms_address, $db, 'utf8', $user, $passwd);
            $settings = require("settings.php"); // ajout de settings pour cacher le mot de passe de la base, et l'utilisateur, parametres a regler dans ce fichier
            //appel de la base (connexion) creation d'un objet dataBaseCall qui contient une connexion a la base cela permet de limiter les connexions successives a la base
            $checkingDatas = new dataBaseCall($settings["db_host"], $settings["db_name"], 'utf8', $settings["db_user"], $settings["db_password"]);
            //vrai si les informations clientes sont exactes
            $registered = $checkingDatas->clientRegistered($client_name, $client_nonce, $client_created, $client_access, $client_password_digest, $client_IP);
            //$registered = true;
            //si l'authentification est reusssie
            if ($registered) {
                //on affecte le nom du client et son acces 
                $this->_client_name = $client_name;
                $this->_client_access = $client_access;
                //creation d'une session avec nom sous la forme "nomclient_accesclient_session"
                session_name($this->_client_name . "_" . $this->_client_access . "_session");
                //demarrage de sesion
                session_start();
                session_write_close();
                //client authentifie
                $this->_clientAuthenticated = true;
            }
        } //deuxieme possibilite : le client est authentifie, et a un token, on a alors non plus les sels, mais un array binarysecurity token
        //verification de l'existence des parametres
        elseif (isset($Security->UsernameToken->Username) && isset($Security->BinarySecurityToken)) {
            //recuperation des infos client : nom et acces
            list($client_name, $client_access) = explode(",", $Security->UsernameToken->Username);
            //creation d'une session avec nom de la meme forme que precedement
            session_name($client_name . "_" . $client_access . "_session");
            //demarrage de sessio,
            session_start();
            session_regenerate_id();
            //recuperation du token de la session
            $token = $_SESSION["token"];
            $token_time = $_SESSION["token_time"];
            //fermeture de la sessio,
            session_write_close();

            //sleep($this->_tokenTTL+1);
            //si on a l'existence du token de session
            if (isset($token) && isset($token_time)) {
                //si les token concordent
                if ($token == $Security->BinarySecurityToken) {
                    //et que les token sont encore valides
                    if ($token_time >= (time() - $this->_tokenTimeToLive)) { // cad que le temps de peremption du token est plus grand que la date actuel moins son temps de vie
                        $this->_client_name = $client_name; //affectation du nom et de l'acces
                        $this->_client_access = $client_access;

                        $this->_tokenChecked = true; //le token est verifie et valide, il est checked
                    }
                }
            }
        }
    }

    //si les informations clientes sont exactes et donc que _clientAuthenticate (var globale est vraie) 
    public function authenticate() {
        //par defaut le token vaudra authentification echouee pour indiquer l'echec d'authentification
        $token = "Authentification echouee";
        //sinon si le client est authentifie
        if ($this->_clientAuthenticated) {

            //logs : (ip,client,modalite,action)->log
            //on recupere son IP
            $client_IP = $_SERVER["REMOTE_ADDR"];
            //on recupere le temps au moment de l'auth
            $time = time();
            //recuperation du nom de client et acces
            $client_name = $this->_client_name;
            $client_access = $this->_client_access;
            //sauvegarde avec log
            $action = "authenticate";
            $this->_logger->LogClient($client_IP, $client_name, $client_access, $action);
            //generation d'un token (chaine aleatoire comme nonce)
            $crypto_strong = false;
            while (!$crypto_strong) {
                $token = base64_encode(bin2hex(openssl_random_pseudo_bytes(16, $crypto_strong)));
                $token_time = time();
            }
            //creation d'une session
            session_name($this->_client_name . "_" . $this->_client_access . "_session");
            //demarrage de la session
            session_start();
            session_regenerate_id();
            //affectation du token de la session à notre Token donne au client, pareil pour son temps d affectation
            $_SESSION["token"] = $token;
            $_SESSION["token_time"] = $time;
            //fermeture de session
            session_write_close();
        }
        return $token;
    }

    //la fonction getWSDL prend en parametre le nom d'un serveur et renvoit le WSDL avec les fonctions auorisees pour notre client
    public function getWSDL($service) {
        //la chaine service_WSDL sera renvoyee sous forme error par defaut
        $service_WSDL = 'ERROR';
        //array de fonction vide
        $functions = array();
        //si le token est toujours valable
        if ($this->_tokenChecked) {
            //appel du log
            //logs : (ip,client,modalite,service,action)->log
            //recuperation de l'IP
            $client_IP = $_SERVER["REMOTE_ADDR"];
            //date actuelle
            $time = time();
            //nom du client
            $client_name = $this->_client_name;
            //acces
            $client_access = $this->_client_access;
            $action = "getWSDL";
            //stokage de l'action dans le logger
            $this->_logger->LogServ($client_IP, $client_name, $client_access, $service, $action);

            //renvoyer la liste des fonctions auxquelles l'utilisateur a accï¿½s 
            //appel ï¿½ la base
            if ($this->_tokenChecked) {
                //connexion ï¿½ la base de donnï¿½es 
                //$gettingDatas = new dataBaseCall('localhost', 'moowse', 'utf8', 'root', '');
                //$gettingDatas = new dataBaseCall($dbms_address, $db, 'utf8', $user, $passwd);
                //appel de settings pour obtenir les infos de connexion à la bdd
                $settings = require("settings.php");
                //connexion à la bdd
                $gettingDatas = new dataBaseCall($settings["db_host"], $settings["db_name"], 'utf8', $settings["db_user"], $settings["db_password"]);
                //cette fonction renvoie la liste de fonction auxquelles le client de nom client_name, peut acceder sur le serveur $serveur
                $functions = $gettingDatas->listFunction($client_name, $service);

                //si le tableau est non vide le fichier WSDL contiendra au moins une focntion
                if (count($functions) != 0) {
                    $service_WSDL = generateWSDL($functions); // on appelle cette fonction qui génère le WSDL si on a plus d'une focntion dans la liste
                } else {
                    $service_WSDL = generateFakeWSDL($functions); //sinon on renvoie le WSDL de hello
                }
            }
        }

        return htmlspecialchars($service_WSDL, ENT_XML1); //on retourne la chaine de caractere associee au WSDL
    }

    //getter sur le nom du client
    function getClientName() {
        return $this->_client_name;
    }

}

?>
