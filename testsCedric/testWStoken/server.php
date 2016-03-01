<?php
	class HelloWorld {
		private $_token;
		function setToken($token) {
			$this->_token = $token;
		}
		function hello($name) {
			$token = $this->_token;
			$temps = 1;
			//sleep($temps+1);
			$answer = "ERROR !";
			session_start();
			if(isset($_SESSION["token"]) && isset($_SESSION["token_time"]) && isset($token)) {
				if($_SESSION["token"] == $token) {
					if($_SESSION["token_time"] >= (time() - $temps)) {
						$answer = "Hello from " . $name;
					}
				}
			}
			return $answer;
		}
		function getToken() {
			session_start();
			$token = uniqid(rand(), true);
			$_SESSION["token"] = $token;
			$_SESSION["token_time"] = time();
			return $token;
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