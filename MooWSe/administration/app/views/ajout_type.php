<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue pour ajouter ou modifier un type de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    // Vérification de si un paramètre a été donné (=modification d'un type)
    if (isset($_GET['type_id'])) {
        $type_id = $_GET['type_id'];
        require("../controllers/getTypes.php");
    }
    
     // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout/modification d'un type";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout/ Modification d'un type";

    require("../views/header.php");
    ?>
        <body>
            <div class="navigation">

                <?php
                // Vérification de l'existence de $type_id
                // Son existence implique une modification d'un type existant

                if (isset($type_id)) {
                    // Modification d'un administrateur
                    ?>
                    <form name="formAdd" action="../controllers/addType.php" method="POST">

                        <input type="hidden" name="type_id" id="type_id" value="<?php print_r($type[0]['type_id']) ?>"/>

                        <label for="type_name">Nom :</label>
                        <input type="text" name="type_name" id="type_name" value="<?php print_r($type[0]['type_name']) ?>" required/>

                        <br />

                        <label for="type_complex">Type Complexe :</label>
                        <?php
                        if ($type[0]['type_complex'] == 1) {
                            ?>
                            <input type="checkbox" name="type_complex" id="type_complex" checked required/>
                        <?php
                        } else {
                            ?>
                            <input type="checkbox" name="type_complex" id="type_complex" required/>
                        <?php
                        }
                        ?>
                        
                        <br />
                        <br />

                        <a href="gestion_types.php"><button type="button">Annuler</button></a>
                        <button type="button" onclick="validerFormulaireType()">Valider</button>
                    </form>
                    <?php
                } else {
                    // Ajout d'un type
                    ?>
                    <form name="formAdd" action="../controllers/addType.php" method="POST">
                        <label for="type_name">Nom :</label>
                        <input type="text" name="type_name" id="type_name" placeholder="Nom" required/>

                        <br />

                        <label for="type_complex">Type Complexe :</label>
                        <input type="checkbox" name="type_complex" id="type_complex" required/>
                        
                        <br />
                        <br />

                        <a href="gestion_types.php"><button type="button">Annuler</button></a>
                        <button type="button" onclick="validerFormulaireType()">Valider</button>
                    </form>
                    <?php
                }
                include("../../app/views/footer.php");
                ?>
            </div>

            <script type="text/javascript" src="../../public/js/functions.js"></script>

        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>