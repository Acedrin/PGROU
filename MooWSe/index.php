<?php
	require ".\Moteur\MooWSe.class.php";

	$MooWSe_WSDL = "http://localhost/github/PGROU/MooWSe/MooWSe.wsdl";
	$MooWSe_server = new SoapServer($MooWSe_WSDL);
	$MooWSe_server->setClass("MooWSe");
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$MooWSe_server->handle();
	} else {
		echo file_get_contents($MooWSe_WSDL);
	}
?>