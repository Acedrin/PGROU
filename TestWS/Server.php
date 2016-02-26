<?php

class HelloWorld {

    function hello($name) {
        return "Hello from " . $name;
    }

    function goodbye($name) {
    	return "Goodbye from " . $name;
    }

    function somme($a,$b) {
        $c = Calculatrice::somme($a,$b);
        return $c;
    }
}

class Calculatrice {

	function somme($a,$b) {
		return $a + $b;
	}
}

$URL = "http://localhost/github/PGROU/TestWS/test.wsdl";
$server = new SoapServer($URL);
$server->setClass("HelloWorld");
//$server->setClass("Calculatrice");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $server->handle();
} else {
    echo "Ce serveur SOAP peut g&eacute;rer les fonctions suivantes : ";
    $functions = $server->getFunctions();
    foreach ($functions as $func) {
        echo $func . "\r";
    }
}
?>