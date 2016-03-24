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

//file_put_contents("agap2.wsdl",generateWSDL([1,2]));
$URL = "http://localhost/github/PGROU/MooWSe/Moteur/Tests/Serveurs/agap.wsdl";
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