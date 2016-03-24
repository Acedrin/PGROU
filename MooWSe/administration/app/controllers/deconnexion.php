<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Deconnexion d'un administrateur

  Quentin Payet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

require ("../../vendor/autoload.php");

session_start();
ini_set("display_errors", 0);
error_reporting(0);
$logger = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
$logger->info("Deconnexion de " . $_SESSION['login']);
$_SESSION = array();
session_destroy();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $_SESSION['alert'] = array(true, "D&eacute;connexion r&eacute;ussie.\nA bient&ocirc;t !");
} else if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $_SESSION['alert'] = array(true, "Temps de connexion d&eacute;pass&eacute;.\nVeuillez vous reconnecter.");
}
header('Content-Type: text/html; charset=utf-8');
header("Location:../../index.php");
?>
