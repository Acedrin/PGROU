<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des types de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    require("../controllers/getTypes.php");
    
    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion Types";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion des Types";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <p>
                Types simples :
            </p>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Actions</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($types); $i++) {
                    if ($types[$i]['type_complex'] == 0) {
                        ?>
                        <tr>
                            <td>
                                <?php
                                print_r($types[$i]['type_name']);
                                ?>
                            </td>
                            <td>
                                <a href="ajout_type.php?type_id=<?php print_r($types[$i]['type_id']) ?>"><img src="../../public/img/edit.png" title="Modifier le type" alt="Modifier"></a>

                                &nbsp;

                                <a href="../controllers/deleteType.php?type_id=<?php print_r($types[$i]['type_id']) ?>" 
                                   onclick="return(confirm('Voulez-vous vraiment supprimer le type <?php print_r($types[$i]['type_name']) ?> ?'));">
                                    <img src="../../public/img/delete.png" title="Supprimer le type" alt="Supprimer">
                                </a>
                            </td>
                        </tr>  
                        <?php
                    }
                }
                ?>
            </table>
            <br/>
            <p>
                Types complexes :
            </p>
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Sous-types</th>
                    <th>Actions</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($types); $i++) {
                    if ($types[$i]['type_complex'] == 1) {
                        ?>
                        <tr>
                            <td>
                                <?php
                                print_r($types[$i]['type_name']);
                                ?>
                            </td>
                            <td>
                                <?php
                                for ($j = 0; $j < sizeof($typescomplex); $j++) {
                                    if ($typescomplex[$j]['typecomplex_depends'] == $types[$i]['type_id']) {
                                        print_r($typescomplex[$j]['typecomplex_order']);
                                        echo(" : ");
                                        print_r($typescomplex[$j]['tyco_name']);
                                        echo("<br/>");
                                    }
                                }
                                ?>
                            </td>
                            <td>
                                <a href="ajout_type.php?type_id=<?php print_r($types[$i]['type_id']) ?>"><img src="../../public/img/edit.png" title="Modifier le type" alt="Modifier"></a>

                                &nbsp;
                                
                                <a href="gestion_type_complexe.php?type_id=<?php print_r($types[$i]['type_id']) ?>"><img src="../../public/img/configuration.png" title="G&eacute;rer les sous-types" alt="Sous-types"></a>

                                &nbsp;

                                <a href="../controllers/deleteType.php?type_id=<?php print_r($types[$i]['type_id']) ?>" 
                                   onclick="return(confirm('Voulez-vous vraiment supprimer le type <?php print_r($types[$i]['type_name']) ?> ?'));">
                                    <img src="../../public/img/delete.png" title="Supprimer le type" alt="Supprimer">
                                </a>
                            </td>
                        </tr>  
                        <?php
                    }
                }
                ?>
            </table>
            <br />
            <br />

            <a href="ajout_type.php"><button type="button">Ajouter un type</button></a>
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