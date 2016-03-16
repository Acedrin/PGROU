<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des fonctions de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    require("../controllers/getFunctions.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion fonctions";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion fontions";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Serveur</th>
                    <th>Fonction</th>
                    <th>Input</th>
                    <th>Output</th>
                    <th>Action</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($functions); $i++) {
                    ?>
                    <tr>

                        <td>
                            <?php
                            print_r($functions[$i]['server_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($functions[$i]['function_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            
                            ?>
                        </td>
                        <td>
                            <?php

                            ?>
                        </td>
                        <td>
                            <a href="ajout_fonction.php?fonction_id=<?php print_r($functions[$i]['function_id']) ?>"><img src="../../public/img/edit.png" title="Modifier la fonction" alt="Modifier"></a>

                            &nbsp;

                            <a href="gestion_acces_fonction.php?function_id=<?php print_r($functions[$i]['function_id']) ?>"><img src="../../public/img/lock.gif" title="Gérer les droits d'accès de la fonction" alt="Droits d'accès"></a>

                            &nbsp;

                            <a href="../controllers/deleteFunction.php?function_id=<?php print_r($functions[$i]['function_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer la fonction <?php print_r($functions[$i]['function_name']) ?> du serveur <?php print_r($functions[$i]['server_name']) ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer la fonction" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br />
            <br />

            <a href="ajout_fonction.php"><button type="button">Ajouter une fonction</button></a>
            <a href="ajout_serveur.php"><button type="button">Ajouter un serveur</button></a>
            <?php include("../../app/views/layout.html"); ?>
        </div>
    </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>