<?php
// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

function connectMaBase() {
    $bdd = mysql_connect('localhost', 'root', '');
    mysql_select_db('moowse', $bdd);
}

if (isset($_SESSION['login'])) {

// Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Ajout d'une fonction";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Ajout d'une fonction";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <div style="background-color:darksalmon">
                <h3>Ajouter un Serveur</h3>
                <form name="server" method="post" action="remplissage.php">
                    <!-- On écrit le nom du serveur --> 
                    <label style="display:block;width: 150px;float:left"> Name : </label> <input type="text" name="name"/><br/>
                    </br>
                    <!-- On écrit le nom de la Soap adress associée--> 
                    <label style="display:block;width: 150px;float:left "> Soap_adress : </label><input type="text" name="soap_adress"/><br/>
                    <input type="submit" name="valider_server" onclick="return confirm('Confirmer?')" value="OK"/>
                </form>
                <?php
                if (isset($_POST['valider_server'])) {
                    //On récupère les valeurs entrées par l'utilisateur :
                    $server_name = $_POST['name'];
                    $server_soapadress = $_POST['soap_adress'];

                    //On se connecte
                    connectMaBase();

                    //On prépare la commande sql d'insertion
                    $sql = 'INSERT INTO server VALUES("","' . $server_name . '","' . $server_soapadress . '")';
                    /* on lance la commande (mysql_query) et au cas où,
                      on rédige un petit message d'erreur si la requête ne passe pas
                      (Message qui intègrera les causes d'erreur sql) */
                    echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>";
                    mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());

                    // on ferme la connexion
                    mysql_close();
                }
                ?>
            </div>
            <div>
                <h3>Ajouter une fonction</h3>
                <form name="server" method="post" action="remplissage.php">
                    <!-- On écrit le nom de la fonction --> 
                    <label style="display:block;width: 150px;float:left"> Name : </label> <input type="text" name="name"/><br/>
                    </br>
                    <!-- On sélectionne le serveur associé--> 
                    <label for="server_name" style="display:block;width: 150px;float:left"> Server: </label>
                    <select name="server_name" id="server_name">

                        <?php
                        try {
                            //On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des serveurs 
                        $reponse = mysql_query('SELECT DISTINCT server_name FROM server');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?>
                            <!--On affiche la liste des serveurs -->
                            <option value="<?php echo $data['server_name']; ?>" > <?php echo $data['server_name']; ?></option>
                            <?php
                        }
                        ?>

                    </select> </br>
                    <!--On confirme l'ajout à la base de données -->
                    <input type="submit" name="valider_function" onclick="return confirm('Confirmer?')" value="OK"/>
                </form>


                <?php
                //Si on a confirmé l'ajout
                if (isset($_POST['valider_function'])) {
                    //On récupère les informations entrées par l'utilisateur 
                    $function_name = $_POST['name'];
                    $server_name = $_POST['server_name'];
                    // On se connecte			
                    connectMaBase();
                    // On ajoute les informations à la base de données
                    $sql = 'INSERT INTO function VALUES("",(SELECT server_id FROM server WHERE server_name ="' . $server_name . '"),"' . $function_name . '")';

                    echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>";
                    mysql_query($sql) or die('Erreur SQL !' . $sql . '<br/>' . mysql_error());
                    //On ferme la connexion    
                    mysql_close();
                }
                ?>
            </div>
            <div style="background-color:darksalmon"> 
                <h3>Ajouter des variables &agrave; une fonction</h3>
                <form name="server" method="post" action="remplissage.php">
                    <!-- On écrit le nom de la variable --> 
                    <label style="display:block;width: 150px;float:left"> Name: </label> <input type="text" name="name"/><br/>
                    </br>
                    <!-- On sélectionne la fonction associée --> 
                    <label for="function_name" style="display:block;width: 150px;float:left"> Function: </label>

                    <select name="function_name" id="function_name_variable">

                        <?php
                        try { // On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des fonctions
                        $reponse = mysql_query('SELECT DISTINCT function_name FROM function');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?><!--On affiche la liste des fonctions -->
                            <option value="<?php echo $data['function_name']; ?>" > <?php echo $data['function_name']; ?></option>
                            <?php
                        }
                        ?>

                    </select> </br>
                    </br> <!-- On choisit le nom du type associé--> 
                    <label for="type_name" style="display:block;width: 150px;float:left"> Type: </label>
                    <select name="type_name" id="type">

                        <?php
                        try { // On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des types
                        $reponse = mysql_query('SELECT type_name FROM type');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?> <!--On affiche la liste des types -->
                            <option value="<?php echo $data['type_name']; ?>" > <?php echo $data['type_name']; ?></option>
                            <?php
                        }
                        ?>

                    </select> 
                    </br>
                    </br> 
                    <!--On choisit si la variable est en entrée ou en sortie -->
                    <label style="display:block;width: 150px;float:left"> Entr&eacute;e/Sortie: </label> 
                    <input type="radio" name="input" value="1"/> <label for="input">Input</label>
                    <input type="radio" name="input" value="0" /> <label for="output">Output</label><br />
                    </br>
                    <!--On écrit l'ordre de la variable -->
                    <label style="display:block;width: 150px;float:left"> Order: </label> <input type="text" name="order"/><br/>
                    <!--On confirme l'ajout de la variable -->
                    <input type="submit" name="valider_variable" onclick="return confirm('Confirmer?')" value="OK"/>
                </form>


                <?php
                // Si on a confirmé l'ajout	
                if (isset($_POST['valider_variable'])) {
                    //On récupère les informations entrées par l'utilisateur 
                    $variable_name = $_POST['name'];
                    $function_name = $_POST['function_name'];
                    $input = $_POST['input'];
                    $order = $_POST['order'];
                    $type_name = $_POST['type_name'];
                    // On se connecte				
                    connectMaBase();
// On ajoute les informations à la base de données
                    $sql = 'INSERT INTO variable VALUES("","' . $variable_name . '","' . $input . '","' . $order . '",(SELECT DISTINCT function_id FROM function WHERE function_name ="' . $function_name . '"),(SELECT DISTINCT type_id FROM type WHERE type_name ="' . $type_name . '"))';
                    echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>";
                    mysql_query($sql) or die('Erreur SQL !' . $sql . '<br/>' . mysql_error());
                    //On ferme la connexion 
                    mysql_close();
                }
                ?>
            </div>
            <div>
                <h3>Ajouter un Type</h3>
                <form name="server" method="post" action="remplissage.php">
                    <!-- On écrit le nom du type--> 
                    <label style="display:block;width: 150px;float:left"> Name : </label> <input type="text" name="name"/><br/>
                    </br>
                    <!-- On indique si le type est complexe ou non--> 
                    <label style="display:block;width: 150px;float:left"> Type Complexe: </label> 
                    <input type="radio" name="type_complex" value="1"/> <label>Oui</label>
                    <input type="radio" name="type_complex" value="0" /> <label>Non</label><br />
                    <!-- On confirme l'ajout du type --> 
                    <input type="submit" name="valider_type" onclick="return confirm('Confirmer?')" value="OK"/>
                </form>
                <?php
                //Si on a confirmé l'ajout
                if (isset($_POST['valider_type'])) {
                    //On récupère les informations entrées par l'utilisateur 
                    $type_name = $_POST['name'];
                    $type_complex = $_POST['type_complex'];

                    // On se connecte
                    connectMaBase();
                    // On ajoute les informations à la base de données          
                    $sql = 'INSERT INTO type VALUES("","' . $type_name . '","xsd:' . $type_name . '","' . $type_complex . '")';
                    echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>";
                    mysql_query($sql) or die('Erreur SQL !' . $sql . '<br />' . mysql_error());
                    //On ferme la connexion   
                    mysql_close();
                }
                ?>
            </div>
            <?php
            include("../../app/views/layout.html");
            ?>
        </div>
    </body>
    </html>
    <?php
} else {
    header('Content-Type: text/html; charset=utf-8');
    header("Location:../../index.php");
}
?>
