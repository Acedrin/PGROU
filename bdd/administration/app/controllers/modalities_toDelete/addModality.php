<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'une modalité d'accès des clients
  de MooWse dans la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

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
        $modality_id = 0;
        $modality_name = "";

        // Vérification de la présence de l'id utilisateur à modifier le cas échéant
        if (isset($_POST['modality_id'])) {
            $modality_id = $_POST['modality_id'];
        }

        if (isset($_POST['modality_name'])) {
            $modality_name = htmlspecialchars($_POST['modality_name']);
            $correct = true;
        } else {
            // Le nom n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; le nom de la modalit&eacute; à ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si l'id de la modalité est supérieur à 0, c'est une modification
            // Sinon c'est un ajout
            if ($modality_id > 0) {
                try {
                    // Vérification de l'unicité du nom
                    $stmt = $bdd->prepare('SELECT modality_name FROM modality WHERE modality_name = :modality_name');
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->bindParam(':modality_name', $modality_name);
                    $stmt->execute();

                    $rows = $stmt->fetch();
                    $stmt->closeCursor();

                    if ($rows['modality_name'] == $modality_name) {
                        // Le nom de modalité ajouté existe déjà, refus
                        $message = array(false, "Le nom de modalit&eacute; ajout&eacute; existe d&eacute;j&agrave; dans la base de donn&eacute;es");
                    } else {
                        // Modification de la modalité
                        $stmt = $bdd->prepare("UPDATE modality SET modality_name=:modality_name WHERE modality_id=:modality_id");
                        $stmt->bindParam(':modality_name', $modality_name);
                        $stmt->bindParam(':modality_id', $modality_id);
                        $edited = $stmt->execute();

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($edited) {
                            // La modalité a bien été édité
                            $message = array(true, "La modalité a bien été modifié");
                        } else {
                            $message = array(false, "Erreur lors de la modification de la modalit&eacute;\nVeuillez r&eacute;essayer");
                        }
                    }
                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la modification de la modalit&eacute;\nVeuillez r&eacute;essayer");
                }
            } else {
                try {
                    // Vérification de l'unicité du nom
                    $stmt = $bdd->prepare('SELECT modality_name FROM modality WHERE modality_name = :modality_name');
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->bindParam(':modality_name', $modality_name);
                    $stmt->execute();

                    $rows = $stmt->fetch();
                    $stmt->closeCursor();

                    if ($rows['modality_name'] == $modality_name) {
                        // Le nom de modalité ajouté existe déjà, refus
                        $message = array(false, "Le nom de modalit&eacute; ajout&eacute; existe d&eacute;j&agrave; dans la base de donn&eacute;es");
                    } else {
                        // Ajout de la modalité
                        $stmt = $bdd->prepare("INSERT INTO  modality(modality_name) VALUES (:modality_name)");
                        $stmt->bindParam(':modality_name', $modality_name);
                        $added = $stmt->execute();

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($edited) {
                            // La modalité a bien été ajoutée
                            $message = array(true, "La modalit&eacute; a bien &eacute;t&eacute; ajout&eacute;");
                        } else {
                            $message = array(false, "Erreur lors de l'ajout de la modalit&eacute;\nVeuillez r&eacute;&eacute;ssayer");
                        }
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout de la modalit&eacute;.\nVeuillez r&eacute;&eacute;ssayer");
                }
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_modalities.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:/app/views/gestion_modalities.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:/index.html");
}