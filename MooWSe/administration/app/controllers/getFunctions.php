<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection fonctions des serveurs de MooWse dans la base de données

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
    // Vérification d'une demande d'information sur un serveur particulier
    if (isset($server_id)) {

        try {
            // Récupération des fonctions du serveur demandé
            $stmt = $bdd->prepare('SELECT function.function_id,server.server_id,function.function_name,server.server_name '
                    . 'FROM server '
                    . 'INNER JOIN function ON server.function_id=function.function_id '
                    . 'WHERE server.server_id=:server_id '
                    . 'ORDER BY server.server_name,function.function_name');
            $stmt->bindParam(':server_id', $server_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $functions = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des fonctions du client\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else if (isset($function_id)) {
        // Vérification d'une demande d'information sur une fonction particulière
        try {
            // Récupération des informations de la fonction
            $stmt = $bdd->prepare('SELECT function.function_id,server.server_id,function.function_name,server.server_name '
                    . 'FROM function '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'WHERE function.function_id=:function_id '
                    . 'ORDER BY server.server_name,function.function_name');
            $stmt->bindParam(':function_id', $function_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $function = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des informations de la fonction\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else {
        // Récupération de toutes les fonctions
        try {
            // Récupération de toutes les fonctions
            $stmt = $bdd->prepare('SELECT function.function_id,server.server_id,function.function_name,server.server_name '
                    . 'FROM function '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'ORDER BY server.server_name,function.function_name');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $functions = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des droits d'acc&agrave;s &agrave; la fonction\nVeuillez r&eacute;essayer");

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