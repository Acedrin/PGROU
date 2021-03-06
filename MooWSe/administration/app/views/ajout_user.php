<?php
/* --------------------------------------------------
  Projet MOOWSE
  Fichier html
  Vue pour ajouter ou modifier un administrateur de MooWse

  Victor Enaud
  Ecole Centrale de Nantes
  -------------------------------------------------- */

// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

if (isset($_SESSION['login'])) {
    // Vérification de si un paramètre a été donné (=modification d'un administrateur)
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        require("../controllers/getUsers.php");
    }
    
     // Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout/modification d'un administrateur";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout/ Modification d'un Administrateur";

    require("../views/header.php");
    ?>
        <body>
            <div class="navigation">

                <?php
                // Vérification de l'existence de $user
                // Son existence implique une modification d'un adminitrateur existant

                if (isset($user)) {
                    // Modification d'un administrateur
                    ?>
                    <form name="formAdd" action="../controllers/addUser.php" method="POST">

                        <input type="hidden" name="user_id" id="user_id" value="<?php print_r($user[0]['user_id']) ?>"/>

                        <label for="user_uid">Login de l'administrateur :</label>
                        <input type="text" name="user_uid" id="user_uid" value="<?php print_r($user[0]['user_uid']) ?>" placeholder="Login" required/>

                        <br />

                        <p>
                            Date d'expiration du login (jj-mm-aaa) :
                            <br/>
                            <i>0-0-0 indiquera que le login a une validité infinie</i>
                        </p>
                        <select name="jour">
                            <?php
                            $date = explode('-', $user[0]['user_expirationdate']);
                            $today = date('Y');
                            for ($j = 0; $j <= 31; $j++) {
                                if ($j == $date['2']) {
                                    ?>
                                    <option value="<?php echo $j ?>" selected><?php echo $j ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value = "<?php echo $j ?>"><?php echo $j ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <select name="mois">
                            <?php
                            for ($j = 0; $j <= 12; $j++) {
                                if ($j == $date['1']) {
                                    ?>
                                    <option value="<?php echo $j ?>" selected><?php echo $j ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value = "<?php echo $j ?>"><?php echo $j ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                        <select name="annee">
                            <option value="0">0</option>
                            <?php
                            for ($j = $today; $j <= $today + 10; $j++) {
                                if ($j == $date['0']) {
                                    ?>
                                    <option value="<?php echo $j ?>" selected><?php echo $j ?></option>
                                    <?php
                                } else {
                                    ?>
                                    <option value = "<?php echo $j ?>"><?php echo $j ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>

                        <br />
                        <br />

                        <a href="gestion_administrateurs.php"><button type="button">Annuler</button></a>
                        <button type="button" onclick="validerFormulaireUser()">Valider</button>
                    </form>
                    <?php
                } else {
                    // Ajout d'un administrateur
                    ?>
                    <form name="formAdd" action="../controllers/addUser.php" method="POST">

                        <label for="user_uid">Login de l'administrateur :</label>
                        <input type="text" name="user_uid" id="user_uid" placeholder="Login" required/>

                        <br />

                        <p>
                            Date d'expiration du login (jj-mm-aaa) :
                            <br/>
                            <i>0-0-0 indiquera que le login a une validité infinie</i>
                        </p>
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

                        <br />
                        <br />

                        <a href="gestion_administrateurs.php"><button type="button">Annuler</button></a>
                        <button type="button" onclick="validerFormulaireUser()">Valider</button>
                    </form>
                    <?php
                }
                include("../../app/views/footer.php");
                ?>
            </div>

            <script type="text/javascript" src="../../public/js/functions.js"></script>

        </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>