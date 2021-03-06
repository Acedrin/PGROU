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

// Booléen pour vérifier le bon ajout du client
$added = false;
$edited = false;

// Booléen pour vérifier si le formulaire remis est correct
$correct = false;

// Booléen pour vérifier si c'est une demande de modification de mot de passe
$password = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Initialisation des variables du formulaire
        $client_name = "";
        $client_ip = "";
        $modality_id = 0;
        $client_password = "";

        // Vérification de la présence de l'id client à modifier le cas échéant
        if (isset($_POST['client_id'])) {
            $client_id = $_POST['client_id'];
        }

        // Vérification d'un ajout
        if (!isset($client_id)) {
            // C'est un ajout
            // Vérification du champ "client_name"
            if (isset($_POST['client_name'])) {
                $client_name = htmlspecialchars($_POST['client_name']);

                // Vérification du champ "client_ip"
                if (isset($_POST['client_ip'])) {
                    $client_ip = htmlspecialchars($_POST['client_ip']);

                    // Vérification du champ "modality_id"
                    if ($_POST['modality_id'] > 0) {
                        $modality_id = htmlspecialchars($_POST['modality_id']);

                        // Vérification si les deux mots de passes sont égaux
                        if (($_POST['client_password'] == $_POST['client_password_confirmation'])) {
                            $client_password = sha1($_POST['client_password']);
                            $correct = true;
                        } else {
                            // Les deux mots de passes ne sont pas identiques
                            $message = array(false, "Erreur - Les mots de passes tapés sont différents\nVeuillez recommencer");
                        }
                    } else {
                        // L'id de modalité n'est pas fixé
                        $message = array(false, "Vous n'avez pas indiqué la modalité de connexion du client à ajouter");
                    }
                } else {
                    // L'ip n'est pas fixé
                    $message = array(false, "Vous n'avez pas indiqué l'ip du client à ajouter");
                }
            } else {
                // Le nom n'est pas fixé
                $message = array(false, "Vous n'avez pas indiqué le nom du client à ajouter");
            }
        } else if (!isset($_POST['client_password']) && isset($client_id)) {
            // C'est une modification
            // Vérification du champ "client_name"
            if (isset($_POST['client_name'])) {
                $client_name = htmlspecialchars($_POST['client_name']);

                // Vérification du champ "client_ip"
                if (isset($_POST['client_ip'])) {
                    $client_ip = htmlspecialchars($_POST['client_ip']);

                    // Vérification du champ "modality_id"
                    if ($_POST['modality_id'] > 0) {
                        $modality_id = htmlspecialchars($_POST['modality_id']);
                        $correct = true;
                    } else {
                        // L'id de modalité n'est pas fixé
                        $message = array(false, "Vous n'avez pas indiqué la modalité de connexion du client à ajouter");
                    }
                } else {
                    // L'ip n'est pas fixé
                    $message = array(false, "Vous n'avez pas indiqué l'ip du client à ajouter");
                }
            } else {
                // Le nom n'est pas fixé
                $message = array(false, "Vous n'avez pas indiqué le nom du client à ajouter");
            }
        } else if (isset($_POST['client_password']) && isset($client_id)) {
            // Modification du mot de passe d'un client existant
            // Vérification si les deux mots de passes sont égaux
            if (($_POST['client_password'] == $_POST['client_password_confirmation'])) {
                $client_password = sha1($_POST['client_password']);
                $correct = true;
                $password = true;
            } else {
                // Les deux mots de passes ne sont pas identiques
                $message = array(false, "Erreur - Les mots de passes tapés sont différents\nVeuillez recommencer");
            }
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si l'id du client existe et le booléen $password est faux, c'est une modification
            // Si par contre le booléen $password est vrai, c'est une modification du mot de passe
            // Sinon c'est un ajout
            if (isset($client_id) && !$password) {
                try {
                    // Modification du client
                    $stmt = $bdd->prepare("UPDATE client SET client_name=:client_name, client_ip=:client_ip, modality_id=:modality_id WHERE client_id=:client_id");
                    $stmt->bindParam(':client_name', $client_name);
                    $stmt->bindParam(':client_ip', $client_ip);
                    $stmt->bindParam(':modality_id', $modality_id);
                    $stmt->bindParam(':client_id', $client_id);
                    $edited = $stmt->execute();

                    // log de modification d'un client
                    $loggerModif = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerModif->info($_SESSION['login'] . " a modifié le client d'id " . $client_id . ". Nouveau nom:" . $client_name . ". Nouvelle adresse ip: " . $client_ip);

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if ($edited) {
                        // Le client a bien été édité
                        $message = array(true, "Le client a bien &eacute;t&eacute; modifi&eacute;");
                    } else {
                        $message = array(false, "Erreur lors de la modification du client\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la modification du client\nVeuillez r&eacute;essayer");
                }
            } else if (isset($client_id) && $password) {
                try {
                    // Modification du mot de passe du client
                    $stmt = $bdd->prepare("UPDATE client SET client_password=:client_password WHERE client_id=:client_id");
                    $stmt->bindParam(':client_password', $client_password);
                    $stmt->bindParam(':client_id', $client_id);
                    $edited = $stmt->execute();

                    // log d'ajout d'un client
                    $loggerModif = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerModif->info($_SESSION['login'] . " a modifié le mot de passe du client d'id " . $client_id);

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if ($edited) {
                        // Le client a bien été édité
                        $message = array(true, "Le mot de passe du client a bien &eacute;t&eacute; modifi&eacute;");
                    } else {
                        $message = array(false, "Erreur lors de la modification du mot de passe du client\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la modification du mot de passe du client\nVeuillez r&eacute;essayer");
                }
            } else {
                try {
                    // Ajout du client
                    $stmt = $bdd->prepare("INSERT INTO client(client_name, client_ip, modality_id, client_password) VALUES (:client_name, :client_ip, :modality_id, :client_password)");
                    $stmt->bindParam(':client_name', $client_name);
                    $stmt->bindParam(':client_ip', $client_ip);
                    $stmt->bindParam(':modality_id', $modality_id);
                    $stmt->bindParam(':client_password', $client_password);
                    $added = $stmt->execute();

                    // log d'ajout d'un client
                    $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerAjout->info($_SESSION['login'] . " a ajouté le client " . $client_name . " d'adresse ip " . $client_ip);

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if ($added) {
                        // Le client a bien été ajouté
                        $message = array(true, "Le client a bien &eacute;t&eacute; ajout&eacute;");
                    } else {
                        $message = array(false, "Erreur lors de l'ajout du client\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout du client\nVeuillez r&eacute;essayer");
                }
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
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