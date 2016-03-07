<?php

/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Sélection de tous les administrateurs de MooWse dans la base de données
  Appel de la vue gestion_administrateurs

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Connexion à la base de données
//include('bdd.php');

try {
    // Connexion à la base de données
    $bdd = new PDO('mysql:host=localhost;dbname=moowse;charset=utf8', 'root', '');

    // Récupération de tous les utilisateurs
    $users = $bdd->prepare('SELECT * FROM user');
    $users->setFetchMode(PDO::FETCH_ASSOC);
    $users->execute();

// Traitement des exceptions
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Redirection vers la vue gestion_administrateurs.php
//header('Content-Type: text/html; charset=utf-8');
//header("Location:gestion_administrateurs.php");

include_once('gestion_administrateurs.php');
