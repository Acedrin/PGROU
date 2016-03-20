<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout d'un sous-type à un type complexe de MooWse dans la base de données

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

        // Récupération du type dont dépends le sous-type
        if (isset($_POST['typecomplex_depends'])) {
            $typecomplex_depends = $_POST['typecomplex_depends'];
        }

        if (isset($_POST['typecomplex_order'])) {
            $typecomplex_order = htmlspecialchars($_POST['typecomplex_order']);

            if ($_POST['typecomplex_type']) {
                $typecomplex_type = htmlspecialchars($_POST['typecomplex_type']);
                $correct = true;
            } else {
                // Le type n'est pas fixé
                $message = array(false, "Vous n'avez pas indiqu&eacute; le type du sous-type &agrave; ajouter");
            }
        } else {
            // L'ordre n'est pas fixé
            $message = array(false, "Vous n'avez pas indiqu&eacute; l'ordre du sous-type &agrave; ajouter");
        }

        // Si le formulaire remis est correct, on enregistre
        if ($correct) {
            try {
                // Récupération des sous-types existants du type complexe
                $stmt = $bdd->prepare('SELECT typecomplex_order,typecomplex_type FROM typecomplex WHERE typecomplex_depends=:typecomplex_depends');
                $stmt->bindParam(':typecomplex_depends', $typecomplex_depends);
                $stmt->setFetchMode(PDO::FETCH_ASSOC);
                $stmt->execute();

                // Enregistrement du résultat dans un tableau
                $orders = array();
                $types = array();
                // Utilisation d'une boucle pour que le label des colonnes soit l'id
                while ($row = $stmt->fetch()) {
                    $orders[] = $row['typecomplex_order'];
                    $types[] = $row['typecomplex_type'];
                }
                $depends_list = array_combine($orders, $types);

                // Fermeture de la connexion
                $stmt->closeCursor();

                // Déplacement des types dont l'order est égal ou supérieur à celui ajouté
                for ($i = sizeof($depends_list); $i > $typecomplex_order - 1; $i--) {

                    $order = $i + 1;

                    // Modification du sous-type
                    $stmt = $bdd->prepare("UPDATE typecomplex SET typecomplex_order=:order WHERE typecomplex_depends=:typecomplex_depends AND typecomplex_order=:exorder");
                    $stmt->bindParam(':order', $order);
                    $stmt->bindParam(':typecomplex_depends', $typecomplex_depends);
                    $stmt->bindParam(':exorder', $i);
                    $stmt->execute();

                    // Fermeture de la connexion
                    $stmt->closeCursor();
                }

                // Ajout du sous-type
                $stmt = $bdd->prepare("INSERT INTO  typecomplex(typecomplex_depends, typecomplex_order, typecomplex_type) VALUES (:typecomplex_depends, :typecomplex_order, :typecomplex_type)");
                $stmt->bindParam(':typecomplex_depends', $typecomplex_depends);
                $stmt->bindParam(':typecomplex_order', $typecomplex_order);
                $stmt->bindParam(':typecomplex_type', $typecomplex_type);
                $added = $stmt->execute();

                // Fermeture de la connexion
                $stmt->closeCursor();

                if ($added) {
                    // Le type a bien été ajouté
                    $message = array(true, "Le sous-type a bien &eacute;t&eacute; ajout&eacute;");
                    // log d'ajout d'un user
                    $loggerAjout = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
                    $loggerAjout->info($_SESSION['login'] . " a ajouté le type " . $typecomplex_order);
                } else {
                    $message = array(false, "Erreur lors de l'ajout du sous-type\nVeuillez r&eacute;&eacute;ssayer");
                }


                // Gestion des exceptions
            } catch (Exception $e) {
                $message = array(false, "Erreur lors de l'ajout du sous-type.\nVeuillez r&eacute;&eacute;ssayer");
            }
        }
    }

    // Enregistrement du message
    $_SESSION['alert'] = $message;

    // Redirection vers la vue gestion_administrateurs.php
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../views/gestion_type_complexe.php?type_id=" . $typecomplex_depends);
    die();
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}    