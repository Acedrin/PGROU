<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des variables d'une fonction de MooWse

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

    require("../controllers/getFunctions.php");
    require("../controllers/getTypes.php");
    require("../controllers/getVariables.php");

    // Initialisation des compteurs du nombre d'inputs et d'outputs
    $nb_input = 0;
    $nb_output = 0;

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion des variables d'une fonction";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion des variables d'une fonction";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Serveur</th>
                    <th>Fonction</th>
                    <th>Input</th>
                    <th>Output</th>
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
                    <td>
                        <?php
                        for ($j = 0; $j < sizeof($variables); $j++) {
                            if ($variables[$j]['function_id'] == $function[0]['function_id'] && $variables[$j]['variable_input'] == 1) {
                                print_r($variables[$j]['variable_order']);
                                echo(" : ");
                                print_r($variables[$j]['variable_name']);
                                echo(" (");
                                print_r($variables[$j]['type_name']);
                                echo(")");
                                echo("<br/>");
                            }
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        for ($j = 0; $j < sizeof($variables); $j++) {
                            if ($variables[$j]['function_id'] == $function[0]['function_id'] && $variables[$j]['variable_input'] == 0) {
                                print_r($variables[$j]['variable_order']);
                                echo(" : ");
                                print_r($variables[$j]['variable_name']);
                                echo(" (");
                                print_r($variables[$j]['type_name']);
                                echo(")");
                                echo("<br/>");
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>


            <p>
                Configurer les variables de cette fonction :
            </p>

            <table>
                <tr>
                    <th>Entr&eacute;e/Sortie</th>
                    <th>Ordre</th>
                    <th>Nom</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($variables); $i++) {
                    ?>
                    <tr class="couleur<?php print_r($variables[$i]['variable_input']) ?>">
                        <td>
                            <?php
                            if ($variables[$i]['variable_input'] == 1) {
                                // Compteur du nombre d'inputs
                                $nb_input = $nb_input + 1;

                                echo("Input");
                            } else {
                                // Compteur du nombre d'outputs
                                $nb_output = $nb_output + 1;

                                echo("Output");
                            }
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($variables[$i]['variable_order']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($variables[$i]['variable_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($variables[$i]['type_name']);
                            ?>
                        </td>
                        <td>
                            <a href="../controllers/deleteVariable.php?variable_id=<?php print_r($variables[$i]['variable_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer la variable <?php print_r($variables[$i]['variable_name']); ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer a variable" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br />
            <br />

            <a href="gestion_fonctions.php"><button type="button">Retour</button></a>
            <a href="#"><button type="button" onclick="show()">Ajouter une variable</button></a>
            <br />
            <br />
            <div id="hide" style="display:none">
                <form name="formAdd" action="../controllers/addVariable.php" method="POST">
                    <input type="hidden" name="function_id" value="<?php print_r($function[0]['function_id']) ?>"/>

                    <label for="variable_name">Nom :</label>
                    <input type="text" name="variable_name" placeholder="Nom">

                    <br />

                    <label for="variable_input">Entr&eacute;e/Sortie :</label>
                    <input type="radio" name="variable_input" value="1">Input
                    <input type="radio" name="variable_input" value="0">Output

                    <br/>

                    <label for="variable_order">Ordre :</label>
                    <input type="text" name="variable_order" placeholder="Ordre">

                    <br />

                    <label for="type_id">Type :</label>
                    <select name="type_id">
                        <option value=0>&nbsp;</option>
                        <?php
                        // Récupération des ids des types
                        $keys = array_keys($types_list);

                        for ($j = 0; $j < sizeof($types_list); $j++) {
                            $liste_type_id = $keys[$j];
                            ?>
                            <option value=<?php echo $liste_type_id ?>><?php print_r($types_list[$liste_type_id]) ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <br />
                    <br />

                    <button type="button" onclick="validerFormulaireVariable()">Valider</button>
                </form>
            </div>

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