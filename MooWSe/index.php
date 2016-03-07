<?php
	require "MooWSe.php";

	$MooWSe_WSDL = "http://localhost/github/PGROU/MooWSe/MooWSe.wsdl";
	$MooWSe_server = new SoapServer($MooWSe_WSDL);
	$MooWSe_server->setClass("MooWSe");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$MooWSe_server->handle();
	} else {
		echo "Ce serveur SOAP peut gérer les fonctions suivantes :<br/>";
		$functions = $MooWSe_server->getFunctions();
		foreach($functions as $function) {
			echo "- ".$function."<br/>";
		}
	}
?>