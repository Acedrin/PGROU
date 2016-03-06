<?php
session_start();
ini_set("display_errors", 0);
error_reporting(0);
if (isset($_SESSION['login'])) {
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <title>MooWse - Accueil Administration</title>
            <link href="accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
        </head>
        <body>
            
            <div class="navigation">
                <h2>Bienvenue sur l'interface d'administration de MooWse</h2>
                <div class="navigation2"><a href="">Modification d'un WebService</a></br></div>
                <div class="navigation2"><a href="">Configuration de la base de données</a></br></div>
                <div class="navigation2"><a href="users/getUsers.php">Gestion des administrateurs de MooWse</a></br></div>
                <div class="navigation2"><a href="">Historique</a></br></div>
            <form action="deconnexion.php" method="POST">
                <p><button type="submit">Déconnexion</button></p>
            </form>
                <h6>Moteur de Webservices de l'Ecole Centrale de Nantes.</h6></div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:index.html");
}
?>