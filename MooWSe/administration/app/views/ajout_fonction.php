<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue pour ajouter/modifier une ou des fonctions de MooWse

  Christophe Cleuet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
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

    // Vérification de si un paramètre a été donné (=modification d'une fonction)
    if (isset($_GET['function_id'])) {
        $function_id = $_GET['function_id'];
        require("../controllers/getFunctions.php");
    } else if (isset($_GET['function_nb'])) {
        // C'est un ajout de plusieurs fonctions
        $function_nb = $_GET['function_nb'];
    } else {
        // L'utilisateur est venu directement sur la vue
        $function_nb = 1;
    }

    // Indication que la requête vient de la vue d'ajout de fonction pour le controller getServers
    $function_add = true;
    require("../controllers/getServers.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout/modification de fonctions";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout/modification de fonctions";

    require("../views/header.php");
    ?>

    <body>
        <div class="navigation">
            <?php
            // Vérification de l'existence de $server
            // Son existence implique une modification d'un client existant

            if (isset($function)) {
                // Modification d'un serveur
                ?>
                <form name="formAdd" action="../controllers/addFunction.php" method="POST">

                    <input type="hidden" name="server_id" id="server_id" value="<?php print_r($server[0]['server_id']) ?>"/>

                    <label for="function_name">Nom du serveur :</label>
                    <input type="text" name="function_name" id="function_name" value="<?php print_r($server[0]['function_name']) ?>" placeholder="Nom" required/>

                    <br />

                    <label for="server_soapadress">Adresse SOAP :</label>
                    <input type="text" name="server_soapadress" id="server_soapadress" value="<?php print_r($server[0]['server_soapadress']) ?>" placeholder="Adresse SOAP" required/>

                    <br />
                    <br />

                    <a href="gestion_fonctions.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireFonction(1)">Valider</button>
                </form>
                <?php
            } else {
                // Ajout de fonctions
                ?>
                <form name="formAdd" action="../controllers/addFunction.php" method="POST">
                    <?php
                    // Répétition de la div autant de fois que demandé
                    for ($i = 0; $i < $function_nb; $i++) {
                        ?>

                        <div class="couleur<?php print_r($i % 2) ?>">

                            <h3>Fonction n°<?php echo($i + 1) ?></h3>

                            <label for="function_name[]">Nom de la fonction :</label>
                            <input type="text" name="function_name[]" id="function_name<?php print_r($i) ?>" placeholder="Nom" required/>

                            <br />

                            <label for="server_id[]">Serveur associé à la fonction :</label>
                            <select name="server_id[]" id="server_id<?php print_r($i) ?>">
                                <option value=0>&nbsp;</option>
                                <?php
                                // Récupération des ids des serveurs
                                $keys = array_keys($servers_list);

                                for ($j = 0; $j < sizeof($servers_list); $j++) {
                                    $server_id = $keys[$j];
                                    if ($server_id == $function[0]['server_id']) {
                                        ?>
                                        <option value=<?php echo $server_id ?> selected><?php print_r($servers_list[$server_id]) ?></option>
                                        <?php
                                    } else {
                                        ?>
                                        <option value=<?php echo $server_id ?>><?php print_r($servers_list[$server_id]) ?></option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>

                            <br />
                            <br />
                        </div>

                        <?php
                    }
                    ?>
                    <a href="gestion_fonctions.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireFonction(<?php print_r($function_nb) ?>)">Valider</button>
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