<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier de script
  Accueil de l'application

  Quentin Payet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

session_start();
ini_set("display_errors", 0);
error_reporting(0);
if (isset($_SESSION['login'])) {
    if (isset($_SESSION['timestamp'])) { // si $_SESSION['timestamp'] existe
        if ($_SESSION['timestamp'] + 300 > time()) {
            $_SESSION['timestamp'] = time();
        } else {
            header("Location:../controllers/deconnexion.php"); // deconnexion au bout de 5 minutes d'inactivite
            exit();
        }
    } else {
        $_SESSION['timestamp'] = time();
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <title>MooWse - Accueil Administration</title>
            <link href="../../public/css/accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
        </head>
        <body>

            <div class="navigation">
                <h2>Bienvenue sur l'interface d'administration de MooWse</h2>
                <div class="navigation2"><a href="remplissage.php">Modification d'un WebService</a></br></div>
                <div class="navigation2"><a href="modification.php">Configuration de la base de données</a></br></div>
                <div class="navigation2"><a href="gestion_administrateurs.php">Gestion des administrateurs</a></br></div>
                <div class="navigation2"><a href="gestion_clients.php">Gestion des clients</a></br></div>
                <div class="navigation2"><a href="gestion_fonctions.php">Gestion des serveurs et leurs fonctions</a></br></div>
                <div class="navigation2"><a href="gestion_types.php">Gestion des types</a></br></div>
                <form action="../controllers/deconnexion.php" method="POST">
                    <p><button type="submit">Déconnexion</button></p>
                </form>
                <h6>Moteur de Webservices de l'Ecole Centrale de Nantes.</h6></div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>