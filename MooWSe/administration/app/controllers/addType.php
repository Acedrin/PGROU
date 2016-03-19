<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'un type de MooWse dans la base de données

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

// Booléen pour vérifier le bon ajout/édition du type
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

        // Vérification de la présence de l'id du type à modifier le cas échéant
        if (isset($_POST['type_id'])) {
            $type_id = $_POST['type_id'];
        }

        if (isset($_POST['type_name'])) {
            $type_name = htmlspecialchars($_POST['type_name']);
            $correct = true;
            
            if ($_POST['type_complex']) {
                $type_complex = 1;
            } else {
                $type_complex = 0;
            }
        } else {
            // Le nom n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; le nom du type à ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si l'id du type existe, c'est une modification
            // Sinon c'est un ajout
            if (isset($type_id)) {
                try {
                    // Vérification de l'unicité du nom
                    $stmt = $bdd->prepare('SELECT type_name FROM type WHERE type_name = :type_name AND NOT type_id=:type_id');
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->bindParam(':type_name', $type_name);
                    $stmt->bindParam(':type_id', $type_id);
                    $stmt->execute();

                    $rows = $stmt->fetch();
                    $stmt->closeCursor();

                    if ($rows['type_name'] == $type_name) {
                        // Le nom ajouté existe déjà, refus
                        $unique = false;
                        $message = array(false, "Le nom du type ajout&eacute; existe d&eacute;j&agrave; dans la base de donn&eacute;es");
                    } else {
                        // Modification du type
                        $stmt = $bdd->prepare("UPDATE type SET type_name=:type_name, type_complex=:type_complex WHERE type_id=:type_id");
                        $stmt->bindParam(':type_name', $type_name);
                        $stmt->bindParam(':type_complex', $type_complex);
                        $stmt->bindParam(':type_id', $type_id);
                        $edited = $stmt->execute();

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($edited) {
                            // Le type a bien été édité
                            $message = array(true, "Le type a bien &eacute;t&eacute; modifi&eacute;");
                        } else {
                            $message = array(false, "Erreur lors de la modification du type\nVeuillez r&eacute;essayer");
                        }
                    }
                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la modification du type\nVeuillez r&eacute;essayer");
                }
            } else {
                try {
                    // Vérification de l'unicité du nom du type
                    $stmt = $bdd->prepare('SELECT type_name FROM type WHERE type_name = :type_name');
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->bindParam(':type_name', $type_name);
                    $stmt->execute();

                    $rows = $stmt->fetch();
                    $stmt->closeCursor();

                    if ($rows['type_name'] == $type_name) {
                        // Le nom ajouté existe déjà, refus
                        $message = array(false, "Le nom du type ajout&eacute; existe d&eacute;j&agrave; dans la base de donn&eacute;es");
                    } else {
                        // Ajout du type
                        $stmt = $bdd->prepare("INSERT INTO  type(type_name, type_complex) VALUES (:type_name, :type_complex)");
                        $stmt->bindParam(':type_name', $type_name);
                        $stmt->bindParam(':type_complex', $type_complex);
                        $added = $stmt->execute();

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($added) {
                            // Le type a bien été ajouté
                            $message = array(true, "Le type a bien &eacute;t&eacute; ajout&eacute;");
                            // log d'ajout d'un user
                            $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                            $loggerAjout->info($_SESSION['login'] . " a ajouté le type " . $type_name);
                        } else {
                            $message = array(false, "Erreur lors de l'ajout du type\nVeuillez r&eacute;&eacute;ssayer");
                        }
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout du type.\nVeuillez r&eacute;&eacute;ssayer");
                }
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_types.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}