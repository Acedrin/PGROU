<?php

class agap{

    function info($StudentNumber) {
        echo "Le numero d'etudiant est  " . $StudentNumber.'</br>' ;
		$Result='Il est cool';
		return $Result;
    }

    function notes($StudentLogin,$StudentPassword) {
		echo 'Login :  ' . $StudentLogin. ' : mdp '.$StudentPassword.'</br>' ;
		$Result='20/20';
    	return $Result;
    }

}


$URL = "http://localhost/moodle/agap.wsdl";
$server = new SoapServer($URL);
$server->setClass("agap");
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