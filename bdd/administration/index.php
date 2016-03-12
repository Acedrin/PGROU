<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Page de connexion à l'application MooWse
 *
 * @author Quentin Payet
 */


session_start();
ini_set("display_errors", 0);
error_reporting(0);
if (!isset($_SESSION['login'])) {
    ?>
<!DOCTYPE html>
<html lang="fr-fr">
	<head> 
		<title>MooWse</title>
                <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
                <link href="/public/css/login.css" rel="stylesheet" type="text/css"/>
	</head>
	<body>
		<form action="/app/controllers/connexion.php" method="POST">
			<h1>Moowse</h1>
			<p><input type="text" name="login" placeholder="Login"></p>
			<p><input type="password" name="password" placeholder="Password"></p>
			<p><button type="submit">Se connecter</button></p>
		</form>
	</body>
</html>
<?php
} else {
    // si quelqu'un est deja connecte, on le renvoie directement a l'accueil
    header('Content-Type: text/html; charset=utf-8');
    header("Location:/app/views/accueil.php");
}
?>