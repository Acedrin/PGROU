<?php 
	require "gestionnaire.php";
	
	$path = end(explode('\\',getcwd()));
	$manager = new Gestionnaire($path);
	echo $manager->getAnswer()."\n";
	/*
	print "<pre>";
	print_r($_SERVER);
	print "</pre>";*/
?>