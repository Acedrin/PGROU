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
    require("../controllers/getClients.php");
    print_r($_SESSION['alert']);
    // Remise à zéro de la variable d'alerte
    $_SESSION['alert'] = "";
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <link href="../../public/css/accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
            <title>MooWse - Gestion clients</title>
        </head>
        <body>
            <div class="navigation">
                <h1>Espace Administration de MooWse</h1>
                <h2>Gestion clients</h2>

                <table>
                    <tbody>
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
                            <tr id="see_client_<?php print_r($clients[$i]['client_id']) ?>">

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

                                    <a href="../controllers/deleteClient.php?client_id=<?php print_r($clients[$i]['client_id']) ?>" onclick=""return confirm('Voulez vous vraiment supprimer le client <?php print_r($clients[$i]['client_name']) ?>><img src="../../public/img/delete.png" title="Supprimer le client" alt="Supprimer"></a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>

                <br />
                <br />

                <a href="ajout_client.php"><button type="button">Ajouter un client</button></a>
                 <?php include("../../app/views/layout.html"); ?>
            </div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.html");
}
?>