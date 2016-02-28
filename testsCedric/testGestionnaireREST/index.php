<?php 
	require "gestionnaire.php";
	
	$service = end(explode('\\',getcwd()));
	$manager = new Gestionnaire($service);
	echo $manager->getAnswer();
?>