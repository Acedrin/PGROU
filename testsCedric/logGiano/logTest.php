<?php

include('Logger.php');
include('logRotate.php');

/*$bar = new Logger();


$bar->LogError("NOME","127.0.0.1","token","error");
$bar->LogFunc("NOME","127.0.0.1","token","function");
$bar->LogUser("NOME","127.0.0.1","token");*/

$prova=new LogRotate('./logs/userLog');
$prova->maxAgedFile('./logs/userLog');

echo "</br>logTest";

?>