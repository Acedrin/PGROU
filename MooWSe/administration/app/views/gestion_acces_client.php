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

    $client_id = $_GET['client_id'];

    require("../controllers/getAccess.php");
    require("../controllers/getClients.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion droits d'acc&egrave;s d'un client";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion droits d'acc&egrave;s d'un client";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Adresse IP</th>
                    <th>Modalit&eacute; de connexion</th>
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


            <p>
                Ce client a acc&egrave;s aux fonctions suivantes :
            </p>

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
                            <a href="../controllers/deleteAccess.php?client_id=<?php print_r($client[0]['client_id']) ?>&function_id=<?php print_r($access[$i]['function_id']) ?>&retour=client" 
                               onclick="return(confirm('Voulez vous vraiment supprimer l''accès du client <?php print_r($clients[0]['client_name']) ?> \n\
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

            <a href="ajout_acces.php?client_id=<?php print_r($client[0]['client_id']) ?>"><button type="button">Ajouter un droit d'acc&egrave;s</button></a>
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