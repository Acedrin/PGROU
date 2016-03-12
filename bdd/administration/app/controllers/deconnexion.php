<?php

require ("../../vendor/autoload.php");

session_start();
ini_set("display_errors", 0);
error_reporting(0);
$logger = new Katzgrau\KLogger\Logger(__DIR__ . '/logs');
$logger->info("Deconnexion de " . $_SESSION['login']);
$_SESSION = array();
session_destroy();
header('Content-Type: text/html; charset=utf-8');
header("Location:../../index.php");
?>
