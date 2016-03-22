<?php
/*-- - - - - - - - - - - - - - - - - - - - - - - - - - -
  Projet MOOWSE
  Fichier php
  Vue inclue dans chaque vue pour y incorporer les éléments communs

  Quentin Payet
  Ecole Centrale de Nantes
 - - - - - - - - - - - - - - - - - - - - - - - - - - -->
*/
?>
        <br/><br/><br/>
        <div class="footer"><a href="accueil.php" class="a2">ACCUEIL</a> |
            <a href="gestion_fonctions.php" class="a2">Fonctions</a> |
            <a href="gestion_types.php" class="a2">Types</a> |
            <a href="gestion_clients.php" class="a2"> Clients</a> |
            <a href="gestion_administrateurs.php" class="a2"> Administrateurs</a></div></br>
            <div class="textfooter">MooWse project 2016 - Ecole Centrale de Nantes<br/>Attention: en cas d'inactivité prolongée vous serez déconnectés. </div>
        <div class="deconnexion">
            <form action="../controllers/deconnexion.php" method="POST">
                <p><button type="submit" class="boutondeconnexion"><br/></button></p>
            </form></div>
