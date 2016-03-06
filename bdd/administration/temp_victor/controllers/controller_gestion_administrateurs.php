<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of controller_gestion_administrateurs
 *
 * @author Victor Enaud
 */

include("../models/user.php");

function show() {
    $users = page();
    include_once("../views/gestion_administrateurs.php");
}