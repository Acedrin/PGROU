<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection de tous les administrateurs de MooWse dans la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification d'une requête GET (= demande d'information sur un admnistrateur particulier)
    if (isset($user_id)) {
        try {
            // Récupération de l'utilisateur
            $stmt = $bdd->prepare('SELECT * FROM user WHERE user_id=:user_id');
            $stmt->bindParam(':user_id', $user_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $user = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration de l'utilisateur\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else {
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
            $message = array(false, "Erreur lors de la récupération des administrateurs.\nVeuillez rééssayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    }
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour accéder à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}