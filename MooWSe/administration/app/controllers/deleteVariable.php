<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'une variable d'une fonction de MooWse de la base de données

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


// Booléen pour vérifier la bonne suppression du sous-type
$deleted = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête GET
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Vérification de la présence de l'id de la variable à supprimer
        if (isset($_GET['variable_id'])) {
            $variable_id = $_GET['variable_id'];
        }

        try {
            // Récupération des éléments de la variable
            $stmt = $bdd->prepare('SELECT * FROM variable WHERE variable_id=:variable_id');
            $stmt->bindParam(':variable_id', $variable_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $variable = $stmt->fetchAll();

            $variable_order = $variable[0]['variable_order'];
            $function_id = $variable[0]['function_id'];
            $variable_input = $variable[0]['variable_input'];

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Récupération des variables existantes de la fonction pour l'entrée (ou sortie)
            $stmt = $bdd->prepare('SELECT variable_order,variable_id FROM variable WHERE function_id=:function_id AND variable_input=:variable_input ORDER BY variable_order');
            $stmt->bindParam(':function_id', $function_id);
            $stmt->bindParam(':variable_input', $variable_input);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $orders = array();
            $ids = array();
            // Utilisation d'une boucle pour que le label des colonnes soit l'ordre
            while ($row = $stmt->fetch()) {
                $orders[] = $row['variable_order'];
                $ids[] = $row['variable_id'];
            }
            $variable_list = array_combine($orders, $ids);

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Suppression de la variable
            $stmt = $bdd->prepare("DELETE FROM variable WHERE variable_id=:variable_id");
            $stmt->bindParam(':variable_id', $variable_id);
            $deleted = $stmt->execute();

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Déplacement des variables dont l'order est égal ou supérieur à celle supprimée
            for ($i = $variable_order; $i < sizeof($variable_list) + 1; $i++) {

                $order = $i - 1;

                // Modification de la variable
                $stmt = $bdd->prepare("UPDATE variable SET variable_order=:order WHERE variable_id=:id");
                $stmt->bindParam(':order', $order);
                $stmt->bindParam(':id', $variable_list[$i]);
                $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();
            }
        } catch (Exception $e) {
            $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
        }
    }

    // Enregistrement du message d'alerte
    if ($deleted) {
        // La suppression a bien été effectuée
        // log de suppression d'une variable
        $loggerSuppr = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
        $loggerSuppr->info($_SESSION['login'] . " a supprimé la variable d'id " . $variable_id . " appartenant à la fonction d'id " . $function_id);
        $message = array(true, "La variable a bien &eacute;t&eacute; supprim&eacute;e");
    } else {
        // La suppression n'a pas été effectuée
        $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_fonction_variables.php?function_id=" . $function_id);
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&egrave;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}