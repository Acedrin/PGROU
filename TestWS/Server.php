<?php

class HelloWorld {

    function hello($name) {
        return "Hello from " . $name;
    }

}

$URL = "http://localhost/github/PGROU/TestWS/test.wsdl";
$server = new SoapServer($URL);
$server->setClass("HelloWorld");
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