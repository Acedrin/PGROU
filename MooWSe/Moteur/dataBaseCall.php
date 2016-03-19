<?php

class dataBaseCall {

    private $bdd; //objet caracterisant la base de données
    private $dataBaseAdress; //contient l'adresse de la base sur le serveur (pour nous pour le moment : localhost)
    private $dataBaseName; //nom de la base de données
    private $encoding; //encodage utilisé pour la base
    private $user; //utilisateur de la base
    private $password; //mot de passe

    //Constructeur de la base, prend en arguments tous les attributs ci dessus sauf bdd

    function dataBaseCall($dataBaseAdress, $dataBaseName, $encoding, $user, $password) {
        $this->dataBaseAdress = $dataBaseAdress;
        $this->dataBaseName = $dataBaseName;
        $this->encoding = $encoding;
        $this->user = $user;
        $this->password = $password;
        $this->bdd = new PDO('mysql:host=' . $dataBaseAdress . ';dbname=' . $dataBaseName . ';charset=' . $encoding . '', $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }

    //Prend en argument les raguments de sécutrité de Security  et renvoit $registered, un booléen qui vaut vrai si l'authentification du client est bonne
    //L'identité est vérifiée en se connectant à la bdd
    function clientRegistered($client_name, $client_nonce, $client_created, $client_access, $client_password_digest, $client_IP) {
        $base = $this->bdd;
        // requete sql
        $recherche_client = $base->query('SELECT client_name,client_ip,client_password,modality_name FROM client INNER JOIN modality ON modality.modality_id=client.modality_id WHERE client_name=\'' . $client_name . '\'');
        //verification de table non vide (sinon le client n'existe pas
        //La reqête SQL renvoit deux lignes avec un id différent pour un même nom de client, s'il y a deux modalités de connexion
        $registered = false;
        //on rentre dans la boucle s'il y a un client qui a ce nom, sinon l'appel est faux
        while ($info_client = $recherche_client->fetch()) {
            //assignation des variables, le préfixe data caractérise les infos récupérerées ne base
            $client_database_name = $info_client['client_name'];
            $client_database_ip = $info_client['client_ip'];
            $client_database_password = $info_client['client_password'];
            $client_database_modality = $info_client['modality_name'];
            //mot de passe encryptre
            $client_database_encrypted_password = base64_encode(sha1($client_nonce . $client_created . $client_database_password));

            //verification des informations en base, la variable ci dessous vaut vraie si les informations 

            $registered = ((($client_access == $client_database_modality) &&
                    ($client_database_encrypted_password == $client_password_digest) &&
                    ($client_IP == $client_database_ip) &&
                    ($client_database_modality == $client_access)) || $registered);
        }

        return $registered;
    }

    //prend en argument le nom du client et renvoit un array contenant les ids des fonctions auxquelles le client peut accèder
    function listFunction($client_name,$service) {
        //appel des fonctions autorisees pour le client
        $base = $this->bdd;
         $function_request = $base->query('SELECT function.function_id FROM function INNER JOIN access ON function.function_id=access.function_id
                    INNER JOIN client ON access.client_id=client.client_id
                    INNER JOIN server ON server.server_id=function.server_id
                    WHERE client_name=\'' . $client_name . '\' AND server_name=\'' . $service . '\'');
        //extraire le premier element, s'il y en a un on extrait les autres et on les met dans un array              
        if ($current_function = $function_request->fetch()) {
            //creation d'un array
            $functions = array($current_function["function_id"]);
            //On garde l'id
            $last_function = $current_function["function_id"];
            //parcours de la table des fonctions autorisees
            while ($current_function = $function_request->fetch()) {
                //ajoute la fonction courante au array
                if ($current_function["function_id"] != $last_function) {
                    array_push($functions, $current_function["function_id"]);
                }
                $last_function = $current_function["function_id"];
            }
        }
        else{
            //on renvoie un array vide
            $functions=array();
        }
        return $functions;
    }

    function affichage($client_name) {
        //appel de la base
        $base = $this->bdd;
        //requete sql
        $function_request = $base->query('SELECT function.function_id,function.function_name,server_name FROM function INNER JOIN access ON function.function_id=access.function_id
                    INNER JOIN client ON access.client_id=client.client_id
                    INNER JOIN server ON server.server_id=function.server_id
                    WHERE client_name=\'' . $client_name . '\' ');

        echo '<h2>Affichage des fonctions auxquelles le client ' . $client_name . ' peut acceder</h2>';
        echo '</br>';
        echo '<u><b>Listing des fonctions :</u></b>';
        echo '</br>';
        $last_function = NULL;
        while ($function_recup = $function_request->fetch()) {
            if ($function_recup != $last_function) {
                echo 'fonction numero: ' . $function_recup["function_id"] . ' ' . $function_recup["function_name"] . ' du serveur ' . $function_recup["server_name"];
                echo '</br>';
                //aller chercher les variables de la fonction
                $variable_request = $base->query('SELECT variable.variable_name,type.type_name,variable.variable_input FROM variable INNER JOIN type ON type.type_id=variable.type_id
                    WHERE variable.function_id=\'' . $function_recup["function_id"] . '\' ');
                while ($variable_recup = $variable_request->fetch()) {
                    $variable_name = $variable_recup["variable_name"];
                    $type_name = $variable_recup["type_name"];
                    $variable_input = $variable_recup["variable_input"];
                    if ($variable_input == 1) {
                        echo'<div style="text-indent: 15px;">Argument(s) :</div>';
                    } else {
                        echo'<div style="text-indent: 15px;">Sortie :</div>';
                    }
                    echo '<div style="text-indent: 30px;">variable ' . $variable_name . ' de type ' . $type_name . '</div>';
                }
                echo '</br>';
                //fin de recherche des variables
            }
            $last_function = $function_recup;
        }
    }

}

?>
