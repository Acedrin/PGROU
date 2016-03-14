<?php 

require "generateWSDL.php";

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

            //connexion � la base de donn�es dont le nom est webservices, l'utilisateur root et sans mot de passe
            $bdd = new PDO('mysql:host=localhost;dbname=webservices;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            //recherche du client en base
            $recherche_client = $bdd->query('SELECT client_name,client_ip,client_password,modality_name FROM client INNER JOIN modality ON modality.modality_id=client.modality_id WHERE client_name=\'' . $client_name . '\'');
            //v�rification de table non vide (sinon le client n'existe pas
            if ($info_client = $recherche_client->fetch()) {
                //assignation des variables
                $client_database_name = $info_client['client_name'];
                $client_database_ip = $info_client['client_ip'];
                $client_database_password = $info_client['client_password'];
                $client_database_modality = $info_client['modality_name'];
                //mot de passe encrypt�
                $client_database_encrypted_password = base64_encode(sha1($client_nonce . $client_created . sha1($client_database_password)));
                //v�rification des informations en base
                $registered = $client_access == $client_database_modality &&
                        $client_database_encrypted_password = $client_password_digest &&
                        $client_IP = $client_database_ip &&
                        (($client_database_modality == $client_access)); 
                        
            } else { //le nom de client est incorrect
                $registered = False;
            }

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
            $service="service";
            $action = "getWSDL";

            //renvoyer la liste des fonctions auxquelles l'utilisateur a acc�s 
            //appel � la base
            if ($this->_tokenChecked) {
                //connexion � la base de donn�es 
                $bdd = new PDO('mysql:host=localhost;dbname=webservices;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
                //appel des fonctions autoris�es pour le client
                $function_request = $bdd->query('SELECT function.function_id FROM function INNER JOIN access ON function.function_id=access.function_id
                    INNER JOIN client ON access.client_id=client.client_id
                    INNER JOIN server ON server.server_id=function.server_id
                    WHERE client_name=\'' . $client_name . '\' ');
                //obtention du nombre de fonctions auxquelles 
                $number_function = $function_request->rowCount();
                //extraire le premier �l�ment                
                $current_function = $function_request->fetch();
                //creation d'un array
                $functions = array($current_function[0]);
                while($current_function = $function_request->fetch()){
                    //ajoute la fonction courante au array
                    array_push($functions,$current_function[0]);
                }                      
            }

            //générateur : (service,fonctions)->WSDL
            $service_WSDL = "<" . $service . ">" . implode(",", $functions) . "</" . $service . ">";
			$service_WSDL = generateWSDL([1,2]);
			//file_put_contents("test.wsdl",$service_WSDL_test);
        }
        return htmlspecialchars($service_WSDL, ENT_XML1);
    }

}

?>