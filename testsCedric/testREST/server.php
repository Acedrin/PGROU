<?php require 'RestServer.php';
	class HelloWorld {
		function hello($name) {
			return "Hello from " . $name;
		}
	}
	
	
	$server = new RestServer();
	$server->setClass("HelloWorld");
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$server->handle();
		
		
	} else {
		echo "Ce serveur REST peut gérer les fonctions suivantes : ";
		$functions = $server->getFunctions();
		foreach($functions as $func) {
			echo $func . "\r";
		}
	}
?>