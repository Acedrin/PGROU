<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of connexion_bdd
 *
 * @author Victor Enaud
 */

try {
    $serveur = new PDO('mysql:host=localhost;dbname=moowse;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
