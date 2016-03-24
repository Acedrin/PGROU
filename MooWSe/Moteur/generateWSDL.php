<?php

/*
 * Classe qui permet de créer un objet pour stocker les résultat des requetes sql
 */

class resultat {

    public $function_name;
    public $variable_name;
    public $variable_input;
    public $type_namesdl;
    public $server_name;
	public $server_soapadress;

    /*
     * Constructeur par défaut sans paramètre
     */

    function __construct() {
        $this->function_name = "";
        $this->variable_name = "";
        $this->variable_input = "";
        $this->type_namesdl = "";
        $this->server_name = "";
        $this->server_soapadress = "";
    }

    /*
     * Permet de rentrer les resultats de la requete dans les attributs
     */

    function population(Array $row) {
        $this->function_name = $row[0];
        $this->variable_name = $row[1];
        $this->variable_input = $row[2];
        $this->type_namewsdl = $row[3];
        $this->server_name = $row[4];
        $this->server_soapadress = $row[5];		
    }

}

//function results($array){
//    
//    return $a;
//}

function generateWSDL($array) {


    if (is_array($array)) {
        $a = new ArrayObject(); // Création d'un tableau d'objet
        foreach ($array as $r) {
            $bdd = new PDO('mysql:host=localhost;dbname=webservices;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            $result = $bdd->query("SELECT DISTINCT function_name,variable_name,variable_input,type_namewsdl,server_name,server_soapadress "
                    . "FROM access,client,function,variable,type,server "
                    . "WHERE client.client_id=access.client_id "
                    . "AND function.function_id=access.function_id "
                    . "AND function.server_id=server.server_id "
                    . "AND function.function_id=$r "
                    . "AND function.function_id=variable.function_id "
                    . "AND variable.type_id=type.type_id ");
            while ($row = $result->fetch()) {
                //On définit une nouvelle instance de la classe resultat
                $ligne = new resultat($row);
                // On fait un appel à la methode "population" qui stocke la ligne de resultat de la requête
                $ligne->population($row);
                // On stocke l'objet dans un tableau d'objet.
                $a->append($ligne);
            }
            $bdd = null;
        }
    }
    /*
     *  definition des variables utiles pour écrire le WSDL  
     */
    $tempRequest = "";  // variable utile pour la création du fichier WSDL
    $tempResponse = "";  // variable utile pour la création du fichier WSDL
    $tempServer = ""; // On va stocker plus tard le nom de serveur dans cette variable
    $tempFunction = "";
    $parcours = 0;

    /*
     * on commence l'écriture du WSDL au format XML
     */
    header('Content-type: text/xml; charset=UTF-8');
    $oXMLWriter = new XMLWriter;
    $oXMLWriter->openMemory();
    $oXMLWriter->startDocument('1.0', 'UTF-8');
    $oXMLWriter->setIndent(true);

    foreach ($a as $q) {

        if (strcmp($q->server_name, $tempServer) != 0) {

            /*
             * écriture de la balise d'ouverture "definition"
             */
            $oXMLWriter->startElementNS('wsdl', 'definitions', 'http://schemas.xmlsoap.org/wsdl/');
            $oXMLWriter->writeAttribute('name', $q->server_name);
            $oXMLWriter->writeAttributeNs('xmlns', 'soap', NULL, 'http://schemas.xmlsoap.org/wsdl/soap/');
            $oXMLWriter->writeAttributeNs('xmlns', 'xsd', NULL, 'http://www.w3.org/2001/XMLSchema');
            $oXMLWriter->writeAttributeNs('xmlns', 'soapenc', NULL, 'http://schemas.xmlsoap.org/soap/encoding/');
            $oXMLWriter->writeAttribute('xmlns', 'http://schemas.xmlsoap.org/wsdl/');
			$oXMLWriter->endElement();

            //on utilise une boucle foreach pour parcourir l'ensemble des résultats
            foreach ($a as $r) {
                //ecriture de la premiere balise request
                // Dans la boucle if, si lors d'une nouvelle itération la fonction est toujours la meme on ne rentre pas dedans.
                if (($r->variable_input == 1) && (strcmp($r->function_name, $tempRequest)) != 0) {
                    $oXMLWriter->startElementNS('wsdl', 'message', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name . 'Request');
                    foreach ($a as $s) {
                        if (strcmp($s->function_name, $r->function_name) == 0 && ($s->variable_input == 1)) {
                            $oXMLWriter->startElementNS('wsdl', 'part', NULL);
                            $oXMLWriter->writeAttribute('name', $s->variable_name);
                            $oXMLWriter->writeAttribute('type', 'xsd:' . $s->type_namewsdl);
                            $oXMLWriter->endElement();
                        }
                    }
                    $oXMLWriter->endElement();
                }
                $tempRequest = $r->function_name;
                if (($r->variable_input == 0)) { //&& (strcmp($r->function_name,$tempResponse))!=0){
                    $oXMLWriter->startElementNS('wsdl', 'message', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name . 'Response');
                    foreach ($a as $s) {
                        if (strcmp($s->function_name, $r->function_name) == 0 && ($s->variable_input == 0)) {
                            $oXMLWriter->startElementNS('wsdl', 'part', NULL);
                            $oXMLWriter->writeAttribute('name', $s->variable_name);
                            $oXMLWriter->writeAttribute('type', 'xsd:' . $s->type_namewsdl);
                            $oXMLWriter->endElement();
                        }
                    }

                    $oXMLWriter->endElement();
                }
                $tempResponse = $r->function_name;
            }

            foreach ($a as $r) {
                if (strcmp($r->function_name, $tempFunction) != 0) {
                    $oXMLWriter->startElementNS('wsdl', 'portType', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name . 'PortType');
                    $oXMLWriter->startElementNS('wsdl', 'operation', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name);
                    foreach ($a as $s) {
                        if (strcmp($s->function_name, $r->function_name) == 0 && ($s->variable_input == 1) && $parcours == 0) {
                            $oXMLWriter->startElementNS('wsdl', 'input', NULL);
                            $oXMLWriter->writeAttribute('message', 'tns:' . $r->function_name . 'Request');
                            $oXMLWriter->endElement();
                            $parcours = 1;
                        }
                        if (strcmp($s->function_name, $r->function_name) == 0 && ($s->variable_input == 0)) {
                            $oXMLWriter->startElementNS('wsdl', 'output', NULL);
                            $oXMLWriter->writeAttribute('message', 'tns:' . $r->function_name . 'Response');
                            $oXMLWriter->endElement();
                        }
                    }
                    $oXMLWriter->endElement();
                    $oXMLWriter->endElement();
                }
                $tempFunction = $r->function_name;
                $parcours = 0;
            }

            $tempFunction = "";
            foreach ($a as $r) {
                if (strcmp($r->function_name, $tempFunction) != 0) {
                    $oXMLWriter->startElementNS('wsdl', 'binding', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name . 'Binding');
                    $oXMLWriter->writeAttribute('type', 'tns:' . $r->function_name . 'PortType');
                    $oXMLWriter->startElementNS('soap', 'binding', NULL);
                    $oXMLWriter->writeAttribute('style', 'rpc');
                    $oXMLWriter->writeAttribute('transport', 'http://schemas.xmlsoap.org/soap/http');
                    $oXMLWriter->endElement();
                    $oXMLWriter->startElementNS('wsdl', 'operation', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name);
                    $oXMLWriter->startElementNS('soap', 'operation', NULL);
                    $oXMLWriter->writeAttribute('soapAction', 'urn:xmethods-delayed-quotes#' . $r->function_name);
                    $oXMLWriter->endElement();
                    foreach ($a as $s) {
                        if (strcmp($s->function_name, $r->function_name) == 0 && ($s->variable_input == 1) && $parcours == 0) {
                            $oXMLWriter->startElementNS('wsdl', 'input', NULL);
                            $oXMLWriter->startElementNS('soap', 'body', NULL);
                            $oXMLWriter->writeAttribute('use', 'encoded');
                            $oXMLWriter->writeAttribute('namespace', 'urn:xmethods-delayed-quotes');
                            $oXMLWriter->writeAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');
                            $oXMLWriter->endElement();
                            $oXMLWriter->endElement();
                            $parcours = 1;
                        }
                        if (strcmp($s->function_name, $r->function_name) == 0 && ($s->variable_input == 0)) {
                            $oXMLWriter->startElementNS('wsdl', 'output', NULL);
                            $oXMLWriter->startElementNS('soap', 'body', NULL);
                            $oXMLWriter->writeAttribute('use', 'encoded');
                            $oXMLWriter->writeAttribute('namespace', 'urn:xmethods-delayed-quotes');
                            $oXMLWriter->writeAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');
                            $oXMLWriter->endElement();
                            $oXMLWriter->endElement();
                        }
                    }
                    $oXMLWriter->endElement();
                    $oXMLWriter->endElement();
                }
                $tempFunction = $r->function_name;
                $parcours = 0;
            }
            $tempFunction = "";
            foreach ($a as $r) {
                if (strcmp($r->function_name, $tempFunction) != 0) {
                    $oXMLWriter->startElementNS('wsdl', 'service', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name . 'Service');
                    $oXMLWriter->startElementNS('wsdl', 'port', NULL);
                    $oXMLWriter->writeAttribute('name', $r->function_name . 'Port');
                    $oXMLWriter->writeAttribute('binding', $r->function_name . 'Binding');
                    $oXMLWriter->startElementNS('soap', 'address', NULL);
                    $oXMLWriter->writeAttribute('location', $q->server_soapadress);
                    $oXMLWriter->endElement();
                    $oXMLWriter->endElement();
                    $oXMLWriter->endElement();
                }
                $tempFunction = $r->function_name;
            }
            $oXMLWriter->endElement();
        }
        $tempServer = $q->server_name;
    }
    $oXMLWriter->endDocument();
    return $oXMLWriter->outputMemory(TRUE);
}

function generateFakeWSDL() {

    /*
     * on commence l'écriture du WSDL au format XML
     */
    header('Content-type: text/xml; charset=UTF-8');
    $oXMLWriter = new XMLWriter;
    $oXMLWriter->openMemory();
    $oXMLWriter->startDocument('1.0', 'UTF-8');
    $oXMLWriter->setIndent(true);

    /*
     * écriture de la balise d'ouverture "definition"
     */
    $oXMLWriter->startElementNS('wsdl', 'definitions', 'http://schemas.xmlsoap.org/wsdl/');
    $oXMLWriter->writeAttribute('name', 'agap');
    $oXMLWriter->writeAttributeNs('xmlns', 'soap', NULL, 'http://schemas.xmlsoap.org/wsdl/soap/');
    $oXMLWriter->writeAttributeNs('xmlns', 'xsd', NULL, 'http://www.w3.org/2001/XMLSchema');
    $oXMLWriter->writeAttributeNs('xmlns', 'soapenc', NULL, 'http://schemas.xmlsoap.org/soap/encoding/');
    $oXMLWriter->writeAttribute('xmlns', 'http://schemas.xmlsoap.org/wsdl/');

    //balise message request
    $oXMLWriter->startElementNS('wsdl', 'message', NULL);
    $oXMLWriter->writeAttribute('name', 'helloRequest');
    //d�fintion des variables d'entree
    $oXMLWriter->startElementNS('wsdl', 'part', NULL);
    $oXMLWriter->writeAttribute('name', 'name');
    $oXMLWriter->writeAttribute('type', 'xsd:string');
    $oXMLWriter->endElement();
    //fin description des variables d'entree
    $oXMLWriter->endElement();

    //balise message response
    $oXMLWriter->startElementNS('wsdl', 'message', NULL);
    $oXMLWriter->writeAttribute('name', 'helloResponse');
    //d�fintion des variables d'entree
    $oXMLWriter->startElementNS('wsdl', 'part', NULL);
    $oXMLWriter->writeAttribute('name', 'name');
    $oXMLWriter->writeAttribute('type', 'xsd:string');
    $oXMLWriter->endElement();
    //fin description des variables de sortie 
    $oXMLWriter->endElement();

    //Port Type
    $oXMLWriter->startElementNS('wsdl', 'portType', NULL);
    $oXMLWriter->writeAttribute('name', 'helloPortType');
    $oXMLWriter->startElementNS('wsdl', 'operation', NULL);
    $oXMLWriter->writeAttribute('name', 'hello');
    //input
    $oXMLWriter->startElementNS('wsdl', 'input', NULL);
    $oXMLWriter->writeAttribute('message', 'tns:helloRequest');
    $oXMLWriter->endElement();
    //output
    $oXMLWriter->startElementNS('wsdl', 'output', NULL);
    $oXMLWriter->writeAttribute('message', 'tns:helloResponse');
    $oXMLWriter->endElement();
    //fin operation
    $oXMLWriter->endElement();
    //fin port type
    $oXMLWriter->endElement();

    //binding
    $oXMLWriter->startElementNS('wsdl', 'binding', NULL);
    $oXMLWriter->writeAttribute('name', 'helloBinding');
    $oXMLWriter->writeAttribute('type', 'tns:helloPortType');
    $oXMLWriter->startElementNS('soap', 'binding', NULL);
    $oXMLWriter->writeAttribute('style', 'rpc');
    $oXMLWriter->writeAttribute('transport', 'http://schemas.xmlsoap.org/soap/http');
    $oXMLWriter->endElement();
    $oXMLWriter->startElementNS('wsdl', 'operation', NULL);
    $oXMLWriter->writeAttribute('name', 'hello');
    $oXMLWriter->startElementNS('soap', 'operation', NULL);
    $oXMLWriter->writeAttribute('soapAction', 'urn:xmethods-delayed-quotes#hello');
    $oXMLWriter->endElement();
    //input
    $oXMLWriter->startElementNS('wsdl', 'input', NULL);
    $oXMLWriter->startElementNS('soap', 'body', NULL);
    $oXMLWriter->writeAttribute('use', 'encoded');
    $oXMLWriter->writeAttribute('namespace', 'urn:xmethods-delayed-quotes');
    $oXMLWriter->writeAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');
    $oXMLWriter->endElement();
    $oXMLWriter->endElement();
    //output
    $oXMLWriter->startElementNS('wsdl', 'output', NULL);
    $oXMLWriter->startElementNS('soap', 'body', NULL);
    $oXMLWriter->writeAttribute('use', 'encoded');
    $oXMLWriter->writeAttribute('namespace', 'urn:xmethods-delayed-quotes');
    $oXMLWriter->writeAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');
    $oXMLWriter->endElement();
    $oXMLWriter->endElement();
    //fin operation
    $oXMLWriter->endElement();
    //fin binding
    $oXMLWriter->endElement();

    //service
    $oXMLWriter->startElementNS('wsdl', 'service', NULL);
    $oXMLWriter->writeAttribute('name', 'helloService');
    $oXMLWriter->startElementNS('wsdl', 'port', NULL);
    $oXMLWriter->writeAttribute('name', 'helloPort');
    $oXMLWriter->writeAttribute('binding', 'helloBinding');
    $oXMLWriter->startElementNS('soap', 'address', NULL);
    $oXMLWriter->writeAttribute('location', 'http://localhost/testWS/server.php');
    //fin soap
    $oXMLWriter->endElement();
    //fin port
    $oXMLWriter->endElement();
    //fin service
    $oXMLWriter->endElement();

    $oXMLWriter->endElement();

    $oXMLWriter->endDocument();
    return $oXMLWriter->outputMemory(TRUE);
}
