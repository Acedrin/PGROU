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
    require("../controllers/getUsers.php");
    print_r($_SESSION['alert']);
    // Remise à zéro de la variable d'alerte
    $_SESSION['alert'] = "";
    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion Administrateur";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestions des Administrateurs";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">

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