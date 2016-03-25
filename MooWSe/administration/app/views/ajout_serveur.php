<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue pour ajouter ou modifier un serveur de MooWse

  Christophe Cleuet
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    // Vérification de si un paramètre a été donné (=modification d'un client)
    if (isset($_GET['server_id'])) {
        $server_id = $_GET['server_id'];
        require("../controllers/getServers.php");
    }

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout/modification d'un serveur";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout/modification d'un serveur";

    require("../views/header.php");
    ?>

    <body>
        <div class="navigation">
            <?php
            // Vérification de l'existence de $server
            // Son existence implique une modification d'un serveur existant

            if (isset($server)) {
                // Modification d'un serveur
                ?>
                <form name="formAdd" action="../controllers/addServer.php" method="POST">

                    <input type="hidden" name="server_id" id="server_id" value="<?php print_r($server[0]['server_id']) ?>"/>

                    <label for="server_name">Nom du serveur :</label>
                    <input type="text" name="server_name" id="server_name" value="<?php print_r($server[0]['server_name']) ?>" placeholder="Nom" required/>

                    <br />

                    <label for="server_soapadress">Adresse SOAP :</label>
                    <input type="text" name="server_soapadress" id="server_soapadress" value="<?php print_r($server[0]['server_soapadress']) ?>" placeholder="Adresse SOAP" required/>

                    <br />
                    <br />

                    <a href="gestion_fonctions.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireServeur()">Valider</button>
                </form>
                <?php
            } else {
                // Ajout d'un serveur
                ?>
                <form name="formAdd" action="../controllers/addServer.php" method="POST">

                    <label for="server_name">Nom du serveur :</label>
                    <input type="text" name="server_name" id="server_name" placeholder="Nom" required/>

                    <br />

                    <label for="server_soapadress">Adresse SOAP :</label>
                    <input type="text" name="server_soapadress" id="server_soapadress" placeholder="Adresse SOAP" required/>
                    
                    <br />
                    <br />

                    <a href="gestion_fonctions.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireServeur()">Valider</button>
                </form>
                <?php
            }
            include("../../app/views/footer.php");
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