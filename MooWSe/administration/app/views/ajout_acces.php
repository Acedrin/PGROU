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
//ini_set("display_errors", 0);
//error_reporting(0);

if (isset($_SESSION['login'])) {
    if (isset($_GET['client_id'])) {
        $client_id = $_GET['client_id'];
        require("../controllers/getClients.php");
    }

    if (isset($_GET['server_id'])) {
        $server_id = $_GET['server_id'];
    }
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
                // Ajout d'un client
                ?>
                <form name="formAdd" action="../controllers/addClient.php" method="POST">

                    <label for="client_name">Nom du client :</label>
                    <input type="text" name="client_name" id="client_name" placeholder="Nom" required/>

                    <br />

                    <label for="client_ip">IP du client :</label>
                    <input type="text" name="client_ip" id="client_ip" placeholder="IP" required/>

                    <br />

                    <label for="modality_id">Modalit&eacute; de connexion du client :</label>
                    <?php
                    $keys = array_keys($modalities);
                    ?>
                    <select name="modality_id">
                        <option value=0>&nbsp;</option>
                        <?php
                        // Récupération des ids des modalités
                        $keys = array_keys($modalities);

                        for ($j = 0; $j < sizeof($modalities); $j++) {
                            $modality_id = $keys[$j];
                            ?>
                            <option value=<?php echo $modality_id ?>><?php print_r($modalities[$modality_id]) ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <br />
                    <br />

                    <label for="client_password">Mot de passe du client :</label>
                    <input type="password" name="client_password" id="client_password" placeholder="Password" required/>

                    <br />

                    <label for="client_password_confirmation">Confirmation du mot de passe du client :</label>
                    <input type="password" name="client_password_confirmation" id="client_password_confirmation" placeholder="Retype password" required/>

                    <br />
                    <br />

                    <a href="gestion_clients.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireClient(2)">Valider</button>
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