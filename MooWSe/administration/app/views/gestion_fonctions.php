<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des fonctions de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {

    require("../controllers/getFunctions.php");
    require("../controllers/getServers.php");
    require("../controllers/getVariables.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion des serveurs et des fonctions";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion des serveurs et des fonctions";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <!-- Tableau affichant l'ensemble des serveurs -->
            <p>
                Serveurs de MooWse :
            </p>

            <table>
                <tr>
                    <th>Nom</th>
                    <th>Adresse SOAP</th>
                    <th>Nombre de fonctions</th>
                    <th>Actions</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($servers); $i++) {
                    ?>
                    <tr>
                        <td>
                            <?php
                            print_r($servers[$i]['server_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($servers[$i]['server_soapadress']);
                            ?>
                        </td>
                        <td>
                            <?php
                            if (isset($nbFunctions[$servers[$i]['server_id']])) {
                                print_r($nbFunctions[$servers[$i]['server_id']]);
                            } else {
                                echo(0);
                            }
                            ?>
                        </td>
                        <td>
                            <a href="ajout_serveur.php?server_id=<?php print_r($servers[$i]['server_id']) ?>"><img src="../../public/img/edit.png" title="Modifier le serveur" alt="Modifier"></a>

                            &nbsp;

                            <a href="../controllers/deleteServer.php?server_id=<?php print_r($servers[$i]['server_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer le serveur <?php print_r($servers[$i]['function_name']) ?> ?\n\n\
ATTENTION - Cela supprimera toutes les <?php print_r($nbFunctions[$servers[$i]['server_id']]); ?> fonctions qui y sont encore associées !'));">
                                <img src="../../public/img/delete.png" title="Supprimer le serveur" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
            <br/>
            <!-- Tableau affichant l'ensemble des fonctions -->

            <p>
                Fonctions de MooWse :
            </p>

            <table>
                <tr>
                    <th>Serveur</th>
                    <th>Fonction</th>
                    <th>Input</th>
                    <th>Output</th>
                    <th>Actions</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($functions); $i++) {

                    // Vérification d'un changement d'id serveur pour alterner la couleur des lignes 
                    if ($i == 0) {
                        // Première ligne du tableau
                        $couleur = 0;
                    } else if ($functions[$i - 1]['server_id'] != $functions[$i]['server_id']) {
                        // La ligne concerne un nouveau serveur
                        // Incrémentation de couleur
                        $couleur = $couleur + 1;

                        // Couleur modulo 2 pour n'obtenir qu'un 0 ou un 1
                        $couleur = $couleur % 2;
                    }
                    ?>
                    <tr class="couleur<?php print_r($couleur) ?>">

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
                            <?php
                            for ($j = 0; $j < sizeof($variables); $j++) {
                                if ($variables[$j]['function_id'] == $functions[$i]['function_id'] && $variables[$j]['variable_input'] == 1) {
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
                                if ($variables[$j]['function_id'] == $functions[$i]['function_id'] && $variables[$j]['variable_input'] == 0) {
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
                            <a href="ajout_fonction.php?function_id=<?php print_r($functions[$i]['function_id']) ?>"><img src="../../public/img/edit.png" title="Modifier la fonction" alt="Modifier"></a>

                            &nbsp;

                            <a href="gestion_fonction_variables.php?function_id=<?php print_r($functions[$i]['function_id']) ?>"><img src="../../public/img/configuration.png" title="G&eacute;rer les variables" alt="Variables"></a>
                            
                            &nbsp;

                            <a href="gestion_acces_fonction.php?function_id=<?php print_r($functions[$i]['function_id']) ?>"><img src="../../public/img/lock.gif" title="Gérer les droits d'acc&egrave;s &agrave; la fonction" alt="Droits d'accès"></a>
                            
                            &nbsp;

                            <a href="../controllers/deleteFunction.php?function_id=<?php print_r($functions[$i]['function_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer la fonction <?php print_r($functions[$i]['function_name']) ?> du serveur <?php print_r($functions[$i]['server_name']) ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer la fonction" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br />
            <br />

            <a href="#"><button type="button" onclick="nbFunctions()">Ajouter des fonctions</button></a>
            <a href="ajout_serveur.php"><button type="button">Ajouter un serveur</button></a>
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