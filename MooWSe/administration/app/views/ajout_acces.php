<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue pour ajouter un droit d'accès pour un client de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    if (isset($_GET['client_id'])) {
        $client_id = $_GET['client_id'];
    }

    if (isset($_GET['function_id'])) {
        $function_id = $_GET['function_id'];
    }

    require("../controllers/getClients.php");
    require("../controllers/getFunctions.php");
    require("../controllers/getAccess.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout de droits d'acc&egrave;s";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout de droits d'acc&egrave;s";

    require("../views/header.php");
    ?>

    <?php
    // Récupération des function_id auxquelles le client a accès
    $acces_function = array();
    for ($i = 0; $i < sizeof($access); $i++) {
        $acces_function[] = $access[$i]['function_id'];
    }

    // Récupération des client_id qui ont accès à la fonction
    $acces_client = array();
    for ($i = 0; $i < sizeof($access); $i++) {
        $acces_client[] = $access[$i]['client_id'];
    }
    ?>

    <body>
        <div class="navigation">
            <?php
            // Vérification de l'existence de $client
            // Son existence implique une modification d'un client existant

            if (isset($client_id)) {
                // Ajout d'un droit d'accès à partir d'un client
                ?>

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
                    Ajouter les droits d'acc&egrave;s suivants :
                </p>

                <form name="formAdd" action="../controllers/addAccess.php" method="POST">

                    <input type="hidden" name="client_id[]" value="<?php print_r($client[0]['client_id']) ?>"/>
                    <input type="hidden" name="retour" value="client"/>

                    <table>
                        <tr>
                            <th>Serveur</th>
                            <th>Fonction</th>
                            <th>Droit d'acc&egrave;s</th>
                        </tr>
                        <?php
                        for ($i = 0; $i < sizeof($functions); $i++) {
                            ?>
                            <?php
                            if (!in_array($functions[$i]['function_id'], $acces_function)) {
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
                                        <input type="checkbox" name="function_id[]" value="<?php print_r($functions[$i]['function_id']) ?>">
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>

                    <br />
                    <br />

                    <a href="gestion_acces_client.php?client_id=<?php print_r($client[0]['client_id']) ?>"><button type="button">Annuler</button></a>
                    <button type="submit">Valider</button>
                </form>
                <?php
            } else {
                // Ajout d'un droit d'accès à partir d'une fonction
                ?>

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


                <p>
                    Ajouter les droits d'acc&egrave;s suivants :
                </p>

                <form name="formAdd" action="../controllers/addAccess.php" method="POST">

                    <input type="hidden" name="function_id[]" value="<?php print_r($function[0]['function_id']) ?>"/>
                    <input type="hidden" name="retour" value="fonction"/>
                    
                    <table>
                        <tr>
                            <th>Nom</th>
                            <th>IP</th>
                            <th>Modalit&eacute; de connexion</th>
                            <th>Droit d'acc&egrave;s</th>
                        </tr>
                        <?php
                        for ($i = 0; $i < sizeof($clients); $i++) {
                            ?>
                            <?php
                            if (!in_array($clients[$i]['client_id'], $acces_client)) {
                                ?>
                                <tr>

                                    <td>
                                        <?php
                                        print_r($clients[$i]['client_name']);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        print_r($clients[$i]['client_ip']);
                                        ?>
                                    </td>
                                    <td>
                                        <?php
                                        print_r($modalities[$clients[$i]['modality_id']]);
                                        ?>
                                    </td>
                                    <td>
                                        <input type="checkbox" name="client_id[]" value="<?php print_r($clients[$i]['client_id']) ?>">
                                    </td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>

                    <br />
                    <br />

                    <a href="gestion_acces_fonction.php?function_id=<?php print_r($function[0]['function_id']) ?>"><button type="button">Annuler</button></a>
                    <button type="submit">Valider</button>
                </form>
                <?php
            }
            include("../../app/views/layout.html");
            ?>
        </div>
    </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>