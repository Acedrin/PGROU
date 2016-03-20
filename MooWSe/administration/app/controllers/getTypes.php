<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection de tous les types de MooWse dans la base de données

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

    // Vérification d'une demande d'information sur un type particulier
    if (isset($type_id)) {
        
        try {
            // Récupération du type
            $stmt = $bdd->prepare('SELECT * FROM type WHERE type_id=:type_id');
            $stmt->bindParam(':type_id', $type_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $type = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();
            
            // Récupération de ses dépendances si complexe
            $stmt = $bdd->prepare('SELECT typecomplex.typecomplex_depends,typecomplex.typecomplex_order,typecomplex.typecomplex_type,tyde.type_name as depends_name,tyty.type_name as tyco_name '
                    . 'FROM typecomplex '
                    . 'INNER JOIN type as tyde ON typecomplex.typecomplex_depends=tyde.type_id '
                    . 'INNER JOIN type as tyty ON typecomplex.typecomplex_type=tyty.type_id '
                    . 'WHERE typecomplex_depends=:type_id '
                    . 'ORDER BY typecomplex_order');
            $stmt->bindParam(':type_id', $type_id);
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $typecomplex = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

            // Récupération de la liste des types
            $stmt = $bdd->prepare('SELECT type_id,type_name FROM type');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $type_id = array();
            $type_name = array();
            // Utilisation d'une boucle pour que le label des colonnes soit l'id
            while ($row = $stmt->fetch()) {
                $type_id[] = $row['type_id'];
                $type_name[] = $row['type_name'];
            }
            $types_list = array_combine($type_id, $type_name);

            // Fermeture de la connexion
            $stmt->closeCursor();
            
            // Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration du type\nVeuillez r&eacute;essayer");

            // Enregistrement du message
            $_SESSION['alert'] = $message;
        }
    } else {
        // La requête cherche à obtenir tous les types existants
        try {
            // Récupération de tous les types
            $stmt = $bdd->prepare('SELECT * FROM type');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $types = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();
            
            // Récupération de leurs dépendances
            $stmt = $bdd->prepare('SELECT typecomplex.typecomplex_depends,typecomplex.typecomplex_order,typecomplex.typecomplex_type,tyde.type_name as depends_name,tyty.type_name as tyco_name '
                    . 'FROM typecomplex '
                    . 'INNER JOIN type as tyde ON typecomplex.typecomplex_depends=tyde.type_id '
                    . 'INNER JOIN type as tyty ON typecomplex.typecomplex_type=tyty.type_id '
                    . 'ORDER BY typecomplex_order');
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->execute();

            // Enregistrement du résultat dans un tableau
            $typescomplex = $stmt->fetchAll();
            // Fermeture de la connexion
            $stmt->closeCursor();

// Traitement des exceptions
        } catch (Exception $e) {
            $message = array(false, "Erreur lors de la r&eacute;cup&eacute;ration des types\nVeuillez r&eacute;essayer");

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