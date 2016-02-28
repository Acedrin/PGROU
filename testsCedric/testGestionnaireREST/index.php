<?php 
	require "gestionnaire.php";
	
	$root = end(explode('\\',getcwd()));
	$manager = new Gestionnaire($root);
	echo $manager->getAnswer();
?>