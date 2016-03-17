<?php

class MooWSe {

    private $_client_name;
    private $_client_access;
    private $_clientAuthenticated = false;
    private $_tokenChecked = false;
    private $_tokenTimeToLive = 1;

    public function Security($Security) {

        if (isset($Security->UsernameToken->Username) && isset($Security->UsernameToken->Password) && isset($Security->UsernameToken->Nonce) && isset($Security->UsernameToken->Created)) {
            //ligne a commenter
            list($client_name, $client_access) = explode(",", $Security->UsernameToken->Username);
            //$client_password = $Security->UsernameToken->Password;
            $client_password_digest = $Security->UsernameToken->Password;
            $client_nonce = $Security->UsernameToken->Nonce;
            $client_created = $Security->UsernameToken->Created;
            $client_IP = $_SERVER["REMOTE_ADDR"];

            //on regarde si le client est enregistré
            $checkingDatas = new dataBaseCall('localhost', 'webservices', 'utf8', 'root', '');
            $registered = $checkingDatas->clientRegistered($client_nonce, $client_created, $client_access, $client_password_digest, $client_IP);

            //si l'authentification est reusssie
            if ($registered) {

                $this->_client_name = $client_name;
                $this->_client_access = $client_access;
                session_name($this->_client_name . "_" . $this->_client_access . "_session");
                session_start();
                session_write_close();

                $this->_clientAuthenticated = true;
            }
        } elseif (isset($Security->UsernameToken->Username) && isset($Security->BinarySecurityToken)) {
            list($client_name, $client_access) = explode(",", $Security->UsernameToken->Username);
            session_name($client_name . "_" . $client_access . "_session");
            session_start();
            session_regenerate_id();
            $token = $_SESSION["token"];
            $token_time = $_SESSION["token_time"];
            session_write_close();

            //sleep($this->_tokenTTL+1);
            if (isset($token) && isset($token_time)) {
                if ($token == $Security->BinarySecurityToken) {
                    if ($token_time >= (time() - $this->_tokenTimeToLive)) {
                        $this->_client_name = $client_name;
                        $this->_client_access = $client_access;
                        $this->_tokenChecked = true;
                    }
                }
            }
        }
    }

    public function authenticate() {

        //require ('./Loggeur/Logger.php');

        $token = "ERROR !";
        if ($this->_clientAuthenticated) {

            //logs : (ip,date,client,modalite,action)->log
            $client_IP = $_SERVER["REMOTE_ADDR"];
            $time = time();
            $client_name = $this->_client_name;
            $client_access = $this->_client_access;
            $action = "authenticate";

/*            $logger= new Logger();
            $logger->LogClient($client_IP,$client_name,$client_access,$action);*/

            $crypto_strong = false;
            while (!$crypto_strong) {
                $token = base64_encode(bin2hex(openssl_random_pseudo_bytes(16, $crypto_strong)));
                $token_time = time();
            }

            session_name($this->_client_name . "_" . $this->_client_access . "_session");
            session_start();
            session_regenerate_id();
            $_SESSION["token"] = $token;
            $_SESSION["token_time"] = $time;
            session_write_close();
        }
        return $token;
    }

    public function getWSDL($service) {
        $service_WSDL = "ERROR !";
        if ($this->_tokenChecked) {

            //logs : (ip,date,client,modalite,service,action)->log
            $client_IP = $_SERVER["REMOTE_ADDR"];
            $time = time();
            $client_name = $this->_client_name;
            $client_access = $this->_client_access;
            $service = "service";
            $action = "getWSDL";

/*            $logger= new Logger();
            $logger->LogServ($client_IP,$client_name,$client_access,$service,$action);*/

            //renvoyer la liste des fonctions auxquelles l'utilisateur a accï¿½s 
            //appel ï¿½ la base
            if ($this->_tokenChecked) {
                $gettingDatas = new dataBaseCall('localhost', 'webservices', 'utf8', 'root', '');
                $functions=$gettingDatas->listFunction($client_name);
            }

            //gÃ©nÃ©rateur : (service,fonctions)->WSDL
            $service_WSDL = "<" . $service . ">" . implode(",", $functions) . "</" . $service . ">";
        }
        return htmlspecialchars($service_WSDL, ENT_XML1);
    }

}

class dataBaseCall {

    private $bdd; //objet caracterisant la base de données
    private $dataBaseAdress; //contient l'adresse de la base sur le serveur (pour nous pour le moment : localhost)
    private $dataBaseName; //nom de la base de données
    private $encoding; //encodage utilisé pour la base
    private $user; //utilisateur de la base
    private $password; //mot de passe

    //Constructeur de la base, prend en arguments tous les attributs ci dessus sauf bdd

    function dataBaseCall($dataBaseAdress, $dataBaseName, $encoding, $user, $password) {
        $this->dataBaseAdress = $dataBaseAdress;
        $this->dataBaseName = $dataBaseName;
        $this->encoding = $encoding;
        $this->user = $user;
        $this->password = $password;
        $this->bdd = new PDO('mysql:host=' . $dataBaseAdress . ';dbname=' . $dataBaseName . ';charset=' . $encoding . '', $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    //Prend en argument les raguments de sécutrité de Security  et renvoit $registered, un booléen qui vaut vrai si l'authentification du client est bonne
    //L'identité est vérifiée en se connectant à la bdd
    function clientRegistered($client_name, $client_nonce, $client_created, $client_access, $client_password_digest, $client_IP) {
        $base = $this->bdd;
        // requete sql
        $recherche_client = $base->query('SELECT client_name,client_ip,client_password,modality_name FROM client INNER JOIN modality ON modality.modality_id=client.modality_id WHERE client_name=\'' . $client_name . '\'');
        //verification de table non vide (sinon le client n'existe pas
        //La reqête SQL renvoit deux lignes avec un id différent pour un même nom de client, s'il y a deux modalités de connexion
        $registered = false;
        //on rentre dans la boucle s'il y a un client qui a ce nom, sinon l'appel est faux
        while ($info_client = $recherche_client->fetch()) {
            //assignation des variables, le préfixe data caractérise les infos récupérerées ne base
            $client_database_name = $info_client['client_name'];
            $client_database_ip = $info_client['client_ip'];
            $client_database_password = $info_client['client_password'];
            $client_database_modality = $info_client['modality_name'];
            //mot de passe encryptre
            $client_database_encrypted_password = base64_encode(sha1($client_nonce . $client_created . $client_database_password));
            //verification des informations en base, la variable ci dessous vaut vraie si les informations 

            echo '</br>';
            $registered = ((($client_access == $client_database_modality) &&
                    ($client_database_encrypted_password == $client_password_digest) &&
                    ($client_IP == $client_database_ip) &&
                    ($client_database_modality == $client_access)) || $registered);
        }
        return $registered;
    }

    //prend en argument le nom du client et renvoit un array contenant les ids des fonctions auxquelles le client peut accèder
    function listFunction($client_name) {
        //appel des fonctions autorisees pour le client
        $base = $this->bdd;
        $function_request = $base->query('SELECT function.function_id FROM function INNER JOIN access ON function.function_id=access.function_id
                    INNER JOIN client ON access.client_id=client.client_id
                    INNER JOIN server ON server.server_id=function.server_id
                    WHERE client_name=\'' . $client_name . '\' ');
        //extraire le premier element              
        $current_function = $function_request->fetch();
        //creation d'un array
        $functions = array($current_function["function_id"]);
        //On garde l'id
        $last_function = $current_function["function_id"];
        //parcours de la table des fonctions autorisees
        while ($current_function = $function_request->fetch()) {
            //ajoute la fonction courante au array
            if ($current_function["function_id"] != $last_function) {
                array_push($functions, $current_function["function_id"]);
            }
            $last_function = $current_function["function_id"];
        }
        //on renvoit l'array des fonctions autorisées
        return $functions;
    }

    function affichage($client_name) {
        //appel de la base
        $base = $this->bdd;
        //requete sql
        $function_request = $base->query('SELECT function.function_id,function.function_name,server_name FROM function INNER JOIN access ON function.function_id=access.function_id
                    INNER JOIN client ON access.client_id=client.client_id
                    INNER JOIN server ON server.server_id=function.server_id
                    WHERE client_name=\'' . $client_name . '\' ');
        //obtention du nombre de fonctions auxquelles le cl
        $number_function = $function_request->rowCount();
        echo '<h2>Affichage des fonctions auxquelles ' . $client_name . ' vous peut acceder</h2>';
        echo '</br>';
        echo 'Le client ' . $client_name . ' peut acceder a ' . $number_function . ' fonction(s)';
        echo '</br>';
        echo '<u><b>Listing des fonctions :</u></b>';
        echo '</br>';
        $last_function=NULL;
        while ($function_recup = $function_request->fetch()) {
            if($function_recup!=$last_function){
                echo 'fonction numero: ' . $function_recup["function_id"] . ' ' . $function_recup["function_name"] . ' du serveur ' . $function_recup["server_name"];
                echo '</br>';
            }
            $last_function=$function_recup;
        }
    }

}


?>