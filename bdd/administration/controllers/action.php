<?php

/* --------------------------------------------------
  Options Informatique et MDBIT
  Janvier 2015
  Fichier de script

  Jean-Yves MARTIN
  Centrale NANTES
  -------------------------------------------------- */

$restart = true;
$Serveur = "ldaps://ldaps.nomade.ec-nantes.fr:636";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On récupère le login et le mot de passe
    $login = "";
    $password = "";

    if (isset($_POST["login"])) {
        $login = $_POST["login"];
    }
    if (isset($_POST["password"])) {
        $password = $_POST["password"];
    }

    // Partie de l'excercice avant la connexion LDAP
    /*
      // Vérification
      if (($login == "larpin") && ($password == "sergio")) {
      $restart = false;
      } */

    putenv('LDAPTLS_REQCERT never');
    $Liaison_LDAP = ldap_connect($Serveur);
    if ($Liaison_LDAP) {
        // Serveur accessible
        $LDAP_DN = "uid=$login,ou=people,dc=ec-nantes,dc=fr";
        $LDAPBind_User = ldap_bind($Liaison_LDAP, $LDAP_DN, $password);
        if ($LDAPBind_User) {
            // Connexion possible de l'utilisateur
            $Filtre = "(uid=$login)";
            $Reponse = ldap_search($Liaison_LDAP, "ou=people,dc=ec-nantes,dc=fr", $Filtre);
            $restart = false;
            if (ldap_count_entries($Liaison_LDAP, $Reponse) == 1) {
                $info = ldap_get_entries($Liaison_LDAP, $Reponse);
                $InfosUser = $info[0];
                $mail = $InfosUser["mail"][0];
                
                // L'utilisateur est connecté, on va donc l'indiquer dans les logs dans la base de données
                $conn = new PDO("mysql:host=127.0.0.1;dbname=prweb", "root", "");
                $query = $conn->prepare("SELECT connexion_id FROM connexion WHERE login=?");
                $query->setFetchMode(PDO::FETCH_ASSOC);
                $query->execute(array($login));
                $rows = $query->fetchAll();
                if (count($rows) == 0) {
                    $queryInsert = $conn->prepare("INSERT INTO connexion(login, email) VALUES (?, ?)");
                    $queryInsert->execute(array($login, $mail));
                    $connexionId = $conn->lastInsertId();
                    $queryInsert->closeCursor();
                } else{
                    $connexionId = $rows[0]["connexion_id"];
                }
                $query->closeCursor();
            }
            @ldap_close($Liaison_LDAP);
        }
    }
}

if ($restart) {
    // La page
    header('Content-Type: text/html; charset=utf-8');
    include "index.html";
} else {
    // Partie de l'excercice avant la redirection vers la page base.php
    // Connexion valide
    /* print "<!DOCTYPE html>\n";
      print "<html lang=\"fr-fr\">\n";
      print "<head>\n";
      print "<title>... Success ...</title>\n";
      print "<meta http-equiv=\"content-type\" content=\"text/html;charset=UTF-8\" />\n";
      print "</head>\n";
      print "<body>\n";
      print "<p>It works !!!!</p>\n";
      print "<p>Your login was : $login</p>\n";
      print "<p>Your password was : ... do you really imagine I will write it ?</p>\n";
      print "</body>\n";
      print "</html>\n";*/
    header('Content-Type: text/html; charset=utf-8');
    include "base.php";
}
?>
