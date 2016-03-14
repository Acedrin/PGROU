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
<!-- On choisit le serveur qu'on souhaite modifier/supprimer --> 
	 <label for="server_name" style="display:block;width: 150px;float:left"> Server : </label>
     <select name="server_name" id="server_name">
<?php

try
{ // On se connecte
   connectMaBase();
}
catch(Exception $e)
{
            die('Erreur : '.$e->getMessage());
}
// On récupère la liste des serveurs 
$reponse = mysql_query('SELECT DISTINCT server_name FROM server');
 
while ($data= mysql_fetch_array($reponse))
{
?> <!--On affiche la liste des serveurs -->
           <option value="<?php echo $data['server_name'];?>"> <?php echo $data['server_name']; ?></option>
<?php
}

?>
</select> </br>
<!--On choisit de modifier ou de supprimet le serveur -->
<input type="submit" name="modifier_serveur" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_serveur" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 <?php
		// Si on a choisi de supprimer
        if (isset ($_POST['supprimer_serveur']))
		{
           // On récupère le nom du serveur
            $server_name=$_POST['server_name'];
			// On se connecte
            connectMaBase();
           // On supprime le serveur de la base de données
            $sql = 'DELETE FROM Server WHERE server_name ="'.$server_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
			// On ferme la connexion
            mysql_close();
        }
		// Si on a choisi de modifier le serveur
       elseif (isset ($_POST['modifier_serveur']))
	   
	   {	     // On récupère le nom du serveur choisi
			 $server_name=$_POST['server_name'];
			    // On récupère l'adress soap associée à ce serveur
			 $req = mysql_query('SELECT server_soapadress FROM server WHERE server_name ="'.$server_name.'"');
			 $row=mysql_fetch_row($req);	
		?>
		</br>
		<!-- On écrit le nom du serveur --> 
 <label style="display:block;width: 150px;float:left "> Serveur choisi : </label><input type="text" readonly name="server_name" value="<?php echo $server_name; ?>"/><br/>
 <!-- L'utilisateur peut alors modifier l'adress Soap --> 
 <label style="display:block;width: 150px;float:left "> Soap_adress : </label><input type="text" name="soap_adress" value="<?php echo $row[0];?>"/><br/>
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
     if (isset ($_POST['valider_server']))
	 {
            //On récupère les valeurs entrées par l'utilisateur :
            $server_name=$_POST['server_name'];
            $server_soapadress=$_POST['soap_adress'];
         
            //On se connecte
			connectMaBase();
 
            //On prépare la commande sql d'update
            $sql = 'UPDATE server SET server_soapadress="'.$server_soapadress.'" WHERE server_name="'.$server_name.'" ';
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
	<h3>Fonction</h3>
	 <form name="function" method="post" action="modification.php">
	 <label for="function_name" style="display:block;width: 150px;float:left"> Fonction : </label>
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
           <option value="<?php echo $data['function_name'];?>"> <?php echo $data['function_name']; ?></option>
<?php
}

?>
</select> </br>
<input type="submit" name="modifier_function" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_function" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 <?php
		
        if (isset ($_POST['supprimer_function']))
		{
            $function_name=$_POST['function_name'];
					
            connectMaBase();

            $sql = 'DELETE FROM Function WHERE function_name ="'.$function_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
             mysql_close();
        }
		
       elseif (isset ($_POST['modifier_function']))
	   
	   {	     
			 $function_name=$_POST['function_name'];
			 $req = mysql_query('SELECT server_name FROM server,function WHERE function.function_name ="'.$function_name.'" AND function.server_id=server.server_id');
			 $row=mysql_fetch_row($req);
		?>
		</br>
<label style="display:block;width: 150px;float:left "> Fonction choisie : </label><input type="text" readonly name="function_name" value="<?php echo $function_name; ?>"/><br/>
 <label style="display:block;width: 150px;float:left "> Server : </label><input type="text" name="server_name" value="<?php echo $row[0];?>"/><br/>
  <input type="submit" name="valider_function" onclick="return confirm('Confirmer?')" value="OK"/>
  </form>
	 
 </br>
  <?php               
     mysql_close();			 
	   }
   ?>
   
	 <?php
	 
     if (isset ($_POST['valider_function']))
	 {
       
            $function_name=$_POST['function_name'];
            $server_name=$_POST['server_name'];
         
    
			connectMaBase();
 
      
         $sql = 'UPDATE function SET function.server_id=(SELECT server_id FROM server WHERE server.server_name="'.$server_name.'") WHERE function.function_name="'.$function_name.'"';
  
			echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br />'.mysql_error());
            
            mysql_close();
        }
        ?>

	</div>
	<div>
	<h3>Variable</h3>
	 <form name="variable" method="post" action="modification.php">
	 <label for="variable_name" style="display:block;width: 150px;float:left"> Variable : </label>
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
           <option value="<?php echo $data['variable_name'];?>"> <?php echo $data['variable_name']; ?></option>
<?php
}

?>
</select> </br>
<input type="submit" name="modifier_variable" onclick="return confirm('Confirmer?')" value="Modifier"/><input type="submit" name="supprimer_variable" onclick="return confirm('Confirmer?')" value="Supprimer"/>
	 <?php
		
        if (isset ($_POST['supprimer_variable']))
		{
            $variable_name=$_POST['variable_name'];
					
            connectMaBase();
            $sql = 'DELETE FROM Variable WHERE variable_name ="'.$variable_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
             mysql_close();
        }
		
       elseif (isset ($_POST['modifier_variable']))
	   
	   {	     
			 $variable_name=$_POST['variable_name'];
			 $req_function= mysql_query('SELECT function_name FROM function, variable WHERE variable.variable_name ="'.$variable_name.'" AND variable.function_id=function.function_id');
			 $row_function=mysql_fetch_row($req_function);
			 $req_type= mysql_query('SELECT type_name FROM type, variable WHERE variable.variable_name ="'.$variable_name.'" AND variable.type_id=type.type_id');
			 $row_type=mysql_fetch_row($req_type);
			 $req_input= mysql_query('SELECT variable_input FROM variable WHERE variable.variable_name ="'.$variable_name.'"');
			 $row_input=mysql_fetch_row($req_input);			 
			 $req_order=mysql_query('SELECT variable_order FROM variable WHERE variable.variable_name ="'.$variable_name.'"');
			 $row_order=mysql_fetch_row($req_order);		 
		?>
		</br>
 <label style="display:block;width: 150px;float:left "> Variable choisie : </label><input type="text" readonly name="variable_name" value="<?php echo $variable_name; ?>"/><br/>
 <label style="display:block;width: 150px;float:left "> Fonction: </label><input type="text" name="function_name" value="<?php echo $row_function[0];?>"/><br/>
  <label style="display:block;width: 150px;float:left "> Type: </label><input type="text" name="type_name" value="<?php echo $row_type[0];?>"/><br/>
  <label style="display:block;width: 150px;float:left "> Order: </label><input type="text" name="type_order" value="<?php echo $row_order[0];?>"/><br/>
  <label style="display:block;width: 150px;float:left"> Input/Output: </label> 
       <input type="radio" name="variable_input" value="1" <?php if ("$row_input[0]" == "1") { echo "checked"; }?>/> <label>Input</label>
       <input type="radio" name="variable_input" value="0" <?php if ("$row_input[0]" == "0") { echo "checked"; }?>/> <label>Output</label><br />
  <input type="submit" name="valider_variable" onclick="return confirm('Confirmer?')" value="OK"/>
  </form>
	 
 </br>
  <?php               
     mysql_close();			 
	   }
   ?>
   
	 <?php
	 
     if (isset ($_POST['valider_variable']))
	 {           
            $variable_name=$_POST['variable_name'];
            $function_name=$_POST['function_name'];
			$type_name=$_POST['type_name'];
			$type_order=$_POST['type_order'];
			$variable_input=$_POST['variable_input'];
			
         
       
			connectMaBase();
 
      
         $sql = 'UPDATE variable SET variable.function_id=(SELECT function_id FROM function WHERE function.function_name="'.$function_name.'"),
                                     variable.type_id=(SELECT type_id FROM type WHERE type.type_name="'.$type_name.'"),
                                     variable.variable_order="'.$type_order.'",
									 variable.variable_input="'.$variable_input.'"
		 WHERE variable.variable_name="'.$variable_name.'"';
  
			echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br />'.mysql_error());
            
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
	 <?php
		
        if (isset ($_POST['supprimer_type']))
		{
           
            $server_name=$_POST['type_name'];
			
            connectMaBase();

            $sql = 'DELETE FROM Type WHERE type_name ="'.$type_name.'"';
            echo "<script>alert(\"Suppression de la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br/>'.mysql_error());
            mysql_close();
        }
		
       elseif (isset ($_POST['modifier_type']))
	   
	   {	     
			 $type_name=$_POST['type_name'];
			 $req = mysql_query('SELECT type_complex FROM type WHERE type_name ="'.$type_name.'"');
			 $row=mysql_fetch_row($req);	
		?>
		</br>
 <label style="display:block;width: 150px;float:left "> Type choisi : </label><input type="text" readonly name="type_name" value="<?php echo $type_name; ?>"/><br/>
<label style="display:block;width: 150px;float:left"> Type Complexe: </label> 
       <input type="radio" name="type_complex" value="1" <?php if ("$row[0]" == "1") { echo "checked"; }?>/> <label>Oui</label>
       <input type="radio" name="type_complex" value="0" <?php if ("$row[0]" == "0") { echo "checked"; }?>/> <label>Non</label><br />
  <input type="submit" name="valider_type" onclick="return confirm('Confirmer?')" value="OK"/>
  </form>
	 
 </br>
  <?php               
     mysql_close();			 
	   }
   ?>
   
	 <?php
	 
     if (isset ($_POST['valider_type']))
	 {
         $type_name=$_POST['type_name'];
         $type_complex=$_POST['type_complex'];
         
       
			connectMaBase();
 
            $sql = 'UPDATE type SET type_complex="'.$type_complex.'" WHERE type_name="'.$type_name.'" ';

			echo "<script>alert(\"Ajout \340 la base de donn\351es\")</script>"; 
            mysql_query ($sql) or die ('Erreur SQL !'.$sql.'<br />'.mysql_error());

            mysql_close();
        }
        ?>

	</div>
</body>
</html>
