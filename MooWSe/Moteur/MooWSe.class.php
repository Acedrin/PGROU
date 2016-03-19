<?php

//classe d'Elliot
require "generateWSDL.php";
//classe de Quentin
require "dataBaseCall.php";
//fichier avec data de configuration
//require "settings.php"; //---> Insert giano

class MooWSe {

    private $_client_name;
    private $_client_access;
    private $_clientAuthenticated = false;
    private $_tokenChecked = false;
    private $_tokenTimeToLive = 1;

    public function Security($Security) {
           //$this->_test=$Security->UsernameToken->Username;
        if (isset($Security->UsernameToken->Username) && isset($Security->UsernameToken->Password) && isset($Security->UsernameToken->Nonce) && isset($Security->UsernameToken->Created)) {
            list($client_name, $client_access) = explode(",", $Security->UsernameToken->Username);
            $client_password_digest = $Security->UsernameToken->Password; // est le mot de passe encrypte recu
            $client_nonce = $Security->UsernameToken->Nonce;
            $client_created = $Security->UsernameToken->Created;
            $client_IP = $_SERVER["REMOTE_ADDR"];
                        $this->_test=$client_IP;
            //on regarde si le client est enregistre, appel de base
            $checkingDatas = new dataBaseCall('localhost', 'webservices', 'utf8', 'root', '');
            //$checkingDatas = new dataBaseCall($dbms_address, $db, 'utf8', $user, $passwd);
            //vrai si les informations clientes sont exactes
            $registered = $checkingDatas->clientRegistered($client_name, $client_nonce, $client_created, $client_access, $client_password_digest, $client_IP);

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
        $token = "ERROR !";
        if ($this->_clientAuthenticated) {

            //logs : (ip,date,client,modalite,action)->log
            $client_IP = $_SERVER["REMOTE_ADDR"];
            $time = time();
            $client_name = $this->_client_name;
            $client_access = $this->_client_access;
            $action = "authenticate";

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
			$service;
            $action = "getWSDL";

            //renvoyer la liste des fonctions auxquelles l'utilisateur a accï¿½s 
            //appel ï¿½ la base
            if ($this->_tokenChecked) {
                //connexion ï¿½ la base de donnï¿½es 
                $gettingDatas = new dataBaseCall('localhost', 'webservices', 'utf8', 'root', ''); 
                //$gettingDatas = new dataBaseCall($dbms_address, $db, 'utf8', $user, $passwd);
				
                $functions = $gettingDatas->listFunction($client_name,$service);
            }
        }

        //gÃ©nÃ©rateur
        $service_WSDL = generateWSDL($functions);
        return htmlspecialchars($service_WSDL, ENT_XML1);
    }

}
/*
//test de Security
$Username ='agap';
	$crypto_strong = false;
	while (!$crypto_strong) { //generation d'un sel (aleatoire pour le moment), le sel va permettre de brouiller le mot de passe aux yeux d'un HDM (Homme Du Milieu)
		$Nonce = base64_encode(bin2hex(openssl_random_pseudo_bytes(16,$crypto_strong))); //encode 64 bit d'un mot de passe aléatoire de 16 bits converti du binaire à l'hexadécimal
		$Created = time(); // temps actuel
	}
        $hash1=  sha1('kangourou87');
	$Password = base64_encode(sha1($Nonce.$Created.$hash1)); //on envoie le mot de passe crypte

	$UsernameToken = array();
	$UsernameToken["Username"] =$Username;
	$UsernameToken["Password"] = $Password;
	$UsernameToken["Nonce"] = $Nonce; //il faut aussi envoyer le sel
	$UsernameToken["Created"] = $Created; //on envoie la date
        
        echo $Password.'</br>';
        //le array Security va contenir UsernameToken et 
	$Security = array();
	$Security["UsernameToken"] = array_push($Security, $UsernameToken);
        echo "<pre>";
		print_r($Security);
        echo "</pre>";
        $moteur= new MooWSe($Security);
        $moteur->Security($Security);
        echo 'coucou</br>';*/

?>