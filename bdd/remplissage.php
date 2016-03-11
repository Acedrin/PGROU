<?php
function connectMaBase(){
    $bdd = mysql_connect ('localhost', 'root', 'root');  
    mysql_select_db ('moowse', $bdd) ;
}
?>
<html>
    <head><title>Formulaire de remplissage</title></head>
    <body>
        <h2>Formulaire de remplissage :</h2>
		<div>
        <h3>Ajouter un Serveur</h3>
        <form name="server" method="post" action="remplissage.php">
            <label style="display:block;width: 150px;float:left"> Name : </label> <input type="text" name="name"/><br/>
            </br>
            <label style="display:block;width: 150px;float:left "> Soap_adress : </label><input type="text" name="soap_adress"/><br/>
            <input type="submit" name="valider_server" onclick="return confirm('Confirmer?')" value="OK"/>
        </form>
        <?php
        if (isset ($_POST['valider_server'])){
            //On récupère les valeurs entrées par l'utilisateur :
            $server_name=$_POST['name'];
            $server_soapadress=$_POST['soap_adress'];
         
            //On se connecte
			connectMaBase();
 
            //On prépare la commande sql d'insertion
            $sql = 'INSERT INTO server VALUES("","'.$server_name.'","'.$server_soapadress.'")';
       	 /*on lance la commande (mysql_query) et au cas où,
            on rédige un petit message d'erreur si la requête ne passe pas
            (Message qui intègrera les causes d'erreur sql)*/
			echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br />'.mysql_error());
            
            // on ferme la connexion
            mysql_close();
        }
        ?>
       </div>
	   <div>
<h3>Ajouter une fonction</h3>
        <form name="server" method="post" action="remplissage.php">
            <label style="display:block;width: 150px;float:left"> Name : </label> <input type="text" name="name"/><br/>
            </br>
            <label for="server_name" style="display:block;width: 150px;float:left"> Server: </label>
            <select name="server_name" id="server_name">

<?php

try
{
   connectMaBase();
}
catch(Exception $e)
{
            die('Erreur : '.$e->getMessage());
}

$reponse = mysql_query('SELECT DISTINCT server_name FROM server');
 
while ($data= mysql_fetch_array($reponse))
{
?>
           <option value="<?php echo $data['server_name'];?>" > <?php echo $data['server_name']; ?></option>
<?php
}

?>

</select> </br>
<input type="submit" name="valider_function" onclick="return confirm('Confirmer?')" value="OK"/>
 </form>
 
       
        <?php
		
        if (isset ($_POST['valider_function'])){
           
            $function_name=$_POST['name'];
			$server_name=$_POST['server_name'];
					
            connectMaBase();

             $sql = 'INSERT INTO function VALUES("",(SELECT server_id FROM server WHERE server_name ="'.$server_name.'"),"'.$function_name.'")';
            echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
            
            mysql_close();
        }
		
        ?>
		</div>
		 <div>
        <h3>Ajouter des variables &agrave; une fonction</h3>
        <form name="server" method="post" action="remplissage.php">
            <label style="display:block;width: 150px;float:left"> Name: </label> <input type="text" name="name"/><br/>
            </br>
            <label for="function_name" style="display:block;width: 150px;float:left"> Function: </label>
            <select name="function_name" id="function_name_variable">

<?php

try
{
   connectMaBase();
}
catch(Exception $e)
{
            die('Erreur : '.$e->getMessage());
}

$reponse = mysql_query('SELECT DISTINCT function_name FROM function');
 
while ($data= mysql_fetch_array($reponse))
{
?>
           <option value="<?php echo $data['function_name'];?>" > <?php echo $data['function_name']; ?></option>
<?php
}

?>

</select> </br>
</br>
<label for="type_name" style="display:block;width: 150px;float:left"> Type: </label>
            <select name="type_name" id="type">

<?php

try
{
   connectMaBase();
}
catch(Exception $e)
{
            die('Erreur : '.$e->getMessage());
}

$reponse = mysql_query('SELECT type_name FROM type');
 
while ($data= mysql_fetch_array($reponse))
{
?>
           <option value="<?php echo $data['type_name'];?>" > <?php echo $data['type_name']; ?></option>
<?php
}

?>

</select> 
</br>
</br>
 <label style="display:block;width: 150px;float:left"> Entr&eacute;e/Sortie: </label> 
       <input type="radio" name="input" value="1"/> <label for="input">Input</label>
       <input type="radio" name="input" value="0" /> <label for="output">Output</label><br />
	   </br>
<label style="display:block;width: 150px;float:left"> Order: </label> <input type="text" name="order"/><br/>
<input type="submit" name="valider_variable" onclick="return confirm('Confirmer?')" value="OK"/>
 </form>
 
       
        <?php
		
        if (isset ($_POST['valider_variable'])){
           
            $variable_name=$_POST['name'];
			$function_name=$_POST['function_name'];
			$input=$_POST['input'];
			$order=$_POST['order'];
			$type_name=$_POST['type_name'];
					
            connectMaBase();

            $sql = 'INSERT INTO variable VALUES("","'.$variable_name.'","'.$input.'","'.$order.'",(SELECT DISTINCT function_id FROM function WHERE function_name ="'.$function_name.'"),(SELECT DISTINCT type_id FROM type WHERE type_name ="'.$type_name.'"))';
            echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
 
            mysql_close();
        }
		
        ?>
		</div>
		<div>
        <h3>Ajouter un Type</h3>
        <form name="server" method="post" action="remplissage.php">
            <label style="display:block;width: 150px;float:left"> Name : </label> <input type="text" name="name"/><br/>
            </br>
       <label style="display:block;width: 150px;float:left"> Type Complexe: </label> 
       <input type="radio" name="type_complex" value="1"/> <label for="moins15">Oui</label>
       <input type="radio" name="type_complex" value="non" /> <label for="medium15-25">Non</label><br />
            <input type="submit" name="valider_type" onclick="return confirm('Confirmer?')" value="OK"/>
        </form>
        <?php
        if (isset ($_POST['valider_type'])){

            $type_name=$_POST['name'];
            $type_complex=$_POST['type_complex'];
         
          
			connectMaBase();
 
          
            $sql = 'INSERT INTO type VALUES("","'.$type_name.'","","'.$type_complex.'")';
           echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br />'.mysql_error());
 
            mysql_close();
        }
        ?>
       </div>
		
    </body>
</html>