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
//ini_set("display_errors", 0);
//error_reporting(0);

if (isset($_SESSION['login'])) {
    // Vérification de si un paramètre a été donné (=modification d'un client)
    if (isset($_GET['client_id'])) {
        $client_id = $_GET['client_id'];
        require("getClients.php");
    } else {
        $client_id = 0;
        require("getClients.php");
    }
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <link href="../accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
            <title>MooWse - Ajout/modification d'un client</title>
        </head>
        <body>
            <script type="text/javascript">
                // Fonction pour valider le formulaire, vérifiant que les champs sont bien remplis
                function validerFormulaire(){
                    var alerte = "";
                    if (!document.getElementsByName('client_name')[0].value.length > 0) {
                        alerte += "Veuillez indiquer le nom du client !\n";
                        document.getElementsByName('client_name')[0].style.backgroundColor = '#FF9900';
                        document.getElementsByName('client_name')[0].style.color = '#000000';
                    }
                    if (!document.getElementsByName('client_ip')[0].value.length > 0) {
                        alerte += "Veuillez indiquer l'ip du client !\n";
                        document.getElementsByName('client_ip')[0].style.backgroundColor = '#FF9900';
                        document.getElementsByName('client_ip')[0].style.color = '#000000';
                    }
                    if (!document.getElementsByName('modality_id')[0].value = 0) {
                        alerte += "Veuillez indiquer le mode de connexion du client !\n";
                        document.getElementsByName('modality_id')[0].style.backgroundColor = '#FF9900';
                        document.getElementsByName('modality_id')[0].style.color = '#000000';
                    }
//                    if (type == 2) {
//                        if (document.getElementsByName('client_password')[0].value != document.getElementsByName('client_password_verification')[0].value) {
//                            alerte += "Veuillez renseigner deux fois le même mot de passe !\n";
//                            document.getElementsByName('client_password')[0].reset();
//                            document.getElementsByName('client_password_verification')[0].reset();
//
//                            document.getElementsByName('client_password')[0].style.backgroundColor = '#FF9900';
//                            document.getElementsByName('client_password')[0].style.color = '#000000';
//                            document.getElementsByName('client_password_verification')[0].style.backgroundColor = '#FF9900';
//                            document.getElementsByName('client_password_verification')[0].style.color = '#000000';
//                        }
//                    }

                    if (alerte == "") {
                        document.getElementsByName('formAdd')[0].submit();
                    } else {
                        alert(alerte);
                    }
                }
            </script>

            <div class="navigation">
                <h1>Espace Administration de MooWse</h1>
                <h2>Ajout/modification d'un client</h2>

                <?php
                // Vérification de l'existence de $client
                // Son existence implique une modification d'un client existant

                if (isset($client)) {
                    // Modification d'un client
                    ?>
                    <form name="formAdd" action="addClient.php" method="POST">

                        <label for="client_name">Nom du client :</label>
                        <input type="text" name="client_name" id="client_name" value="<?php print_r($client[0]['client_name']) ?>" placeholder="Nom" required/>

                        <br />

                        <label for="client_ip">IP du client :</label>
                        <input type="text" name="client_ip" id="client_ip" value="<?php print_r($client[0]['client_ip']) ?>" placeholder="IP" required/>

                        <br />

                        <label for="modality_id">Modalit&eacute; de connexion du client :</label>
                        <?php
                        $keys = array_keys($modalities);
                        ?>
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
                        <button type="button" onclick="validerFormulaire()">Valider</button>
                    </form>
                    <?php
                } else {
                    // Ajout d'un client
                    ?>
                    <form name="formAdd" action="addClient.php" method="POST">

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
                        <button type="button" onclick="validerFormulaire()">Valider</button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../index.html");
}
?>