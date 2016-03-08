<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection de tous les clients de MooWse dans la base de données

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
        // Récupération de tous les clients
        $stmt = $bdd->prepare('SELECT * FROM client');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Enregistrement du résultat dans un tableau
        $clients = $stmt->fetchAll();
        // Fermeture de la connexion
        $stmt->closeCursor();

// Traitement des exceptions
    } catch (Exception $e) {
        $message = array(false, "Erreur lors de la récupération des clients./nVeuillez rééssayer");

        // Enregistrement du message
        $_SESSION['alert'] = $message;
    }
    try {
        // Récupération de toutes les modalités
        $stmt = $bdd->prepare('SELECT * FROM modality');
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->execute();

        // Enregistrement du résultat dans un tableau
        $modality_id = array();
        $modality_name = array();
        // Utilisation d'une boucle pour que le label des colonnes soit l'id
        while ($row = $stmt->fetch()) {
            $modality_id[] = $row['modality_id'];
            $modality_name[] = $row['modality_name'];
        }
        $modalities = array_combine($modality_id, $modality_name);

        // Fermeture de la connexion
        $stmt->closeCursor();

// Traitement des exceptions
    } catch (Exception $e) {
        $message = array(false, "Erreur lors de la récupération des modalités./nVeuillez rééssayer" . $message[1]);

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