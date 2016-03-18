<?php
// Démarrage de la session avant toute chose
session_start();
// Désactivation de l'affichage des erreurs
ini_set("display_errors", 0);
error_reporting(0);

function connectMaBase() {
    $bdd = mysql_connect('localhost', 'root', 'root');
    mysql_select_db('moowse', $bdd);
}

if (isset($_SESSION['login'])) {

// Définition des variables nécessaires pour le header
    $titre_web = "MooWse - Administration de la base";
    $titre_principal = "Espace Administration de MooWse";
    $titre_section = "Administration de la base";

    require("../views/header.php");
    ?>
    <body>
        <div class="navigation">
            <div style="background-color:darksalmon">
                <h3>Serveur</h3>

                <form name="server" method="post" action="modification.php">
                    <!-- On choisit le serveur qu'on souhaite modifier/supprimer --> 
                    <label for="server_name" style="display:block;width: 150px;float:left"> Server : </label>
                    <select name="server_name" id="server_name">
                        <?php
                        try { // On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des serveurs 
                        $reponse = mysql_query('SELECT DISTINCT server_name FROM server');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?> <!--On affiche la liste des serveurs -->
                            <option value="<?php echo $data['server_name']; ?>"> <?php echo $data['server_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select> </br>
                    <!--On choisit de modifier ou de supprimer le serveur -->
                    <input type="submit" name="modifier_serveur" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_serveur" onclick="return confirm('Confirmer?')" value="Supprimer"/>
                    <?php
                    // Si on a choisi de supprimer
                    if (isset($_POST['supprimer_serveur'])) {
                        // On récupère le nom du serveur
                        $server_name = $_POST['server_name'];
                        // On se connecte
                        connectMaBase();
                        // On supprime le serveur de la base de données
                        $sql = 'DELETE FROM Server WHERE server_name ="' . $server_name . '"';
                        echo "<script>alert(\"Suppression de la base de donn\351es\")</script>";
                        mysql_query($sql) or die('Erreur SQL !' . $sql . '<br/>' . mysql_error());
                        // On ferme la connexion
                        mysql_close();
                    }
                    // Si on a choisi de modifier le serveur
                    elseif (isset($_POST['modifier_serveur'])) {      // On récupère le nom du serveur choisi
                        $server_name = $_POST['server_name'];
                        // On récupère l'adress soap associée à ce serveur
                        $req = mysql_query('SELECT server_soapadress FROM server WHERE server_name ="' . $server_name . '"');
                        $row = mysql_fetch_row($req);
                        ?>
                        </br>
                        <!-- On écrit le nom du serveur --> 
                        <label style="display:block;width: 150px;float:left "> Serveur choisi : </label><input type="text" readonly name="server_name" value="<?php echo $server_name; ?>"/><br/>
                        <!-- L'utilisateur peut alors modifier l'adress Soap --> 
                        <label style="display:block;width: 150px;float:left "> Soap_adress : </label><input type="text" name="soap_adress" value="<?php echo $row[0]; ?>"/><br/>
                        <!-- On confirme la modification --> 
                        <input type="submit" name="valider_server" onclick="return confirm('Confirmer?')" value="OK"/>
                    </form>

                    </br>
                    <?php
// On ferme la connexion  
                    mysql_close();
                }
                ?>

                <?php
                // Si on a confirmé la modification
                if (isset($_POST['valider_server'])) {
                    //On récupère les valeurs entrées par l'utilisateur :
                    $server_name = $_POST['server_name'];
                    $server_soapadress = $_POST['soap_adress'];

                    //On se connecte
                    connectMaBase();

                    //On prépare la commande sql d'update
                    $sql = 'UPDATE server SET server_soapadress="' . $server_soapadress . '" WHERE server_name="' . $server_name . '" ';
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
                <h3>Fonction</h3>
                <form name="function" method="post" action="modification.php">
                    <!-- On choisit la fonction u'on souhaite modifier/supprimer --> 
                    <label for="function_name" style="display:block;width: 150px;float:left"> Fonction : </label>
                    <select name="function_name" id="function_name">
                        <?php
                        try { // On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des fonctions
                        $reponse = mysql_query('SELECT DISTINCT function_name FROM function');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?> <!--On affiche la liste des fonctions -->
                            <option value="<?php echo $data['function_name']; ?>"> <?php echo $data['function_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select> </br>
                    <!--On choisit de modifier ou de supprimer la fonction-->
                    <input type="submit" name="modifier_function" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_function" onclick="return confirm('Confirmer?')" value="Supprimer"/>
                    <?php
                    // Si on a choisi de supprimer
                    if (isset($_POST['supprimer_function'])) {// On récupère le nom de la fonction
                        $function_name = $_POST['function_name'];
                        // On se connecte			
                        connectMaBase();
// On supprime la fonction de la base de données
                        $sql = 'DELETE FROM Function WHERE function_name ="' . $function_name . '"';
                        echo "<script>alert(\"Suppression de la base de donn\351es\")</script>";
                        mysql_query($sql) or die('Erreur SQL !' . $sql . '<br/>' . mysql_error());
                        // On ferme la connexion
                        mysql_close();
                    }
                    // Si on a choisi de modifier la fonctions
                    elseif (isset($_POST['modifier_function'])) {     // On récupère le nom de la fonctions choisie 
                        $function_name = $_POST['function_name'];
                        // On récupère le serveur associé à cette fonctions
                        $req = mysql_query('SELECT server_name FROM server,function WHERE function.function_name ="' . $function_name . '" AND function.server_id=server.server_id');
                        $row = mysql_fetch_row($req);
                        ?>
                        </br>
                        <!-- On écrit le nom de la fonction --> 
                        <label style="display:block;width: 150px;float:left "> Fonction choisie : </label><input type="text" readonly name="function_name" value="<?php echo $function_name; ?>"/><br/>
                        <!-- L'utilisateur peut alors modifier le serveur --> 
                        <label style="display:block;width: 150px;float:left "> Server : </label>
                        <select name="server_name" id="server_name">
                            <!-- On affiche le serveur associé --> 
                            <option value="<?php echo $row[0]; ?>"> <?php echo $row[0]; ?></option>
                            <?php
                            try { // On se connecte
                                connectMaBase();
                            } catch (Exception $e) {
                                die('Erreur : ' . $e->getMessage());
                            }
// On récupère la liste des serveurs 
                            $reponse = mysql_query('SELECT DISTINCT server_name FROM server');

                            while ($data = mysql_fetch_array($reponse)) {
                                ?> <!--On affiche la liste des serveurs -->

                                <option value="<?php echo $data['server_name']; ?>"> <?php echo $data['server_name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        <!-- On confirme la modification --> 
                        <input type="submit" name="valider_function" onclick="return confirm('Confirmer?')" value="OK"/>
                    </form>

                    </br>
                    <?php
// On ferme la connexion    
                    mysql_close();
                }
                ?>

                <?php
// Si on a confirmé la modification
                if (isset($_POST['valider_function'])) {
//On récupère les valeurs entrées par l'utilisateur :
                    $function_name = $_POST['function_name'];
                    $server_name = $_POST['server_name'];

//On se connecte
                    connectMaBase();

//On prépare la commande sql d'update 
                    $sql = 'UPDATE function SET function.server_id=(SELECT server_id FROM server WHERE server.server_name="' . $server_name . '") WHERE function.function_name="' . $function_name . '"';
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
            <div style="background-color:darksalmon">
                <h3>Variable</h3>
                <!-- On choisit la variable qu'on souhaite modifier/supprimer --> 
                <form name="variable" method="post" action="modification.php">
                    <label for="variable_name" style="display:block;width: 150px;float:left"> Variable : </label>

                    <select name="variable_name" id="variable_name">
                        <?php
                        try {  // On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des variables
                        $reponse = mysql_query('SELECT DISTINCT variable_name FROM variable');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?>  <!--On affiche la liste des variables -->
                            <option value="<?php echo $data['variable_name']; ?>"> <?php echo $data['variable_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select> </br> <!--On choisit de modifier ou de supprimer la variable -->
                    <input type="submit" name="modifier_variable" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_variable" onclick="return confirm('Confirmer?')" value="Supprimer"/>
                    <?php
                    // Si on a choisi de supprimer
                    if (isset($_POST['supprimer_variable'])) {  // On récupère le nom de la variable
                        $variable_name = $_POST['variable_name'];
                        // On se connecte	
                        connectMaBase();
                        // On supprime la variable de la base de données
                        $sql = 'DELETE FROM Variable WHERE variable_name ="' . $variable_name . '"';
                        echo "<script>alert(\"Suppression de la base de donn\351es\")</script>";
                        mysql_query($sql) or die('Erreur SQL !' . $sql . '<br/>' . mysql_error());
                        // On ferme la connexion
                        mysql_close();
                    }
                    // Si on a choisi de modifier la variable
                    elseif (isset($_POST['modifier_variable'])) {      // On récupère le nom de la varialbe choisi
                        $variable_name = $_POST['variable_name'];
                        // On récupère la fonction associée
                        $req_function = mysql_query('SELECT function_name FROM function, variable WHERE variable.variable_name ="' . $variable_name . '" AND variable.function_id=function.function_id');
                        $row_function = mysql_fetch_row($req_function);
                        // On récupère le type de la varibale
                        $req_type = mysql_query('SELECT type_name FROM type, variable WHERE variable.variable_name ="' . $variable_name . '" AND variable.type_id=type.type_id');
                        $row_type = mysql_fetch_row($req_type);
                        // On indique si la variable est en entrée/sortie
                        $req_input = mysql_query('SELECT variable_input FROM variable WHERE variable.variable_name ="' . $variable_name . '"');
                        $row_input = mysql_fetch_row($req_input);
                        // On récupère l'ordre de la variable			 
                        $req_order = mysql_query('SELECT variable_order FROM variable WHERE variable.variable_name ="' . $variable_name . '"');
                        $row_order = mysql_fetch_row($req_order);
                        ?>
                        </br>
                        <label style="display:block;width: 150px;float:left "> Variable choisie : </label><input type="text" readonly name="variable_name" value="<?php echo $variable_name; ?>"/><br/>
                        <!-- L'utilisateur peut alors modifier toutes les autres informations -->
                        <label style="display:block;width: 150px;float:left "> Fonction: </label>
                        <select name="function_name" id="function_name">
                            <!--On affiche la fonction associée-->
                            <option value="<?php echo $row_function[0]; ?>"> <?php echo $row_function[0]; ?></option>
                            <?php
                            try { // On se connecte
                                connectMaBase();
                            } catch (Exception $e) {
                                die('Erreur : ' . $e->getMessage());
                            }
// On récupère la liste des fonctions
                            $reponse = mysql_query('SELECT DISTINCT function_name FROM function');

                            while ($data = mysql_fetch_array($reponse)) {
                                ?> <!--On affiche la liste des fonctions -->
                                <option value="<?php echo $data['function_name']; ?>"> <?php echo $data['function_name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                        </br>
                        <label style="display:block;width: 150px;float:left "> Type: </label>
                        <!--On affiche le type associé-->
                        <select name="type_name" id="type_name">
                            <option value="<?php echo $row_type[0]; ?>" > <?php echo $row_type[0]; ?></option>
                            <?php
                            try { // On se connecte
                                connectMaBase();
                            } catch (Exception $e) {
                                die('Erreur : ' . $e->getMessage());
                            }
// On récupère la liste des types
                            $reponse = mysql_query('SELECT DISTINCT type_name FROM type');

                            while ($data = mysql_fetch_array($reponse)) {
                                ?>  <!--On affiche la liste des types -->
                                <option value="<?php echo $data['type_name']; ?>" > <?php echo $data['type_name']; ?></option>
                                <?php
                            }
                            ?>
                        </select> </br>
                        <label style="display:block;width: 150px;float:left "> Order: </label><input type="text" name="type_order" value="<?php echo $row_order[0]; ?>"/><br/>
                        <label style="display:block;width: 150px;float:left"> Input/Output: </label> 
                        <input type="radio" name="variable_input" value="1" <?php
                        if ("$row_input[0]" == "1") {
                            echo "checked";
                        }
                        ?>/> <label>Input</label>
                        <input type="radio" name="variable_input" value="0" <?php
                        if ("$row_input[0]" == "0") {
                            echo "checked";
                        }
                        ?>/> <label>Output</label><br />
                        <!-- On confirme la modification --> 
                        <input type="submit" name="valider_variable" onclick="return confirm('Confirmer?')" value="OK"/>
                    </form>

                    </br>
                    <?php
// On ferme la connexion   
                    mysql_close();
                }
                ?>

                <?php
                // Si on a confirmé la modification
                if (isset($_POST['valider_variable'])) {        //On récupère les valeurs entrées par l'utilisateur :     
                    $variable_name = $_POST['variable_name'];
                    $function_name = $_POST['function_name'];
                    $type_name = $_POST['type_name'];
                    $type_order = $_POST['type_order'];
                    $variable_input = $_POST['variable_input'];

                    //On se connecte
                    connectMaBase();

                    //On prépare la commande sql d'update
                    $sql = 'UPDATE variable SET variable.function_id=(SELECT function_id FROM function WHERE function.function_name="' . $function_name . '"),
                                     variable.type_id=(SELECT type_id FROM type WHERE type.type_name="' . $type_name . '"),
                                     variable.variable_order="' . $type_order . '",
									 variable.variable_input="' . $variable_input . '"
		 WHERE variable.variable_name="' . $variable_name . '"';
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
                <h3>Type</h3>
                <form name="type" method="post" action="modification.php">
                    <!-- On choisit le type qu'on souhaite modifier/supprimer -->
                    <label for="type_name" style="display:block;width: 150px;float:left"> Type: </label>
                    <select name="type_name" id="type_name">
                        <?php
                        try { // On se connecte
                            connectMaBase();
                        } catch (Exception $e) {
                            die('Erreur : ' . $e->getMessage());
                        }
// On récupère la liste des types
                        $reponse = mysql_query('SELECT DISTINCT type_name FROM type');

                        while ($data = mysql_fetch_array($reponse)) {
                            ?>  <!--On affiche la liste des types -->
                            <option value="<?php echo $data['type_name']; ?>" > <?php echo $data['type_name']; ?></option>
                            <?php
                        }
                        ?>
                    </select> </br>
                    <!--On choisit de modifier ou de supprimer le type -->
                    <input type="submit" name="modifier_type" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_type" onclick="return confirm('Confirmer?')" value="Supprimer"/>
                    <?php
                    // Si on a choisi de supprimer
                    if (isset($_POST['supprimer_type'])) {
                        // On récupère le nom du type
                        $server_name = $_POST['type_name'];
                        // On se connecte
                        connectMaBase();
                        // On supprime le type de la base de données
                        $sql = 'DELETE FROM Type WHERE type_name ="' . $type_name . '"';
                        echo "<script>alert(\"Suppression de la base de donn\351es\")</script>";
                        mysql_query($sql) or die('Erreur SQL !' . $sql . '<br/>' . mysql_error());
                        // On ferme la connexion
                        mysql_close();
                    }
                    // Si on a choisi de modifier le type
                    elseif (isset($_POST['modifier_type'])) {      // On récupère le nom du type choisi
                        $type_name = $_POST['type_name'];
                        // On indique si le type est complexe ou non
                        $req = mysql_query('SELECT type_complex FROM type WHERE type_name ="' . $type_name . '"');
                        $row = mysql_fetch_row($req);
                        ?>
                        </br>
                        <!-- On écrit le nom du type --> 
                        <label style="display:block;width: 150px;float:left "> Type choisi : </label><input type="text" readonly name="type_name" value="<?php echo $type_name; ?>"/><br/>
                        <!-- L'utilisateur peut alors modifier le complexité du type -->
                        <label style="display:block;width: 150px;float:left"> Type Complexe: </label> 
                        <input type="radio" name="type_complex" value="1" <?php
                               if ("$row[0]" == "1") {
                                   echo "checked";
                               }
                               ?>/> <label>Oui</label>
                        <input type="radio" name="type_complex" value="0" <?php
                if ("$row[0]" == "0") {
                    echo "checked";
                }
                ?>/> <label>Non</label><br />
                        <!-- On confirme la modification --> 
                        <input type="submit" name="valider_type" onclick="return confirm('Confirmer?')" value="OK"/>
                    </form>

                    </br>
                    <?php
// On ferme la connexion    
                    mysql_close();
                }
                ?>

                <?php
// Si on a confirmé la modification
                if (isset($_POST['valider_type'])) {   //On récupère les valeurs entrées par l'utilisateur :
                    $type_name = $_POST['type_name'];
                    $type_complex = $_POST['type_complex'];
//On se connecte
                    connectMaBase();
//On prépare la commande sql d'update
                    $sql = 'UPDATE type SET type_complex="' . $type_complex . '" WHERE type_name="' . $type_name . '" ';
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
