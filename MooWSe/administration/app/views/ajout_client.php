<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue pour ajouter ou modifier un client de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    // Vérification de si un paramètre a été donné (=modification d'un client)
    if (isset($_GET['client_id'])) {
        $client_id = $_GET['client_id'];
        require("../controllers/getClients.php");
    } else {
        $client_id = 0;
        require("../controllers/getClients.php");
    }

    // Vérification d'un paramètre password, signifiant que c'est pour une modification de mot de passe
    if (isset($_GET['password'])) {
        $password = true;
    } else {
        $password = false;
    }

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout/modification d'un client";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout/modification d'un client";

    require("../views/header.php");
    ?>

    <body>
        <div class="navigation">
            <?php
            // Vérification de l'existence de $client
            // Son existence implique une modification d'un client existant

            if (isset($client) && !$password) {
                // Modification d'un client
                ?>
                <form name="formAdd" action="../controllers/addClient.php" method="POST">

                    <input type="hidden" name="client_id" id="client_id" value="<?php print_r($client[0]['client_id']) ?>"/>

                    <label for="client_name">Nom du client :</label>
                    <input type="text" name="client_name" id="client_name" value="<?php print_r($client[0]['client_name']) ?>" placeholder="Nom" required/>

                    <br />

                    <label for="client_ip">IP du client :</label>
                    <input type="text" name="client_ip" id="client_ip" value="<?php print_r($client[0]['client_ip']) ?>" placeholder="IP" required/>

                    <br />

                    <label for="modality_id">Modalit&eacute; de connexion du client :</label>
                    <select name="modality_id">
                        <?php
                        // Récupération des ids des modalités
                        $keys = array_keys($modalities);

                        for ($j = 0; $j < sizeof($modalities); $j++) {
                            $modality_id = $keys[$j];
                            if ($modality_id == $client[0]['modality_id']) {
                                ?>
                                <option value=<?php echo $modality_id ?> selected><?php print_r($modalities[$modality_id]) ?></option>
                                <?php
                            } else {
                                ?>
                                <option value=<?php echo $modality_id ?>><?php print_r($modalities[$modality_id]) ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>

                    <br />
                    <br />

                    <a href="gestion_clients.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireClient(1)">Valider</button>
                </form>
                <?php
            } else if (isset($client) && $password){
                // Modification du mot de passe d'un client
                ?>
                <form name="formAdd" action="../controllers/addClient.php" method="POST">
                    <input type="hidden" name="client_id" id="client_id" value="<?php print_r($client[0]['client_id']) ?>"/>

                    <label for="client_password">Nouveau mot de passe du client :</label>
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
                    <button type="button" onclick="validerFormulaireClient(3)">Valider</button>
                </form>
                <?php
            }
            include("../../app/views/layout.html")
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