<?php
function connectMaBase(){
    $bdd = mysql_connect ('localhost', 'root', 'root');  
    mysql_select_db ('moowse', $bdd) ;
}
?>
<html>
    <head><title>Administration de la Base</title></head>
    <body>
	 <h2>Administration de la Base :</h2>
	<div>
	<h3>Serveur</h3>
	 <form name="server" method="post" action="modification.php">
	 <label for="server_name" style="display:block;width: 150px;float:left"> Server : </label>
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
<input type="submit" name="modifier_serveur" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_serveur" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 </form>
	 <?php
		
        if (isset ($_POST['supprimer_serveur'])){
           
            $server_name=$_POST['server_name'];
					
            connectMaBase();

            $sql = 'DELETE FROM Server WHERE server_name ="'.$server_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
            mysql_close();
        }
		
        ?>
		<?php
		
        if (isset ($_POST['modifier_serveur'])){
		?>


<?php
        }
?>

	</div>
	<div>
	<h3>Fonction</h3>
	 <form name="function" method="post" action="modification.php">
	 <label for="function_name" style="display:block;width: 150px;float:left"> Function: </label>
     <select name="function_name" id="function_name">

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
<input type="submit" name="modifier_function" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_function" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 </form>
	 <?php
		
        if (isset ($_POST['supprimer_function'])){
           
            $function_name=$_POST['function_name'];
					
            connectMaBase();

            $sql = 'DELETE FROM Function WHERE function_name ="'.$function_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
             mysql_close();
        }
		
        ?>
	</div>
	<div>
	<h3>Variable</h3>
	 <form name="variable" method="post" action="modification.php">
	 <label for="variable_name" style="display:block;width: 150px;float:left"> Variable: </label>
     <select name="variable_name" id="variable_name">

<?php

try
{
   connectMaBase();
}
catch(Exception $e)
{
            die('Erreur : '.$e->getMessage());
}

$reponse = mysql_query('SELECT DISTINCT variable_name FROM variable');
 
while ($data= mysql_fetch_array($reponse))
{
?>
           <option value="<?php echo $data['variable_name'];?>" > <?php echo $data['variable_name']; ?></option>
<?php
}

?>

</select> </br>
<input type="submit" name="modifier_variable" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_variable" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 </form>
	 <?php
		
        if (isset ($_POST['supprimer_variable'])){
           
            $variable_name=$_POST['variable_name'];
					
            connectMaBase();

            $sql = 'DELETE FROM Variable WHERE variable_name ="'.$variable_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
             mysql_close();
        }
		
        ?>
	</div>
	<div>
	<h3>Type</h3>
	 <form name="type" method="post" action="modification.php">
	 <label for="type_name" style="display:block;width: 150px;float:left"> Type: </label>
     <select name="type_name" id="type_name">

<?php

try
{
   connectMaBase();
}
catch(Exception $e)
{
            die('Erreur : '.$e->getMessage());
}

$reponse = mysql_query('SELECT DISTINCT type_name FROM type');
 
while ($data= mysql_fetch_array($reponse))
{
?>
           <option value="<?php echo $data['type_name'];?>" > <?php echo $data['type_name']; ?></option>
<?php
}

?>

</select> </br>
<input type="submit" name="modifier_type" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_type" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 </form>
	 <?php
		
        if (isset ($_POST['supprimer_type'])){
           
            $type_name=$_POST['type_name'];
					
            connectMaBase();

            $sql = 'DELETE FROM Type WHERE type_name ="'.$type_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
             mysql_close();
        }
		
        ?>
	</div>
</body>
</html>