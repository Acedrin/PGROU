<?php

class moodle{

    function matiere($NumMatiere) {
        echo "La matiere " . $NumMatiere. 'est la meilleure!'.'</br>' ;
		$Result=42;
		return $Result;
    }

    function description($DesMatiere) {
		echo 'Description de ' . $DesMatiere. ' : bonne matiere '.'</br>' ;
		$Result=' : bonne matiere ';
    	return $Result;
    }

}


$URL = "http://localhost/github/PGROU/MooWSe/Moteur/Tests/Serveurs/moodle.wsdl";
$server = new SoapServer($URL);
$server->setClass("moodle");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $server->handle();
} else {
    echo "Ce serveur SOAP peut executer les fonctions suivantes : ";
    $functions = $server->getFunctions();
    foreach ($functions as $func) {
        echo $func . "\r";
    }
}
?>