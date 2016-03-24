<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Page de connexion à l'application MooWse

  Quentin Payet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

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
            <link rel="shortcut icon" href="../../public/img/favicon.ico" type="image/x-icon">
            <link rel="icon" href="../../public/img/favicon.ico" type="image/x-icon">
        </head>
        <?php
        // Vérification de la présence d'une alerte
        if (isset($_SESSION['alert'])) {
            if ($_SESSION['alert'][0] == true) {
                // Le message est un message de confirmation
                ?>
                <div id="success_message">
                    <?php print_r($_SESSION['alert'][1]) ?>
                </div>
                <?php
            } else if ($_SESSION['alert'][0] == false) {
                // Le message est un message d'erreur
                ?>
                <div id="error_message">
                    <?php print_r($_SESSION['alert'][1]) ?>
                </div>
                <?php
            }
            // Suppression de la variable
            unset($_SESSION['alert']);
        }
        ?>
        <body>
            <form action="/app/controllers/connexion.php" method="POST">
                <h1>MooWse</h1>
                <p><input type="text" name="login" placeholder="Login"></p>
                <p><input type="password" name="password" placeholder="Password"></p>
                <p><button type="submit">Se connecter</button></p>
            </form>
        </body>
    </html>
    <?php
} else {
    // si quelqu'un est deja connecté, on le renvoie directement a l'accueil
    header('Content-Type: text/html; charset=utf-8');
    header("Location:/app/views/accueil.php");
}
?>