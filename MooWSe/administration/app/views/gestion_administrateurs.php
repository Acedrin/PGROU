<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des administrateurs de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    require("../controllers/getUsers.php");
    print_r($_SESSION['alert']);
    // Remise à zéro de la variable d'alerte
    $_SESSION['alert'] = "";
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <link href="../../public/css/accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
            <title>MooWse - Gestion administrateurs</title>
        </head>
        <body>
            <div class="navigation">
                <h1>Espace Administration de MooWse</h1>
                <h2>Gestion administrateurs</h2>

                <table>
                    <tr>
                        <th>Login</th>
                        <th>Expiration</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    for ($i = 0; $i < sizeof($users); $i++) {
                        ?>
                        <tr>

                            <td>
                                <?php
                                print_r($users[$i]['user_uid']);
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($users[$i]['user_expirationdate'] == "0000-00-00") {
                                    echo "Pas d'expiration";
                                } else {
                                    print_r($users[$i]['user_expirationdate']);
                                }
                                ?>
                            </td>
                            <td>
                                <a href="ajout_user.php?user_id=<?php print_r($users[$i]['user_id']) ?>"><img src="../../public/img/edit.png" title="Modifier l'administrateur" alt="Modifier"></a>

                                &nbsp;

                                <a href="../controllers/deleteUser.php?user_id=<?php print_r($users[$i]['user_id']) ?>" 
                                   onclick="return(confirm('Voulez vous vraiment supprimer l\'administrateur <?php print_r($users[$i]['user_uid']) ?> ?'));">
                                    <img src="../../public/img/delete.png" title="Supprimer l'administrateur" alt="Supprimer">
                                </a>
                            </td>
                        </tr>  
                        <?php
                    }
                    ?>
                </table>

                <br />
                <br />

                <a href="ajout_user.php"><button type="button">Ajouter un administrateur</button></a>
                <?php include("../../app/views/layout.html"); ?>
            </div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>