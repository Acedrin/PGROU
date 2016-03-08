<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection de tous les administrateurs de MooWse dans la base de données
  Appel de la vue gestion_administrateurs

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

// Connexion à la base de données
require("../bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {
    try {
        // Récupération de tous les utilisateurs
        $stmt = $bdd->prepare('SELECT * FROM user');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Enregistrement du résultat dans un tableau
        $users = $stmt->fetchAll();

        // Fermeture de la connexion
        $stmt->closeCursor();

// Traitement des exceptions
    } catch (Exception $e) {
        $message = array(false, "Erreur lors de la récupération des administrateurs./nVeuillez rééssayer");

        // Enregistrement du message
        $_SESSION['alert'] = $message;
    }
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour accéder à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:index.html");
}