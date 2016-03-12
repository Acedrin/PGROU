<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'un administrateur de MooWse de la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

// Booléen pour vérifier la bonne suppression de l'utilisateur
$deleted = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête POST
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Initialisation de l'id utilisateur
        $user_id = 0;

        // Vérification de la présence de l'id utilisateur à supprimer
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
        }

        try {
            // Suppression de l'utilisateur
            $stmt = $bdd->prepare("DELETE FROM user WHERE user_id=:user_id");
            $stmt->bindParam(':user_id', $user_id);
            $deleted = $stmt->execute();

            // Fermeture de la connexion
            $stmt->closeCursor();

        // Gestion des exceptions
        } catch (Exception $e) {
            $message = array(false,"Une erreur a été rencontrée lors de la suppression.\nVeuillez réessayer");
        }
    }
    
    // Enregistrement du message d'alerte
    if ($deleted) {
        // La suppression a bien été effectuée
        $message = array(true,"L'utilisateur a bien été supprimé");
    } else {
        // La suppression n'a pas été effectuée
        $message = array(false,"Une erreur a été rencontrée lors de la suppression.\nVeuillez réessayer");
    }
    
    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_administrateurs.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false,"Connectez-vous pour accéder à cette ressource");
    $_SESSION['alert'] = $message;
    
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}