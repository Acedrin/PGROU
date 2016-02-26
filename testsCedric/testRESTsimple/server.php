<?php ;
	class HelloWorld {
		function hello($name) {
			return "Hello from " . $name;
		}
	}
	
	
	
	$instance = new HelloWorld();
	
	if ($_SERVER["REQUEST_METHOD"] == "GET") {
		$URI = $_SERVER['REQUEST_URI'];
		list($github,$PGROU,$folder,$subfolder,$function,$param) = explode('/', trim($URI, '/'));
		echo $instance->$function($param);
	} else {
		echo "Ce serveur SOAP peut gérer les fonctions suivantes : ";
		$functions = get_class_methods("HelloWorld");
		foreach($functions as $func) {
			echo $func . "\r";
		}
	}
?>