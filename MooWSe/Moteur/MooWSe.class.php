<?php

//classe d'Elliot
require "generateWSDL.php";
//classe de Quentin
require "dataBaseCall.php";
//fichier avec data de configuration
require "settings.php"; //---> Insert giano

class MooWSe {

    private $_client_name;
    private $_client_access;
    private $_clientAuthenticated = false;
    private $_tokenChecked = false;
    private $_tokenTimeToLive = 1;

    public function Security($Security) {

        if (isset($Security->UsernameToken->Username) && isset($Security->UsernameToken->Password) && isset($Security->UsernameToken->Nonce) && isset($Security->UsernameToken->Created)) {
            list($client_name, $client_access) = explode(",", $Security->UsernameToken->Username);
            $client_password_digest = $Security->UsernameToken->Password;
            $client_salt = $Security->UsernameToken->Salt;
            $client_nonce = $Security->UsernameToken->Nonce;
            $client_created = $Security->UsernameToken->Created;
            $client_IP = $_SERVER["REMOTE_ADDR"];

            //on regarde si le client est enregistr�, appel de base

            $checkingDatas = new dataBaseCall('localhost', 'webservices', 'utf8', 'root', '');

            //$checkingDatas = new dataBaseCall($dbms_address, $db, 'utf8', $user, $passwd);

            $registered = $checkingDatas->clientRegistered($client_name,$client_nonce, $client_created, $client_access, $client_password_digest, $client_IP);

//si l'authentification est r�usssie
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
            $action = "getWSDL";

            //renvoyer la liste des fonctions auxquelles l'utilisateur a acc�s 
            //appel � la base
            if ($this->_tokenChecked) {
                //connexion � la base de donn�es 

                $checkingDatas = new dataBaseCall('localhost', 'webservices', 'utf8', 'root', ''); 

                //$gettingDatas = new dataBaseCall($dbms_address, $db, 'utf8', $user, $passwd);
                $functions = $gettingDatas->listFunction($client_name,$service);
            }
        }

        //générateur : (service,fonctions)->WSDL
        //$service_WSDL = "<" . $service . ">" . implode(",", $functions) . "</" . $service . ">";
        $service_WSDL = generateWSDL($functions);
        //file_put_contents("test.wsdl",$service_WSDL_test);
        return htmlspecialchars($service_WSDL, ENT_XML1);
    }

}

?>