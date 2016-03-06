<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of user
 *
 * @author Victor Enaud
 */
include_once("connexion_bdd.php");

function page() {
    try {
        $stmt = $bdd->prepare("SELECT user_id, user_uid, user_expirationdate FROM user");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $query->execute();
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function add($user) {
    try {
        $stmt = $bdd->prepare("INSERT INTO  user(user_uid,  user_expirationdate) VALUES (:user_uid, :user_expirationdate)");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':user_uid', $user['user_uid']);
        $stmt->bindParam(':user_expirationdate', $user['user_expirationdate']);
        $stmt->execute();
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function update($user) {
    try {
        $stmt = $bdd->prepare("UPDATE user SET (user_uid, user_expirationdate) = (:user_uid, :user_expirationdate) WHERE user_id = (:user_id)");
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $stmt->bindParam(':user_uid', $user['user_uid']);
        $stmt->bindParam(':user_expirationdate', $user['user_expirationdate']);
        $stmt->bindParam(':user_id', $user['user_id']);
        $stmt->execute();
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function delete($user_id) {
    if ($user_id > 0) {
        try {
            $stmt = $bdd->prepare("DELETE FROM user where (:user_id)");
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }
}
