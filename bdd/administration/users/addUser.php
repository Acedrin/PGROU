<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Ajout/modification d'un administrateur de MooWse dans la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Booléen pour vérifier la bonne suppression de l'utilisateur
$added = false;

// Booléen pour vérifier si le formulaire remis est correct
$correct = false;

// Connexion à la base de données
require("../bdd.php");

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
        }
    }

    // Si le formulaire remis est correct, on enregistre
    if ($correct) {
        // Si l'id de l'utilisateur est supérieur à 0, c'est une modification
        // Sinon c'est un ajout
        if ($user_id > 0) {
            try {
                // Modification de l'utilisateur
                $stmt = $bdd->prepare("UPDATE user SET user_uid=:user_uid, user_expirationdate=:user_expirationdate WHERE user_id=:user_id");
                $stmt->bindParam(':user_uid', $user_uid);
                $stmt->bindParam(':user_expirationdate', $user_expirationdate);
                $stmt->bindParam(':user_id', $user_id);
                $added = $stmt->execute();

            // Gestion des exceptions
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        } else {
            try {
                // Ajout de l'utilisateur
                $stmt = $bdd->prepare("INSERT INTO  user(user_uid,  user_expirationdate) VALUES (:user_uid, :user_expirationdate)");
                $stmt->bindParam(':user_uid', $user_uid);
                $stmt->bindParam(':user_expirationdate', $user_expirationdate);
                $added = $stmt->execute();
            // Gestion des exceptions
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }
    }
}
// Redirection vers la vue gestion_administrateurs.php
// Passage par le controlleur getUsers.php pour avoir la liste des administrateurs
header("Location:getUsers.php");
die();

