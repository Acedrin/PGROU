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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Initialisation de l'id utilisateur
    $user_id = 0;

    // Vérification de la présence de l'id utilisateur à supprimer
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
    }

    try {
        // Connexion à la base de données
        $bdd = new PDO('mysql:host=localhost;dbname=moowse;charset=utf8', 'root', '');

        // Suppression de l'utilisateur
        $stmt = $bdd->prepare("DELETE FROM user where (:user_id)");
        $stmt->bindParam(':user_id', $user_id);
        $deleted = $stmt->execute();
// Gestion des exceptions
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}
// Redirection vers la vue gestion_administrateurs.php
//header('Content-Type: text/html; charset=utf-8');
//header("Location:gestion_administrateurs.php");

include_once('gestion_administrateurs.php');
