<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'un client de MooWse dans la base de données

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

// Booléen pour vérifier la bonne suppression de l'utilisateur
$added = false;
$edited = false;

// Booléen pour vérifier si le formulaire remis est correct
$correct = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Initialisation des variables du formulaire
        $client_id = 0;
        $function_id = 0;

        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
            if (isset($_POST['function_id'])) {
                $function_id = $_POST['function_id'];
                $correct = true;
            } else {
                $message = array(false, "Vous n'avez pas indiqu&eacute; la fonction concern&eacute;e");
            }
        } else {
            $message = array(false, "Vous n'avez pas indiqu&eacute; le client concern&eacute;");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si le couple function_id et client_id existe déjà, le droit est mis à 1
            // Sinon c'est un ajout

            try {
                // Recherche du couple function_id et client_id
                $stmt = $bdd->prepare("SELECT * FROM access WHERE client_id=:client_id AND function_id=:function_id");
                $stmt->bindParam(':client_id', $client_id);
                $stmt->bindParam(':function_id', $function_id);
                $stmt->execute();

                $exist = $stmt->fetch();

                // Fermeture de la connexion
                $stmt->closeCursor();

                if (!($exist == false)) {
                    // Le couple est déjà existant
                    try {
                        // Ajout du droit d'accès
                        $stmt = $bdd->prepare("UPDATE access SET access_right=1 WHERE client_id=:client_id AND function_id=:function_id)");
                        $stmt->bindParam(':client_id', $client_id);
                        $stmt->bindParam(':function_id', $function_id);
                        $added = $stmt->execute();

                        // log d'ajout d'un droit d'accès
                        $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                        $loggerAjout->info($_SESSION['login'] . " a ajouté le droit d'accès au client " . $client_name . " d'adresse ip " . $client_ip . " pour la fonction " . $function_id);

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($added) {
                            // Le droit d'accès a bien été ajouté
                            $message = array(true, "Le droit d'acc&egrave;s a bien &eacute;t&eacute; ajout&eacute;");
                        } else {
                            $message = array(false, "Erreur lors de l'ajout du droit d'acc&egrave;s\nVeuillez r&eacute;essayer");
                        }

                        // Gestion des exceptions
                    } catch (Exception $e) {
                        $message = array(false, "Erreur lors de l'ajout du droit d'acc&egrave;s\nVeuillez r&eacute;essayer");
                    }
                }


                if ($added) {
                    // Le droit d'accès a bien été ajouté
                    $message = array(true, "Le droit d'acc&egrave;s a bien &eacute;t&eacute; ajout&eacute;");
                } else {
                    $message = array(false, "Erreur lors de l'ajout du droit d'acc&egrave;s\nVeuillez r&eacute;essayer");
                }

                // Gestion des exceptions
            } catch (Exception $e) {
                $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des droits existants\nVeuillez r&eacute;essayer");
            }

            if ($exist == false) {
                try {
                    // Ajout du droit d'accès
                    $stmt = $bdd->prepare("INSERT INTO access(client_id, function_id, access_right) VALUES (:client_id, :function_id, :access_right)");
                    $stmt->bindParam(':client_id', $client_id);
                    $stmt->bindParam(':function_id', $function_id);
                    $stmt->bindParam(':access_right', 1);
                    $added = $stmt->execute();

                    // log d'ajout d'un droit d'accès
                    $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerAjout->info($_SESSION['login'] . " a ajouté le droit d'accès au client " . $client_name . " d'adresse ip " . $client_ip . " pour la fonction " . $function_id);

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if ($added) {
                        // Le droit d'accès a bien été ajouté
                        $message = array(true, "Le droit d'acc&egrave;s a bien &eacute;t&eacute; ajout&eacute;");
                    } else {
                        $message = array(false, "Erreur lors de l'ajout du droit d'acc&egrave;s\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout du droit d'acc&egrave;s\nVeuillez r&eacute;essayer");
                }
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    // Passage par le controlleur getUsers.php pour avoir la liste des administrateurs
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_clients.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}