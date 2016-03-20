<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'un sous-type d'un type complexe de MooWse de la base de données

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
        // Vérification de la présence de l'id du type complexe du sous-type à supprimer
        if (isset($_GET['typecomplex_depends'])) {
            $typecomplex_depends = $_GET['typecomplex_depends'];
        }

        // Vérification de la présence de l'ordre du sous-type à supprimer
        if (isset($_GET['typecomplex_order'])) {
            $typecomplex_order = $_GET['typecomplex_order'];
        }

        try {
            // Récupération des sous-types existants du type complexe
            $stmt = $bdd->prepare('SELECT typecomplex_order,typecomplex_type FROM typecomplex WHERE typecomplex_depends=:typecomplex_depends');
            $stmt->bindParam(':typecomplex_depends', $typecomplex_depends);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $orders = array();
            $types = array();
            // Utilisation d'une boucle pour que le label des colonnes soit l'ordre
            while ($row = $stmt->fetch()) {
                $orders[] = $row['typecomplex_order'];
                $types[] = $row['typecomplex_type'];
            }
            $depends_list = array_combine($orders, $types);

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Suppression du sous-type
            $stmt = $bdd->prepare("DELETE FROM typecomplex WHERE typecomplex_depends=:typecomplex_depends AND typecomplex_order=:typecomplex_order");
            $stmt->bindParam(':typecomplex_depends', $typecomplex_depends);
            $stmt->bindParam(':typecomplex_order', $typecomplex_order);
            $deleted = $stmt->execute();

            // Fermeture de la connexion
            $stmt->closeCursor();
            
            // Déplacement des types dont l'order est égal ou supérieur à celui supprimé
            for ($i = $typecomplex_order; $i < sizeof($depends_list) + 1; $i++) {

                $order = $i - 1;

                // Modification du sous-type
                $stmt = $bdd->prepare("UPDATE typecomplex SET typecomplex_order=:order WHERE typecomplex_depends=:typecomplex_depends AND typecomplex_order=:exorder");
                $stmt->bindParam(':order', $order);
                $stmt->bindParam(':typecomplex_depends', $typecomplex_depends);
                $stmt->bindParam(':exorder', $i);
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
        // log de suppression d'un sous-type
        $loggerSuppr = new Katzgrau\KLogger\Logger(__DIR__ . '../../../logs');
        $loggerSuppr->info($_SESSION['login'] . " a supprimé le sous-type du type d'id" . $typecomplex_depends . " et d'ordre " . $typecomplex_order);
        $message = array(true, "Le sous-type a bien &eacute;t&eacute; supprim&eacute;");
    } else {
        // La suppression n'a pas été effectuée
        $message = array(false, "Une erreur a &eacute;t&eacute; rencontr&eacute;e lors de la suppression.\nVeuillez r&eacute;essayer");
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
    $message = array(false, "Connectez-vous pour acc&egrave;der à cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}