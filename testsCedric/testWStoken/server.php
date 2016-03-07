<?php
	class HelloWorld {
		private $_username = "login";
		private $_password = "password";
		private $_authenticated = false;
		private $_tokenTTL = 1;
		public function Security($Security) {
			if(isset($Security->UsernameToken->Username) && isset($Security->UsernameToken->Password)) {
				if($Security->UsernameToken->Username == $this->_username && $Security->UsernameToken->Password == $this->_password) {
					$this->_authenticated = true;
				}
			} elseif(isset($Security->BinarySecurityToken)) {
				//sleep($this->_tokenTTL+1);
				session_start();
				if(isset($_SESSION["token"]) && isset($_SESSION["token_time"])) {
					if($_SESSION["token"] == $Security->BinarySecurityToken) {
						if($_SESSION["token_time"] >= (time() - $this->_tokenTTL)) {
							$this->_authenticated = true;
						}
					}
				}
			}
		}
		public function authenticate() {
			$token = "ERROR !";
			if ($this->_authenticated) {
				session_start();
				$time = time();
				$token = uniqid(rand(), true);
				$_SESSION["token"] = $token;
				$_SESSION["token_time"] = $time;
			}
			return $token;
		}
		public function hello($name) {
			$answer = "ERROR !";
			if($this->_authenticated) {
				$answer = "Hello from " . $name;
			}
			return $answer;
		}
	}
	
	$URL = "http://localhost/github/PGROU/testsCedric/testWStoken/test.wsdl";
	$server = new SoapServer($URL);
	$server->setClass("HelloWorld");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$server->handle();
		
		
	} else {
		echo "Ce serveur SOAP peut gérer les fonctions suivantes : ";
		$functions = $server->getFunctions();
		foreach($functions as $func) {
			echo $func . "\r";
		}
	}
?>