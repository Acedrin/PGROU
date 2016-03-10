<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection des modalités d'accès des clients de MooWse

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

    // Vérification d'une requête GET (= demande d'information sur une modalité particulière)
    if (isset($modality_id)) {
        
        try {
            // Récupération de la modalité
            $stmt = $bdd->prepare('SELECT * FROM modality WHERE modality_id=:modality_id');
            $stmt->bindParam(':modality_id', $modality_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $modality = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration de la modalit&eacute;\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else {
        // La requête cherche à obtenir toutes les modalités existantes
        try {
            // Récupération de toutes les modalités
            $stmt = $bdd->prepare('SELECT * FROM client');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $modalities = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

// Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des modalit&eacute;s\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
        
        try {
        // Récupération du nombre de clients par modalité
        $stmt = $bdd->prepare('SELECT COUNT( * ) as nb_clients FROM client GROUP BY modality_id');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Enregistrement du résultat dans un tableau
        $nb_clients = $stmt->fetchAll();

        // Fermeture de la connexion
        $stmt->closeCursor();

// Traitement des exceptions
    } catch (Exception $e) {
        $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des modalit&eacute;s\nVeuillez r&eacute;essayer\n" . $message[1]);
    }
        // Enregistrement du message
        $_SESSION['alert'] = $message;
    }
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:/index.html");
}