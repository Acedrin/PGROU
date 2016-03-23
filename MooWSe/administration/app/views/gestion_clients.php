<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des clients de MooWse

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
    require("../controllers/getClients.php");

    // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Gestion clients";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Gestion clients";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <table>
                <tr>
                    <th>Nom</th>
                    <th>Adresse IP</th>
                    <th>Modalité de connexion</th>
                    <th>Mot de Passe</th>
                    <th>Actions</th>
                </tr>
                <?php
                for ($i = 0; $i < sizeof($clients); $i++) {
                    ?>
                    <tr>

                        <td>
                            <?php
                            print_r($clients[$i]['client_name']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($clients[$i]['client_ip']);
                            ?>
                        </td>
                        <td>
                            <?php
                            print_r($modalities[$clients[$i]['modality_id']]);
                            ?>
                        </td>
                        <td>
                            <?php
                            if ($clients[$i]['client_password'] == "") {
                                echo 'Non';
                            } else {
                                echo 'Oui';
                            }
                            ?>
                        </td>
                        <td>
                            <a href="ajout_client.php?client_id=<?php print_r($clients[$i]['client_id']) ?>"><img src="../../public/img/edit.png" title="Modifier le client" alt="Modifier"></a>

                            &nbsp;

                            <a href="ajout_client.php?client_id=<?php print_r($clients[$i]['client_id']) ?>&password=true"><img src="../../public/img/key.png" title="Modifier le mot de passe du client" alt="Mot de passe"></a>

                            &nbsp;

                            <a href="gestion_acces_client.php?client_id=<?php print_r($clients[$i]['client_id']) ?>"><img src="../../public/img/lock.gif" title="Gérer les droits d'accès du client" alt="Droits d'accès"></a>

                            &nbsp;

                            <a href="../controllers/deleteClient.php?client_id=<?php print_r($clients[$i]['client_id']) ?>" 
                               onclick="return(confirm('Voulez vous vraiment supprimer le client <?php print_r($clients[$i]['client_name']) ?> ?'));">
                                <img src="../../public/img/delete.png" title="Supprimer le client" alt="Supprimer">
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>

            <br />
            <br />

            <a href="ajout_client.php"><button type="button">Ajouter un client</button></a>
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