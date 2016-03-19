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
            // Récupération des serveurs demandé
            $stmt = $bdd->prepare('SELECT * FROM server WHERE server_id=:server_id');
            $stmt->bindParam(':server_id', $server_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $server = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration du serveur\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else {
        // Récupération de tous les serveurs
        try {
            // Récupération de tous les serveurs
            $stmt = $bdd->prepare('SELECT * FROM server');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $servers = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Récupération du nombre de fonctions par serveur
            $stmt = $bdd->prepare('SELECT server_id,COUNT(*) as nb FROM function GROUP BY server_id');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $server_id_nb = array();
            $function_nb = array();
            // Utilisation d'une boucle pour que le label des colonnes soit l'id
            while ($row = $stmt->fetch()) {
                $server_id_nb[] = $row['server_id'];
                $function_nb[] = $row['nb'];
            }
            $nbFunctions = array_combine($server_id_nb, $function_nb);

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des serveurs\nVeuillez r&eacute;essayer");

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