<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'un serveur de MooWse dans la base de données

  Christophe Cleuet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

//gestion des logs
require ("../../vendor/autoload.php");

// Booléen pour vérifier le bon ajout du serveur
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

        // Vérification de la présence de l'id serveur à modifier le cas échéant
        if (isset($_POST['server_id'])) {
            $server_id = $_POST['server_id'];
        }

        // Vérification du champ "server_name"
        if (isset($_POST['server_name'])) {
            $server_name = htmlspecialchars($_POST['server_name']);

            // Vérification du champ "server_soapadress"
            if (isset($_POST['server_soapadress'])) {
                $server_soapadress = htmlspecialchars($_POST['server_soapadress']);
                $correct = true;
            } else {
                // L'adresse SOAP n'est pas fixée
                $message = array(false, "Vous n'avez pas indiqu&eacute; l'adresse SOAP du serveur &agrave; ajouter");
            }
        } else {
            // Le nom n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; le nom du serveur &agrave; ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si l'id du serveur existe, c'est une modification
            // Sinon c'est un ajout
            if (isset($server_id)) {
                try {
                    // Modification du serveur
                    $stmt = $bdd->prepare("UPDATE server SET server_name=:server_name, server_soapadress=:server_soapadress WHERE server_id=:server_id");
                    $stmt->bindParam(':server_name', $server_name);
                    $stmt->bindParam(':server_soapadress', $server_soapadress);
                    $stmt->bindParam(':server_id', $server_id);
                    $edited = $stmt->execute();

                    // log d'ajout d'un serveur
                    $loggerModif = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerModif->info($_SESSION['login'] . " a modifié le serveur d'id " . $server_id . ". Nouveau nom :" . $server_name . ". Nouvelle adresse SOAP : " . $server_soapadress);

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if ($edited) {
                        // Le serveur a bien été édité
                        $message = array(true, "Le serveur a bien &eacute;t&eacute; modifi&eacute;");
                    } else {
                        $message = array(false, "Erreur lors de la modification du serveur\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la modification du serveur\nVeuillez r&eacute;essayer");
                }
            } else {
                try {
                    // Ajout du serveur
                    $stmt = $bdd->prepare("INSERT INTO server(server_name, server_soapadress) VALUES (:server_name, :server_soapadress)");
                    $stmt->bindParam(':server_name', $server_name);
                    $stmt->bindParam(':server_soapadress', $server_soapadress);
                    $added = $stmt->execute();

                    // log d'ajout d'un serveur
                    $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerAjout->info($_SESSION['login'] . " a ajouté le serveur " . $server_name . " d'adresse SOAP " . $server_soapadress);

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if ($added) {
                        // Le serveur a bien été ajouté
                        $message = array(true, "Le serveur a bien &eacute;t&eacute; ajout&eacute;");
                    } else {
                        $message = array(false, "Erreur lors de l'ajout du serveur\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout du serveur\nVeuillez r&eacute;essayer");
                }
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_fonctions.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}