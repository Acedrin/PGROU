<?php
	class HelloWorld {
		function hello($name) {
			return "Hello from " . $name;
		}
	}
	
	$URL = "http://localhost/github/PGROU/testsCedric/testWS/test.wsdl";
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