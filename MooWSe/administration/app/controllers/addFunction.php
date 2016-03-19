<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'une fonction d'un serveur
  de MooWse dans la base de données

  Christophe Cleuet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
//ini_set("display_errors", 0);
//error_reporting(0);

//gestion des logs
require ("../../vendor/autoload.php");

// Booléen pour vérifier le bon ajout de la fonction
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

        $server_ids = array();
        $function_names = array();

        // Vérification de la présence de l'id fonction à modifier le cas échéant
        if (isset($_POST['function_id'])) {
            $function_id = $_POST['function_id'];
        }

        // Vérification du champ "function_name"
        if (isset($_POST['function_name'])) {
            $function_names = $_POST['function_name'];

            // Vérification du champ "server_id"
            if (isset($_POST['server_id'])) {
                $server_ids = $_POST['server_id'];
                $correct = true;
            } else {
                // L'id du serveur n'est pas fixée
                $message = array(false, "Vous n'avez pas indiqu&eacute; le serveur de la fonction &agrave; ajouter");
            }
        } else {
            // Le nom n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; le nom de la fonction &agrave; ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            // Si l'id de la fonction existe, c'est une modification
            // Sinon c'est un ajout
            if (isset($function_id)) {
                $function_name = $function_names[0];
                $server_id = $server_ids[0];

                try {
                    // Recherche du couple server_id et function_name
                    $stmt = $bdd->prepare("SELECT * FROM function WHERE function_name=:function_name AND server_id=:server_id AND NOT function_id=:function_id");
                    $stmt->bindParam(':function_name', $function_name);
                    $stmt->bindParam(':server_id', $server_id);
                    $stmt->bindParam(':function_id', $function_id);
                    $stmt->execute();

                    $exist = $stmt->fetch();

                    // Fermeture de la connexion
                    $stmt->closeCursor();

                    if (!($exist == false)) {
                        // Le couple est déjà existant
                        $message = array(false, "Ce nom de fonction existe d&eacute;j&agrave; pour ce serveur\nVeuillez r&eacute;essayer");
                    }

                    // Gestion des exceptions
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des droits existants\nVeuillez r&eacute;essayer");
                }
                if ($exist == false) {
                    try {
                        // Modification de la fonction
                        $stmt = $bdd->prepare("UPDATE function SET function_name=:function_name, server_id=:server_id WHERE function_id=:function_id");
                        $stmt->bindParam(':function_name', $function_name);
                        $stmt->bindParam(':server_id', $server_id);
                        $stmt->bindParam(':function_id', $function_id);
                        $edited = $stmt->execute();

                        // log d'ajout d'une fonction
                        $loggerModif = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                        $loggerModif->info($_SESSION['login'] . " a modifié la fonction d'id " . $function_id . ". Nouveau nom :" . $function_name . ". Nouveau serveur : " . $server_id);

                        // Fermeture de la connexion
                        $stmt->closeCursor();

                        if ($edited) {
                            // La fonction a bien été édité
                            $message = array(true, "La fonction a bien &eacute;t&eacute; modifi&eacute;");
                        } else {
                            $message = array(false, "Erreur lors de la modification de la fonction\nVeuillez r&eacute;essayer");
                        }

                        // Gestion des exceptions
                    } catch (Exception $e) {
                        $message = array(false, "Erreur lors de la modification de la fonction\nVeuillez r&eacute;essayer");
                    }
                }
            } else {
                try {
                    // Boucle sur les fonctions à ajouter
                    for ($i = 0; $i < sizeof($function_names); $i++) {
                        $function_name = $function_names[$i];
                        $server_id = $server_ids[$i];
                        try {
                            // Recherche du couple server_id et function_name
                            $stmt = $bdd->prepare("SELECT * FROM function WHERE function_name=:function_name AND server_id=:server_id");
                            $stmt->bindParam(':function_name', $function_name);
                            $stmt->bindParam(':server_id', $server_id);
                            $stmt->execute();

                            $exist = $stmt->fetch();

                            // Fermeture de la connexion
                            $stmt->closeCursor();

                            if (!($exist == false)) {
                                // Le couple est déjà existant
                                $message = array(false, "Ce nom de fonction existe d&eacute;j&agrave; pour ce serveur\nVeuillez r&eacute;essayer");
                            }

                            // Gestion des exceptions
                        } catch (Exception $e) {
                            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des droits existants\nVeuillez r&eacute;essayer");
                        }

                        if ($exist == false) {
                            try {
                                // Ajout de la fonction
                                $stmt = $bdd->prepare("INSERT INTO function(function_name, server_id) VALUES (:function_name, :server_id)");
                                $stmt->bindParam(':function_name', $function_name);
                                $stmt->bindParam(':server_id', $server_id);
                                $added = $stmt->execute();

                                // log d'ajout d'une fonction
                                $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                                $loggerAjout->info($_SESSION['login'] . " a ajouté la fonction " . $function_name . " pour le serveur d'id " . $server_id);

                                // Fermeture de la connexion
                                $stmt->closeCursor();

                                if ($added) {
                                    // La fonction a bien été ajoutée
                                    $message = array(true, "La fonction a bien &eacute;t&eacute; ajout&eacute;e");
                                } else {
                                    $message = array(false, "Erreur lors de l'ajout de la fonction\nVeuillez r&eacute;essayer");
                                }

                                // Gestion des exceptions
                            } catch (Exception $e) {
                                $message = array(false, "Erreur lors de l'ajout de la fonction\nVeuillez r&eacute;essayer");
                            }
                        }
                    }
                } catch (Exception $e) {
                    $message = array(false, "Erreur lors de l'ajout de la fonction\nVeuillez r&eacute;essayer");
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