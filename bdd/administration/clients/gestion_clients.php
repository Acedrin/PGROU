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
    require("getClients.php");
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <link href="../accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
            <title>MooWse - Gestion clients</title>
            <script>
                // Fonction pour afficher/cacher la zone d'ajout d'un nouvel administrateur
                function toggleNewClient() {
                    if (document.getElementById("new_client").style.display == "none") {
                        document.getElementById("new_client").style.display = "block";
                    } else {
                        document.getElementById("new_client").style.display = "none";
                    }
                }

                // Fonction pour afficher/cacher la ligne de présentation et la
                // ligne d'édition d'un administrateur existant
                function toggleEdit(id) {
                    if (document.getElementById("see_client_" + id).style.display == "none") {
                        document.getElementById("see_client_" + id).style.display = "";
                    } else {
                        document.getElementById("see_client_" + id).style.display = "none";
                    }
                    if (document.getElementById("edit_client_" + id).style.display == "none") {
                        document.getElementById("edit_client_" + id).style.display = "";
                    } else {
                        document.getElementById("edit_client_" + id).style.display = "none";
                    }
                }
            </script>
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
                            <!-- Ligne de présentation d'un client existant
                            La ligne est visible par défaut -->
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
                                    <form action="deleteClient.php" method="POST">
                                        <input type="hidden" name="client_id" value=<?php print_r($clients[$i]['client_id']) ?>>
                                        <button type="submit">Supprimer</button>
                                    </form>
                                    <br/>
                                    <a href="ajout_client.php?client_id=<?php print_r($clients[$i]['client_id']) ?>"><img src="../img/edit.png" title="Modifier le client" alt="Edit"></a>
                                </td>
                            </tr>

                            <!-- Ligne d'édition d'un client existant
                            La ligne est cachée par défaut -->
                        <form action="addClient.php" method="POST">
                            <input type="hidden" name="client_id" value=<?php print_r($clients[$i]['client_id']) ?>>
                            <tr id="edit_client_<?php //print_r($clients[$i]['client_id']) ?>" style="display:none">

                                <td>
                                    <input type="text" size="20" name="client_name" value="<?php print_r($clients[$i]['client_name']) ?>">
                                </td>
                                <td>
                                    <input type="text" size="20" name="client_ip" value="<?php print_r($clients[$i]['client_ip']) ?>">
                                </td>
                                <td>
                                    <?php
                                    $keys = array_keys($modalities);
                                    ?>
                                    <select name="modality_id">
                                        <?php
                                        // Récupération des ids des modalités
                                        $keys = array_keys($modalities);

                                        for ($j = 0; $j < sizeof($modalities); $j++) {
                                            $modality_id = $keys[$j];

                                            if ($modality_id == $clients[$i]['modality_id']) {
                                                ?>
                                                <option value=<?php echo $modality_id ?> selected><?php print_r($modalities[$modality_id]) ?></option>
                                                <?php
                                            } else {
                                                ?>
                                                <option value=<?php echo $modality_id ?>><?php print_r($modalities[$modality_id]) ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <input type="password" size="20" name="client_password" placeholder="Password">
                                    <br/>
                                    <input type="password" size="20" name="client_password_verification" placeholder="Retype password">
                                </td>
                                <td>
                                    <button type = "button" onClick = "toggleEdit(<?php print_r($clients[$i]['client_id']) ?>)">Annuler</button>
                                    <br/>
                                    <button type = "submit">Confirmer</button>
                                </td>

                            </tr>
                        </form>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
                <!-- Formulaire d'ajout d'un nouveau client
                Le formulaire est caché par défaut  -->
                <p><button type="button" onClick="toggleNewClient()">Ajouter un client</button></p>
                <div id="new_client" style="display:none">
                    <h2>Ajouter un client</h2>
                    <form action="addClient.php" method="POST">
                        <table>
                            <tbody>
                                <tr>
                                    <th>Nom</th>
                                    <th>Adresse IP</th>
                                    <th>Modalité de connexion</th>
                                    <th>Mot de Passe</th>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="text" size="20" name="client_name" placeholder="Nom">
                                    </td>
                                    <td>
                                        <input type="text" size="20" name="client_ip" placeholder="Adresse IP">
                                    </td>
                                    <td>
                                        <?php
                                        $keys = array_keys($modalities);
                                        ?>
                                        <select name="modality_id">
                                            <option value=0>&nbsp;</option>
                                            <?php
                                            // Récupération des ids des modalités
                                            $keys = array_keys($modalities);

                                            for ($j = 0; $j < sizeof($modalities); $j++) {
                                                $modality_id = $keys[$j];
                                                ?>
                                                <option value=<?php echo $modality_id ?>><?php print_r($modalities[$modality_id]) ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </td>
                                    <td>
                                        <input type="password" size="20" name="client_password" placeholder="Password">
                                        <br/>
                                        <input type="password" size="20" name="client_password_verification" placeholder="Retype password">
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4"><button type="submit">Ajouter</button></td>
                                </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../index.html");
}
?>