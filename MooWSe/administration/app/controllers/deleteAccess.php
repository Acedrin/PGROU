<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'un droit d'accès de la base de données

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

// Booléen pour vérifier la bonne suppression du client
$deleted = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête GET
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Initialisation de l'id client et de la fonction
        $client_id = 0;
        $function_id = 0;
        
        // Vérification de la présence de l'id client à supprimer
        if (isset($_GET['client_id'])) {
            $client_id = $_GET['client_id'];
        }
        // Vérification de la présence de l'id fonction à supprimer
        if (isset($_GET['function_id'])) {
            $function_id = $_GET['function_id'];
        }        

        try {
            // Suppression du droit d'accès
            $stmt = $bdd->prepare("DELETE FROM access WHERE client_id=:client_id AND function_id=:function_id");
            $stmt->bindParam(':client_id', $client_id);
            $stmt->bindParam(':function_id', $function_id);
            $deleted = $stmt->execute();

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Gestion des exceptions
        } catch (Exception $e) {
            $message = array(false, "Une erreur a été rencontr&eacute;e lors de la suppression\nVeuillez r&eacute;essayer");
        }
    }

    // Enregistrement du message d'alerte
    if ($deleted) {
        // La suppression a bien été effectuée
        $message = array(true, "Le client a bien &eacute;t&eacute; supprim&eacute;");

        // log de suppression d'un client
        $loggerSuppr = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
        $loggerSuppr->info($_SESSION['login'] . " a supprimé le client d'id " . $client_id);
        
    } else {
        // La suppression n'a pas été effectuée
        $message = array(false, "Une erreur a été rencontr&eacute;e lors de la suppression\nVeuillez r&eacute;essayer");
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_clients.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_clients.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}