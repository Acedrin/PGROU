<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$mysqli = new mysqli('localhost', 'root', 'admin', 'new_schema');

/*
 * Ceci est le style POO "officiel"
 * MAIS $connect_error était erroné jusqu'en PHP 5.2.9 et 5.3.0.
 */
if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}

/*
 * Utilisez cette syntaxe de $connect_error si vous devez assurer
 * la compatibilité avec les versions de PHP avant 5.2.9 et 5.3.0.
 */
if (mysqli_connect_error()) {
    die('Erreur de connexion (' . mysqli_connect_errno() . ') '
            . mysqli_connect_error());
}

echo 'Succès... ' . $mysqli->host_info . "\n\n";

//printf("Version de la bibliothèque du client : %s\n", mysqli_get_client_info());

/* Affichage de la version du serveur */
//printf("Version du serveur : %s\n", $mysqli->server_info);

/* "Insert" ne retournera aucun jeu de résultats */
if ($mysqli->query("INSERT INTO modality(modality_id,modality_name) values (1,'yolo')") === TRUE) {
    printf("\n Insertion réalisée avec succès.\n");
}
if ($mysqli->query("INSERT INTO client(client_id,client_name,client_ip,modality_id) values (1,'Elliot','127.168.0.1',1)") === TRUE) {
    printf("\n Insertion réalisée avec succès.\n");
}

if ($result = mysqli_query($mysqli, "SELECT * FROM client LIMIT 10")) {
    printf("Select a retourné %d lignes.\n", mysqli_num_rows($result));

    /* Libération du jeu de résultats */
    $row = $result->fetch_assoc();
    printf( "%s %s \n", $row['client_name'], $row['client_ip']);
}

$mysqli->close();




