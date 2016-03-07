<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Suppression d'un administrateur de MooWse de la base de données

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Booléen pour vérifier la bonne suppression de l'utilisateur
$deleted = false;

// Connexion à la base de données
require("../bdd.php");

// Vérification de la requête POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialisation de l'id utilisateur
    $user_id = 0;

    // Vérification de la présence de l'id utilisateur à supprimer
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    }

    try {
        // Suppression de l'utilisateur
        $stmt = $bdd->prepare("DELETE FROM user WHERE user_id=:user_id");
        $stmt->bindParam(':user_id', $user_id);
        $deleted = $stmt->execute();
// Gestion des exceptions
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
// Redirection vers la vue gestion_administrateurs.php
// Passage par le controlleur getUsers.php pour avoir la liste des administrateurs
header("Location:getUsers.php");
die();
