<?php

// On se connecte à la base de données
//include('bdd.php');
// On récupère les informations de la base de données
try {
    $bdd = new PDO('mysql:host=localhost;dbname=moowse;charset=utf8', 'root', '');
    
    // On récupère tous les utilisateurs
    $users = $bdd->prepare('SELECT * FROM user');
    $users->setFetchMode(PDO::FETCH_ASSOC);
    $users->execute();
    

} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// On redirige ensuite vers la vue appropriée
//header('Content-Type: text/html; charset=utf-8');
//header("Location:gestion_administrateurs.php");

include_once('gestion_administrateurs.php');