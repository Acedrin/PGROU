<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'un type de MooWse de la base de données

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


// Booléen pour vérifier la bonne suppression du type
$deleted = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête GET
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Vérification de la présence de l'id du type à supprimer
        if (isset($_GET['type_id'])) {
            $type_id = $_GET['type_id'];
        }

        // Récupération du type pour vérifier s'il est complexe
        try {
            // Récupération du type
            $stmt = $bdd->prepare("SELECT * FROM type WHERE type_id=:type_id");
            $stmt->bindParam(':type_id', $type_id);
            $type = $stmt->execute();

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Gestion des exceptions
        } catch (Exception $e) {
            $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
        }

        // Si le type est complexe, suppression de ses dépendances
        if ($type[0]['type_complex'] == 1) {
            try {
                // Suppression des dépendances
                $stmt = $bdd->prepare("DELETE FROM typecomplex WHERE typecomplex_depends=:type_id");
                $stmt->bindParam(':type_id', $type_id);
                $deleted = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();

                // Suppression du type
                $stmt = $bdd->prepare("DELETE FROM type WHERE type_id=:type_id");
                $stmt->bindParam(':type_id', $type_id);
                $deleted = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();

                // Gestion des exceptions
            } catch (Exception $e) {
                $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
            }
        } else {
            try {
                // Suppression du type
                $stmt = $bdd->prepare("DELETE FROM type WHERE type_id=:type_id");
                $stmt->bindParam(':type_id', $type_id);
                $deleted = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();

                // Gestion des exceptions
            } catch (Exception $e) {
                $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
            }
        }
    }

    // Enregistrement du message d'alerte
    if ($deleted) {
        // La suppression a bien été effectuée
        // log de suppression d'un type
        $loggerSuppr = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
        $loggerSuppr->info($_SESSION['login'] . " a supprimé le type d'id" . $type_id);
        $message = array(true, "Le type a bien &eacute;t&eacute; supprim&eacute;");
    } else {
        // La suppression n'a pas été effectuée
        $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_types.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&egrave;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}