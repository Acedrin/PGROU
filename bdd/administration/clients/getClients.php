<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection de tous les clients de MooWse dans la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

// Connexion à la base de données
require("../bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification d'une requête GET (= demande d'information sur un client particulier)
    // Si la valeur est 0, alors la requête est seulement d'avoir les modalities
    if (isset($client_id) && $client_id != 0) {
        
        try {
            // Récupération du clients
            $stmt = $bdd->prepare('SELECT * FROM client WHERE client_id=:client_id');
            $stmt->bindParam(':client_id', $client_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $client = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration du client/nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else if (!isset($client_id)) {
        // La requête cherche à obtenir tous les clients existants
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
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des clients/nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
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
        $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des modalit&eacute;s/nVeuillez r&eacute;essayer/n" . $message[1]);

        // Enregistrement du message
        $_SESSION['alert'] = $message;
    }
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:index.html");
}