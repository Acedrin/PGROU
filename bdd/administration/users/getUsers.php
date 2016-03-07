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
require("../bdd.php");

try {
    // Récupération de tous les utilisateurs
    $stmt = $bdd->prepare('SELECT * FROM user');
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $stmt->execute();
    
    // Enregistrement du résultat dans un tableau
    $users = $stmt->fetchAll();
    
    // Fermeture de la connexion
    $stmt->closeCursor();
    
// Traitement des exceptions
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
