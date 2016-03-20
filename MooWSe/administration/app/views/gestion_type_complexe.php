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

if (isset($_SESSION['login']) && isset($_GET['type_id'])) {
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

    $type_id = $_GET['type_id'];

    require("../controllers/getTypes.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion d'un type complexe";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion d'un type complexe";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Sous-types</th>
                </tr>
                <tr>
                    <td>
                        <?php
                        print_r($type[0]['type_name']);
                        ?>
                    </td>
                    <td>
                        <?php
                        for ($j = 0; $j < sizeof($typecomplex); $j++) {
                            if ($typecomplex[$j]['typecomplex_depends'] == $type[0]['type_id']) {
                                print_r($typecomplex[$j]['typecomplex_order']);
                                echo(" : ");
                                print_r($typecomplex[$j]['tyco_name']);
                                echo("<br/>");
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>


            <p>
                Configurer les différents sous-types de ce type complexe :
            </p>

            <table>
                <tr>
                    <th>Ordre</th>
                    <th>Type</th>
                    <th>Action</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($typecomplex); $i++) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            print_r($typecomplex[$i]['typecomplex_order']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($typecomplex[$i]['tyco_name']);
                            ?>
                        </td>
                        <td>
                            <a href="../controllers/deleteTypeComplex.php?typecomplex_depends=<?php print_r($typecomplex[$i]['typecomplex_depends']) ?>&typecomplex_order=<?php print_r($typecomplex[$i]['typecomplex_order']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer le sous-type num&eacute;ro <?php print_r($typecomplex[$i]['typecomplex_order']); ?> \
de type <?php print_r($typecomplex[$i]['tyco_name']); ?> \
du type complexe <?php print_r($type[0]['type_name']); ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer le sous-type" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br />
            <br />

            <a href="#"><button type="button" onclick="show()">Ajouter un sous-type</button></a>
            <br />
            <br />
            <div id="hide" style="display:none">
                <form name="formAdd" action="../controllers/addTypeComplex.php" method="POST">
                    <input type="hidden" name="typecomplex_depends" value="<?php print_r($type[0]['type_id']) ?>"/>

                    <label for="typecomplex_order">Ordre :</label>
                    <select name="typecomplex_order">
                        <option value=0>&nbsp;</option>
                        <?php
                        for ($j = 0; $j < sizeof($typecomplex) + 1; $j++) {
                            ?>
                            <option value=<?php echo($j + 1) ?>><?php echo($j + 1) ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <br />

                    <label for="typecomplex_type">Type :</label>
                    <select name="typecomplex_type" id="server_id0">
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

                    <a href="gestion_types.php"><button type="button">Annuler</button></a>
                    <button type="button" onclick="validerFormulaireTypeComplex()">Valider</button>
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