<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'un administrateur de MooWse dans la base de données

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
        $user_id = 0;
        $user_uid = "";
        $annee = 0;
        $mois = 0;
        $jour = 0;
        $user_expirationdate = "";

        // Vérification de la présence de l'id utilisateur à modifier le cas échéant
        if (isset($_POST['user_id'])) {
            $user_id = $_POST['user_id'];
        }

        if (isset($_POST['user_uid'])) {
            $user_uid = htmlspecialchars($_POST['user_uid']);

            // Vérification de si la date d'expération n'est pas fixée (donc infinie)
            if ($_POST['annee'] == 0 && $_POST['mois'] == 0 && $_POST['jour'] == 0) {
                $correct = true;

                // Sinon vérification qu'aucune des entrées n'est nulles
            } else if ($_POST['annee'] != 0 && $_POST['mois'] != 0 && $_POST['jour'] != 0) {
                $user_expirationdate = $_POST['annee'] . "-" . $_POST['mois'] . "-" . $_POST['jour'];
                $user_expirationdate = htmlspecialchars($user_expirationdate);
                $correct = true;
            } else {
                // Le format de la date n'est pas correct
                $message = array(false, "La date d'expiration ne peut contenir un 0 si d'autres param&egrave;tres sont non nuls");
            }
        } else {
            // l'uid n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; le login de l'administrateur à ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si l'id de l'utilisateur est supérieur à 0, c'est une modification
            // Sinon c'est un ajout
            if ($user_id > 0) {
                try {
                    // Vérification de l'unicité de l'uid
                    $stmt = $bdd->prepare('SELECT user_uid FROM user WHERE user_uid = :user_uid');
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->bindParam(':user_uid', $user_uid);
                    $stmt->execute();

                    $rows = $stmt->fetch();
                    $stmt->closeCursor();

                    if ($rows['user_uid'] == $user_uid) {
                        // L'uid ajouté existe déjà, refus
                        $message = array(false, "Le login ajout&eacute; existe d&eacute;j&agrave; dans la base de donn&eacute;es");
                    } else {
                        // Modification de l'utilisateur
                        $stmt = $bdd->prepare("UPDATE user SET user_uid=:user_uid, user_expirationdate=:user_expirationdate WHERE user_id=:user_id");
                        $stmt->bindParam(':user_uid', $user_uid);
                        $stmt->bindParam(':user_expirationdate', $user_expirationdate);
                        $stmt->bindParam(':user_id', $user_id);
                        $edited = $stmt->execute();

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($edited) {
                            // L'administrateur a bien été édité
                            $message = array(true, "L'administrateur a bien &eacute;t&eacute; modifi&eacute;");
                        } else {
                            $message = array(false, "Erreur lors de la modification de l'administrateur\nVeuillez r&eacute;essayer");
                        }
                    }
                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la modification de l'administrateur\nVeuillez r&eacute;essayer");
                }
            } else {
                try {
                    // Vérification de l'unicité de l'uid
                    $stmt = $bdd->prepare('SELECT user_uid FROM user WHERE user_uid = :user_uid');
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->bindParam(':user_uid', $user_uid);
                    $stmt->execute();

                    $rows = $stmt->fetch();
                    $stmt->closeCursor();

                    if ($rows['user_uid'] == $user_uid) {
                        // L'uid ajouté existe déjà, refus
                        $message = array(false, "Le login ajout&eacute; existe d&eacute;j&agrave; dans la base de donn&eacute;es");
                    } else {
                        // Ajout de l'utilisateur
                        $stmt = $bdd->prepare("INSERT INTO  user(user_uid,  user_expirationdate) VALUES (:user_uid, :user_expirationdate)");
                        $stmt->bindParam(':user_uid', $user_uid);
                        $stmt->bindParam(':user_expirationdate', $user_expirationdate);
                        $added = $stmt->execute();

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($edited) {
                            // L'administrateur a bien été ajouté
                            $message = array(true, "L'administrateur a bien &eacute;t&eacute; ajout&eacute;");
                        } else {
                            $message = array(false, "Erreur lors de l'ajout de l'administrateur\nVeuillez r&eacute;&eacute;ssayer");
                        }
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout de l'administrateur.\nVeuillez r&eacute;&eacute;ssayer");
                }
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:/app/views/gestion_administrateurs.php");
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:/index.html");
}