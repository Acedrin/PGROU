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
    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Accueil";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Accueil";

    require("../views/header.php");
    ?>
        <body>

            <div class="navigation">
                <h2>Bienvenue sur l'interface d'administration de MooWse</h2>
                <a href="gestion_fonctions.php"><div class="navigation2">Gestion des serveurs et leurs fonctions</br></div></a>
                <a href="gestion_clients.php"><div class="navigation2">Gestion des clients</br></div></a>
                <a href="gestion_administrateurs.php"><div class="navigation2">Gestion des administrateurs</br></div></a>
                <a href="gestion_types.php"><div class="navigation2">Gestion des types</br></div></a>
                <?php include("../../app/views/footer.php"); ?>
            </div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>