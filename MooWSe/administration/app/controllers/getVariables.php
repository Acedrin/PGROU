<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection des variables des fonctions de MooWse dans la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

// Connexion à la base de données
require("bdd.php");

// Protection pour ne pas acceder au contrôleur sans être connecté
if (isset($_SESSION['login'])) {
    // Vérification d'une demande d'information sur les variables d'un serveur particulier
    if (isset($server_id)) {

        try {
            // Récupération des variables du serveur demandé
            $stmt = $bdd->prepare('SELECT variable.variable_id,variable.variable_input,variable.variable_order,variable.function_id,variable.type_id,'
                    . 'function.function_name,function.server_id,'
                    . 'server.server_name,'
                    . 'type.type_name,type.type_complex,'
                    . 'typecomplex.typecomplex_order,typecomplex.typecomplex_depends,typecomplex.typecomplex_type '
                    . 'FROM variable '
                    . 'INNER JOIN function ON variable.function_id=function.function_id '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'INNER JOIN type ON variable.type_id=type.type_id '
                    . 'INNER JOIN typecomplex ON type.type_id=typecomplex.typecomplex_depends '
                    . 'INNER JOIN type ON typecomplex.typecomplex_id=type.type_id '
                    . 'WHERE server.server_id=:server_id '
                    . 'ORDER BY server.server_name,function.function_name,variable.variable_name');
            $stmt->bindParam(':server_id', $server_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $varialbes = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des variables du serveur\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else if (isset($function_id)) {
        // Vérification d'une demande d'information sur une fonction particulière
        try {
            // Récupération des variables de la fonction
            $stmt = $bdd->prepare('SELECT variable.variable_id,variable.variable_input,variable.variable_order,variable.function_id,variable.type_id,'
                    . 'function.function_name,function.server_id,'
                    . 'server.server_name,'
                    . 'type.type_name,type.type_complex,'
                    . 'typecomplex.typecomplex_order,typecomplex.typecomplex_depends,typecomplex.typecomplex_type '
                    . 'FROM variable '
                    . 'INNER JOIN function ON variable.function_id=function.function_id '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'INNER JOIN type ON variable.type_id=type.type_id '
                    . 'INNER JOIN typecomplex ON type.type_id=typecomplex.typecomplex_depends '
                    . 'INNER JOIN type ON typecomplex.typecomplex_id=type.type_id '
                    . 'WHERE function.function_id=:function_id '
                    . 'ORDER BY server.server_name,function.function_name,variable.variable_name');
            $stmt->bindParam(':function_id', $function_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $variables = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des informations de la fonction\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else if (isset($variable_id)) {
        // Vérification d'une demande d'information sur une seule variable
        try {
            // Récupération des variables de la fonction
            $stmt = $bdd->prepare('SELECT variable.variable_id,variable.variable_input,variable.variable_order,variable.function_id,variable.type_id,'
                    . 'function.function_name,function.server_id,'
                    . 'server.server_name,'
                    . 'type.type_name,type.type_complex,'
                    . 'typecomplex.typecomplex_order,typecomplex.typecomplex_depends,typecomplex.typecomplex_type '
                    . 'FROM variable '
                    . 'INNER JOIN function ON variable.function_id=function.function_id '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'INNER JOIN type ON variable.type_id=type.type_id '
                    . 'INNER JOIN typecomplex ON type.type_id=typecomplex.typecomplex_depends '
                    . 'INNER JOIN type ON typecomplex.typecomplex_id=type.type_id '
                    . 'WHERE variable.variable_id=:variable_id');
            $stmt->bindParam(':variable_id', $variable_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $variable = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des informations de la variable\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else {
        // Récupération de toutes les variables
        try {
            // Récupération de toutes les variables
            $stmt = $bdd->prepare('SELECT variable.variable_id,variable.variable_input,variable.variable_order,variable.variable_name,variable.function_id,variable.type_id,'
                    . 'function.function_name,function.server_id,'
                    . 'server.server_name,'
                    . 'type.type_name,type.type_complex '
                    . 'FROM variable '
                    . 'INNER JOIN function ON variable.function_id=function.function_id '
                    . 'INNER JOIN server ON function.server_id=server.server_id '
                    . 'INNER JOIN type ON variable.type_id=type.type_id '
                    . 'ORDER BY server.server_name,function.function_name,variable.variable_order');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $variables = $stmt->fetchAll();

            // Fermeture de la connexion
            $stmt->closeCursor();

            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des variables\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    }
} else {
    // L'utilisateur n'est pas connecté
    // Il est redirigé vers la page d'accueil
    $message = array(false, "Connectez-vous pour acc&eacute;der &agrave; cette ressource");
    $_SESSION['alert'] = $message;

    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}    