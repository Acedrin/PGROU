<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Connexion à l'interface d'administration de la base de donnees

  Quentin Payet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

require 'vendor/autoload.php';

// on desactive l'affichage des erreurs pour ameliorer la securite et l'ergonomie

session_start(); // On démarre la session AVANT toute chose
ini_set("display_errors", 0);
error_reporting(0);

$restart = true;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère le login et le mot de passe entres par l'utilisateur
    $login = "";
    $password = "";

    // on vérifie que l'utilisateur a bien rentre un login et un mot de passe
    // on supprime l'interpretation des balises
    if (isset($_POST["login"])) {
        $login = htmlspecialchars($_POST["login"]);
    }
    if (isset($_POST["password"])) {
        $password = htmlspecialchars($_POST["password"]);
    }

    putenv('LDAPTLS_REQCERT=never');

    $Serveur = "ldaps://ldaps.nomade.ec-nantes.fr:636";
    $Liaison_LDAP = ldap_connect($Serveur);
    if ($Liaison_LDAP) {
// le serveur est accessible
        $LDAP_DN = "uid=$login,ou=people,dc=ec-nantes,dc=fr";
        $LDAPBind_User = ldap_bind($Liaison_LDAP, $LDAP_DN, $password);
        if ($LDAPBind_User) {
            // couple identifiant/login reconnu par LDAP
            // dans ce cas controle au niveau de la base de donnees
            try {
                // On se connecte à MySQL
                $bdd = new PDO('mysql:host=localhost;dbname=moowse;charset=utf8', 'root', '');
            } catch (Exception $e) {
                // En cas d'erreur, on affiche un message et on arrête tout
                die('Erreur : ' . $e->getMessage());
            }
            $query = $bdd->prepare('SELECT user_uid FROM user WHERE user_uid=?');
            $query->execute(array($login));
            $rows = $query->fetch();

            if ($rows['user_uid'] == $login) {
                $restart = false;
                $_SESSION['login'] = $login;
                $logger = new Katzgrau\KLogger\Logger(__DIR__.'/logs');
                $logger->info("Connexion de ".$login. " depuis l'adresse ". $_SERVER["REMOTE_ADDR"]);
            }
            $query->closeCursor();
        }
        ldap_close($Liaison_LDAP);
    }
}

if ($restart) {
    // La page
    header('Content-Type: text/html; charset=utf-8');
    include "index.html";
} else {
    // Connexion valide
// redirection vers la page base.html
    header('Content-Type: text/html; charset=utf-8');
    header("Location:accueil.php");
}
?>
