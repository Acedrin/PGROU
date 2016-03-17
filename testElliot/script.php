<?php

/*
 * Générateur WSDL
 * Author : Elliot Catherin
 */

include('generateWSDL.php');




$array=[1];
$wsdl = generateWSDL($array);

echo $wsdl;



