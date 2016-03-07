<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue de l'interface de gestion des administrateurs de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

session_start();
ini_set("display_errors", 0);
error_reporting(0);
if (isset($_SESSION['login'])) {
    ?>
    <!DOCTYPE html>
    <html lang="fr-fr">
        <head>
            <link href="../accueil.css" type="text/css" rel="stylesheet" />
            <meta charset="UTF-8" />
            <title>MooWse - Gestion administrateurs</title>
            <script>
                // Fonction pour afficher/cacher la zone d'ajout d'un nouvel administrateur
                function toggleNewAdmin() {
                    if (document.getElementById("new_admin").style.display == "none") {
                        document.getElementById("new_admin").style.display = "block";
                    } else {
                        document.getElementById("new_admin").style.display = "none";
                    }
                }

                // Fonction pour afficher/cacher la ligne de présentation et la
                // ligne d'édition d'un administrateur existant
                function toggleEdit(id) {
                    if (document.getElementById("see_admin_" + id).style.display == "none") {
                        document.getElementById("see_admin_" + id).style.display = "";
                    } else {
                        document.getElementById("see_admin_" + id).style.display = "none";
                    }
                    if (document.getElementById("edit_admin_" + id).style.display == "none") {
                        document.getElementById("edit_admin_" + id).style.display = "";
                    } else {
                        document.getElementById("edit_admin_" + id).style.display = "none";
                    }
                }
            </script>
        </head>
        <body>
            <div class="navigation">
                <h1>Espace Administration de MooWse</h1>
                <h2>Gestion administrateurs</h2>
            </div>
            <table>
                <tbody>
                    <tr>
                        <th>Login</th>
                        <th>Expiration</th>
                        <th>Action</th>
                    </tr>
                    <?php
                    while ($row = $users->fetch()) {
                        ?>
                        <!-- Ligne de présentation d'un administrateur existant
                        La ligne est visible par défaut -->
                        <tr id="see_admin_<?php echo $row['user_id'] ?>">

                            <td>
                                <?php
                                echo $row['user_uid'];
                                ?>
                            </td>
                            <td>
                                <?php
                                echo $row['user_expirationdate'];
                                ?>
                            </td>
                            <td>
                                <form action="deleteUser.php" method="POST">
                                    <button type="submit">Supprimer</button>
                                </form>
                                <br/>
                                <button type="button" onClick="toggleEdit(<?php echo $row['user_id'] ?>)">Modifier</button>
                            </td>
                        </tr>

                        <!-- Ligne d'édition d'un administrateur existant
                        La ligne est cachée par défaut -->
                    <form action="addUser.php" method="POST">
                        <input type="hidden" name="user_id" value=<?php echo $row['user_id'] ?>>
                        <tr id="edit_admin_<?php echo $row['user_id'] ?>" style="display:none">

                            <td>
                                <input type="text" size="20" name="user_uid" value="<?php echo $row['user_uid'] ?>">
                            </td>
                            <td>
                                <select name="jour">
                                    <?php
                                    $date = explode('-', $row['user_expirationdate']);
                                    $today = date('Y');
                                    for ($i = 0; $i <= 31; $i++) {
                                        if ($i == $date['2']) {
                                            ?>
                                            <option value="<?php echo $i ?>" selected><?php echo $i ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <select name="mois">
                                    <?php
                                    for ($i = 0; $i <= 12; $i++) {
                                        if ($i == $date['1']) {
                                            ?>
                                            <option value="<?php echo $i ?>" selected><?php echo $i ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <select name="annee">
                                    <option value="0">0</option>
                                    <?php
                                    for ($i = $today; $i <= $today + 10; $i++) {
                                        if ($i == $date['0']) {
                                            ?>
                                            <option value="<?php echo $i ?>" selected><?php echo $i ?></option>
                                            <?php
                                        } else {
                                            ?>
                                            <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </td>
                            <td>
                                <button type = "button" onClick = "toggleEdit(<?php echo $row['user_id'] ?>)">Annuler</button>
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
        <!-- Formulaire d'ajout d'un nouvel administrateur
        Le formulaire est caché par défaut  -->
        <p><button type="button" onClick="toggleNewAdmin()">Ajouter un administrateur</button></p>
        <div id="new_admin" style="display:none">
            <h2>Ajouter un administrateur</h2>
            <form action="addUser.php" method="POST">
                <table>
                    <tbody>
                        <tr>
                            <th>Login</th>
                            <th>Expiration</th>
                        </tr>
                        <tr>
                            <td>
                                <input type="text" size="20" name="user_uid" value="">
                            </td>
                            <td>
                                <select name="jour">
                                    <?php
                                    $today = date('Y');
                                    for ($i = 0; $i <= 31; $i++) {
                                        ?>
                                        <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <select name="mois">
                                    <?php
                                    for ($i = 0; $i <= 12; $i++) {
                                        ?>
                                        <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                                <select name="annee">
                                    <option value="0">0</option>
                                    <?php
                                    for ($i = $today; $i <= $today + 10; $i++) {
                                        ?>
                                        <option value = "<?php echo $i ?>"><?php echo $i ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><button type="submit">Ajouter</button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../index.html");
}
?>