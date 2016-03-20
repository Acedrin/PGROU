<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'une variable à une fonction de MooWse dans la base de données

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

// Booléen pour vérifier le bon ajout du type
$added = false;

// Booléen pour vérifier si le formulaire remis est correct
$correct = false;

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {

    // Vérification de la requête POST
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Récupération de la fonction dont dépends la variable
        if (isset($_POST['function_id'])) {
            $function_id = $_POST['function_id'];
        }

        // Vérification de la présence de l'id de la variable à modifier le cas échéant
        if (isset($_POST['variable_id'])) {
            $variable_id = $_POST['variable_id'];
        }


        if (isset($_POST['variable_name'])) {
            $variable_name = htmlspecialchars($_POST['variable_name']);

            if (isset($_POST['variable_order'])) {
                $variable_order = htmlspecialchars($_POST['variable_order']);

                if (isset($_POST['variable_input'])) {
                    $variable_input = htmlspecialchars($_POST['variable_input']);

                    if (isset($_POST['type_id'])) {
                        $type_id = htmlspecialchars($_POST['type_id']);
                        $correct = true;
                    } else {
                        // Le type n'est pas fixé
                        $message = array(false, "Vous n'avez pas indiqu&eacute; le type de la variable &agrave; ajouter");
                    }
                } else {
                    // Ce n'est pas indiqué si la variable est en entrée ou sortie
                    $message = array(false, "Vous n'avez pas indiqu&eacute; si la variable &agrave; ajouter est en entr&eacute;e ou sortie");
                }
            } else {
                // L'ordre n'est pas fixé
                $message = array(false, "Vous n'avez pas indiqu&eacute; l'ordre de la variable &agrave; ajouter");
            }
        } else {
            // Le nom n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; le nom de la variable &agrave; ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            try {
                // Récupération des variables existantes de la fonction également en entrée (ou en sortie)
                $stmt = $bdd->prepare('SELECT variable_id,variable_order FROM variable WHERE function_id=:function_id AND variable_input=:variable_input ORDER BY variable_order');
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

                // Si l'ordre est trop élevé, sa valeur est modifiée
                if ($variable_order > sizeof($variable_list) + 1) {
                    $variable_order = sizeof($variable_list) + 1;
                }

                // Déplacement des variables dont l'order est égal ou supérieur à celle ajoutée
                for ($i = sizeof($variable_list); $i > $variable_order - 1; $i--) {

                    $order = $i + 1;

                    // Modification de la variable
                    $stmt = $bdd->prepare("UPDATE variable SET variable_order=:order WHERE variable_id=:id");
                    $stmt->bindParam(':order', $order);
                    $stmt->bindParam(':id', $variable_list[$i]);
                    $stmt->execute();

                    // Fermeture de la connexion
                    $stmt->closeCursor();
                }

                // Ajout de la variable
                $stmt = $bdd->prepare("INSERT INTO  variable(function_id, variable_name, variable_order, variable_input, type_id) VALUES (:function_id, :variable_name, :variable_order, :variable_input, :type_id)");
                $stmt->bindParam(':function_id', $function_id);
                $stmt->bindParam(':variable_name', $variable_name);
                $stmt->bindParam(':variable_order', $variable_order);
                $stmt->bindParam(':variable_input', $variable_input);
                $stmt->bindParam(':type_id', $type_id);
                $added = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();

                if ($added) {
                    // Le type a bien été ajouté
                    $message = array(true, "La variable a bien &eacute;t&eacute; ajout&eacute;");
                    // log d'ajout d'un user
                    $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerAjout->info($_SESSION['login'] . " a ajouté la variable " . $variable_name);
                } else {
                    $message = array(false, "Erreur lors de l'ajout de la variable\nVeuillez r&eacute;&eacute;ssayer");
                }


                // Gestion des exceptions
            } catch (Exception $e) {
                $message = array(false, "Erreur lors de l'ajout de la variable.\nVeuillez r&eacute;&eacute;ssayer");
            }
        }
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
    $message = array(false, "Connectez-vous pour acc&eacute;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}    