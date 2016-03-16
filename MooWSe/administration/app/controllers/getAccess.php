<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection des droits d'accès des clients de MooWse dans la base de données

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
    // Vérification d'une demande d'information sur un client particulier
    if (isset($client_id)) {

        try {
            // Récupération des droits d'accès du client
            $stmt = $bdd->prepare('SELECT access.function_id,function.server_id,function.function_name,server.server_name '
                    . 'FROM access '
                    . 'INNER JOIN client ON access.client_id=client.client_id '
                    . 'INNER JOIN modality ON client.modality_id=modality.modality_id '
                    . 'INNER JOIN function ON access.function_id=function.function_id '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'WHERE access.client_id=:client_id AND access.access_right=1 ORDER BY server.server_name');
            $stmt->bindParam(':client_id', $client_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $access = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des droits d'acc&agrave;s du client\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else if (isset($function_id)) {
        // Vérification d'une demande d'information sur une fonction particulière
        try {
            // Récupération des droits d'accès à la fonction
            $stmt = $bdd->prepare('SELECT access.client_id,access.function_id,function.server_id,function.function_name,server.server_name,client.client_name,client.client_ip,client.modality_id,modality.modality_name '
                    . 'FROM access '
                    . 'INNER JOIN client ON access.client_id=client.client_id '
                    . 'INNER JOIN modality ON client.modality_id=modality.modality_id '
                    . 'INNER JOIN function ON access.function_id=function.function_id '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'WHERE access.function_id=:function_id AND access.access_right=1');
            $stmt->bindParam(':function_id', $function_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $access = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des droits d'acc&agrave;s &agrave; de la fonction\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    }
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}