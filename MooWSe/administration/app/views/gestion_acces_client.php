<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des accès des clients de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login']) && isset($_GET['client_id'])) {

    $client_id = $_GET['client_id'];

    require("../controllers/getAccess.php");
    require("../controllers/getClients.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion droits d'accès d'un client";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion droits d'accès d'un client";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Adresse IP</th>
                    <th>Modalité de connexion</th>
                </tr>
                <tr>
                    <td>
                        <?php
                        print_r($client[0]['client_name']);
                        ?>
                    </td>
                    <td>
                        <?php
                        print_r($client[0]['client_ip']);
                        ?>
                    </td>
                    <td>
                        <?php
                        print_r($modalities[$client[0]['modality_id']]);
                        ?>
                    </td>
                </tr>
            </table>


            <p> Ce client a accès aux fonctions suivantes :
            <table>
                <tr>
                    <th>Serveur</th>
                    <th>Fonction</th>
                    <th>Action</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($access); $i++) {
                    ?>
                    <tr>

                        <td>
                            <?php
                            print_r($access[$i]['server_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($access[$i]['function_name']);
                            ?>
                        </td>
                        <td>
                            <a href="../controllers/deleteAccess.php?client_id=<?php print_r($client[0]['client_id']) ?>&function_id=<?php print_r($access[$i]['function_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer l''accès du client <?php print_r($clients[0]['client_name']) ?> \n\
                                    à la fonction <?php print_r($access[$i]['function_name']) ?> \n\
                                    du serveur <?php print_r($access[$i]['server_name']) ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer le client" alt="Supprimer">
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