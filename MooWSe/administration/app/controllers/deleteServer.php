<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'un serveur de MooWse et ses fonctions de la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

//gestion des logs
require ("../../vendor/autoload.php");


// Booléen pour vérifier la bonne suppression du serveur
$deleted = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête GET
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Vérification de la présence de l'id du serveur à supprimer
        if (isset($_GET['server_id'])) {
            $server_id = $_GET['server_id'];
        }

        // Récupération des fonctions liées au serveur
        try {
            // Récupération des fonctions du serveur demandé
            $stmt = $bdd->prepare('SELECT function_id FROM function WHERE server.server_id=:server_id');
            $stmt->bindParam(':server_id', $server_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $functions = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Suppression de chaque fonction
            for ($i = 0; $i < sizeof($functions); $i++) {
                // Suppression des variables
                $stmt = $bdd->prepare("DELETE FROM variable WHERE function_id=:function_id");
                $stmt->bindParam(':function_id', $functions[$i]['function_id']);
                $deleted = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();

                // Suppression de la fonction
                $stmt = $bdd->prepare("DELETE FROM function WHERE function_id=:function_id");
                $stmt->bindParam(':function_id', $functions[$i]['function_id']);
                $deleted = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();
            }

            // Suppression du serveur
            $stmt = $bdd->prepare("DELETE FROM server WHERE server_id=:server_id");
            $stmt->bindParam(':server_id', $server_id);
            $deleted = $stmt->execute();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
        }
    }

    // Enregistrement du message d'alerte
    if ($deleted) {
        // La suppression a bien été effectuée
        // log de suppression d'une fonction
        $loggerSuppr = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
        $loggerSuppr->info($_SESSION['login'] . " a supprimé le serveur d'id" . $server_id);
        $message = array(true, "Le serveur a bien &eacute;t&eacute; supprim&eacute;e");
    } else {
        // La suppression n'a pas été effectuée
        $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_fonctions.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&egrave;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}