<?php

include('Logger.php');

$bar = new Logger();


$bar->LogError("127.0.0.1","client_name","error");
//$bar->LogServ("127.0.0.1","clent_name","modality","service","action");
//$bar->LogClient("127.0.0.1","clent_name","modality","action");

//$bar->maxAgedFile('./logs/userLog');

//$bar->variableOnFile("read");
echo "</br>logTest";

?>