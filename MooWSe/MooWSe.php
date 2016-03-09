<?php
	class MooWSe {
		private $_clientAuthenticated = false;
		private $_tokenChecked = false;
		private $_tokenTimeToLive = 1;
		public function Security($Security) {
                        //ajout d'une condition sur le sel
			if(isset($Security->UsernameToken->Username) && isset($Security->UsernameToken->Password)&&isset($Security->UsernameToken->Salt)) {
				//ligne à commenter
                                list($client_name,$client_access) = explode(",",$Security->UsernameToken->Username); 
				$client_password = $Security->UsernameToken->Password;
                                //sel ajouté
                                $client_salt=$Security->UsernameToken->Salt;
				$client_IP = $_SERVER["REMOTE_ADDR"];
				
				//autorisations : (client,modalite,password,ip)->enregistrÃ©?
				$registered = 
					$client_name == "client" && 
					$client_access == "modalite" && 
					$client_password == "password" && 
					$client_IP == "::1"
				;
				if($registered) {
					
					session_start();
					$_SESSION["name"] = $client_name;
					$_SESSION["access"] = $client_access;
					
					$this->_clientAuthenticated = true;
				}
			} elseif(isset($Security->BinarySecurityToken)) {
				session_start();
				$token = $_SESSION["token"];
				$token_time = $_SESSION["token_time"];
				
				//sleep($this->_tokenTTL+1);
				if(isset($token) && isset($token_time)) {
					if($token == $Security->BinarySecurityToken) {
						if($token_time >= (time() - $this->_tokenTimeToLive)) {
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
				session_start();
				$client_name = $_SESSION["name"];
				$client_access = $_SESSION["access"];
				$action = "authenticate";
				
				while (!$crypto_strong) {
					$token = bin2hex(openssl_random_pseudo_bytes(8,$crypto_strong));
					$token_time = time();
				}
				
				session_start();
				$_SESSION["token"] = $token;
				$_SESSION["token_time"] = $time;
			}
			return $token;
		}
		public function getWSDL($service) {
			$service_WSDL = "ERROR !";
			if($this->_tokenChecked) {
				
				//logs : (ip,date,client,modalite,service,action)->log
				$client_IP = $_SERVER["REMOTE_ADDR"];
				$time = time();
				session_start();
				$client_name = $_SESSION["name"];
				$client_access = $_SESSION["access"];
				$service;
				$action = "getWSDL";
				
				//autorisations : (client,modalite,ip)->fonctions
				if ($client_name == "client" && $client_access == "modalite" && $client_IP == "::1") {
					$functions = ["fonction1","fonction2"];
				}
				
				//gÃ©nÃ©rateur : (service,fonctions)->WSDL
				$service_WSDL = "<".$service.">".implode(",",$functions)."</".$service.">";
			}
			return htmlspecialchars($service_WSDL,ENT_XML1);
		}
	}
?>