<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des accès des fonctions de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login']) && isset($_GET['function_id'])) {

    $function_id = $_GET['function_id'];

    require("../controllers/getAccess.php");
    require("../controllers/getFunctions.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion droits d'acc&egrave;s à une fonction";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion droits d'acc&egrave;s &agrave; une fonction";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Serveur</th>
                    <th>Fonction</th>
                </tr>
                <tr>
                    <td>
                        <?php
                        print_r($function[0]['server_name']);
                        ?>
                    </td>
                    <td>
                        <?php
                        print_r($function[0]['function_name']);
                        ?>
                    </td>
                </tr>
            </table>


            <p> Les clients suivants ont acc&egrave;s &agrave; cette fonction :
            <table>
                <tr>
                    <th>Client</th>
                    <th>Adresse IP</th>
                    <th>Modalit&eacute; de connexion</th>
                    <th>Action</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($access); $i++) {
                    ?>
                    <tr>

                        <td>
                            <?php
                            print_r($access[$i]['client_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($access[$i]['client_ip']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($access[$i]['modality_name']);
                            ?>
                        </td>
                        <td>
                            <a href="../controllers/deleteAccess.php?client_id=<?php print_r($access[$i]['client_id']) ?>&function_id=<?php print_r($function[0]['function_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer l''accès du client <?php print_r($access[$i]['client_name']) ?> \n\
                                    à la fonction <?php print_r($access[$i]['function_name']) ?> \n\
                                    du serveur <?php print_r($access[$i]['server_name']) ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer le droit d'acc&egrave;s" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br />
            <br />

            <a href=""><button type="button">Ajouter un droit d'accès</button></a>
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