<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

$mysqli = new mysqli('localhost', 'root', 'admin', 'new_schema');
if ($mysqli->connect_error) {
    die('Erreur de connexion (' . $mysqli->connect_errno . ') '
            . $mysqli->connect_error);
}
if ($result = mysqli_query($mysqli, "SELECT function_id FROM access,client WHERE client_id=1 AND access_right=1 ")) {
    printf("Select a retourné %d lignes.\n", mysqli_num_rows($result));
}

header('Content-type: text/xml; charset=UTF-8');
$oXMLWriter = new XMLWriter;
$oXMLWriter->openMemory();
$oXMLWriter->startDocument('1.0', 'UTF-8');
$oXMLWriter->setIndent(true);
$oXMLWriter->startElementNS('wsdl', 'definition', 'http://schemas.xmlsoap.org/wsdl/');
$oXMLWriter->writeAttribute('name', 'AGAP');
$oXMLWriter->writeAttributeNs('xmlns', 'soap', NULL, 'http://schemas.xmlsoap.org/wsdl/soap/');
$oXMLWriter->writeAttributeNs('xmlns', 'xsd', NULL, 'http://www.w3.org/2001/XMLSchema');
$oXMLWriter->writeAttributeNs('xmlns', 'soapenc', NULL, 'http://schemas.xmlsoap.org/soap/encoding/');
$oXMLWriter->writeAttribute('xmlns', 'http://schemas.xmlsoap.org/wsdl/');
//$oXMLWriter->endAttribute();
//$oXMLWriter->text('Hello, World!');
if ($result = mysqli_query($mysqli, "SELECT function_name,variable_name,variable_input,type_namewsdl FROM access,client,function,variable,type WHERE client.client_id=access.client_id AND function.function_id=access.function_id AND access.access_right=1 AND client.client_id=1 AND function.function_id=variable.function_id AND variable.type_id=type.type_id AND access.function_id=1 ")) {
    // printf("Select a retourné %d lignes.\n", mysqli_num_rows($result));
    while ($row = $result->fetch_assoc()) {
        $temp1 = $row['function_name'];
        $temp2 = $row['variable_name'];
        $temp3 = $row['variable_input'];
        $temp4 = $row['type_namewsdl'];
        $temp = "";
        if (strcmp($temp3, "1") == 0) {
            $temp = $temp1 . 'Request';
            $type = "input";
        } else {
            $temp = $temp1 . 'Response';
            $type = "output";
        }

        $oXMLWriter->startElementNS('wsdl', 'message', NULL);
        $oXMLWriter->writeAttribute('name', 'get' . $temp);
        $oXMLWriter->startElementNS('wsdl', 'part', NULL);
        $oXMLWriter->writeAttribute('name', $temp2);
        $oXMLWriter->writeAttribute('type', $temp4);
        $oXMLWriter->endElement();
        $oXMLWriter->endElement();
    }
}
if ($result = mysqli_query($mysqli, "SELECT function_name,variable_name,variable_input,type_namewsdl FROM access,client,function,variable,type WHERE client.client_id=access.client_id AND function.function_id=access.function_id AND access.access_right=1 AND client.client_id=1 AND function.function_id=variable.function_id AND variable.type_id=type.type_id AND access.function_id=1 ")) {
    // printf("Select a retourné %d lignes.\n", mysqli_num_rows($result));
    $parcours = 0; // variable qui vaut 1 si on a déjà parcouru la boucle une fois
    while ($row = $result->fetch_assoc()) {
        $temp1 = $row['function_name'];
        $temp2 = $row['variable_name'];
        $temp3 = $row['variable_input'];
        $temp4 = $row['type_namewsdl'];
        $temp = "";
        if (strcmp($temp3, "1") == 0) {
            $temp = $temp1 . 'Request';
            $type = "input";
        } else {
            $temp = $temp1 . 'Response';
            $type = "output";
        }

        if ($parcours == 0) {
            $oXMLWriter->startElementNS('wsdl', 'portType', NULL);
            $oXMLWriter->writeAttribute('name', $temp1 . 'portType');
            $oXMLWriter->startElementNS('wsdl', 'operation', NULL);
            $oXMLWriter->writeAttribute('name', 'get' . $temp1);
        }
        if (strcmp($type, "input") == 0) {
            $oXMLWriter->startElementNS('wsdl', $type, NULL);
            $oXMLWriter->writeAttribute('message', 'get' . $temp);
            $oXMLWriter->endElement();
        }
        if (strcmp($type, "input") != 0) {
            $oXMLWriter->startElementNS('wsdl', $type, NULL);
            $oXMLWriter->writeAttribute('message', 'get' . $temp);
            $oXMLWriter->endElement();
        }
        $parcours = 1;
    }
}
$oXMLWriter->endElement();
$oXMLWriter->endElement();

if ($result = mysqli_query($mysqli, "SELECT function_name,variable_name,variable_input,type_namewsdl FROM access,client,function,variable,type WHERE client.client_id=access.client_id AND function.function_id=access.function_id AND access.access_right=1 AND client.client_id=1 AND function.function_id=variable.function_id AND variable.type_id=type.type_id AND access.function_id=1 ")) {
    // printf("Select a retourné %d lignes.\n", mysqli_num_rows($result));
    $parcours = 0; // variable qui vaut 1 si on a déjà parcouru la boucle une fois
    while ($row = $result->fetch_assoc()) {
        $temp1 = $row['function_name'];
        $temp2 = $row['variable_name'];
        $temp3 = $row['variable_input'];
        $temp4 = $row['type_namewsdl'];
        $temp = "";
        if (strcmp($temp3, "1") == 0) {
            $temp = $temp1 . 'Request';
            $type = "input";
        } else {
            $temp = $temp1 . 'Response';
            $type = "output";
        }

        if ($parcours == 0) {

            $oXMLWriter->startElementNS('wsdl', 'binding', NULL);
            $oXMLWriter->writeAttribute('name', $temp1 . 'binding');
            $oXMLWriter->writeAttribute('type', 'tns:' . $temp1 . 'PortType');
            $oXMLWriter->startElementNS('soap', 'binding', NULL);
            $oXMLWriter->writeAttribute('style', 'rpc');
            $oXMLWriter->writeAttribute('transport', 'http://schemas.xmlsoap.org/soap/http');
            $oXMLWriter->endElement();
            $oXMLWriter->startElementNS('wsdl', 'operation', NULL);
            $oXMLWriter->writeAttribute('name', 'get' . $temp1);
            $oXMLWriter->startElementNS('soap', 'operation', NULL);
            $oXMLWriter->writeAttribute('soapAction', 'urn:xmethods-delayed-quotes#get' . $temp1);
            $oXMLWriter->endElement();
        }
        $parcours = 1;
        if (strcmp($type, "input") == 0) {
            $oXMLWriter->startElementNS('wsdl', $type, NULL);
            $oXMLWriter->startElementNS('soap', 'body', NULL);
            $oXMLWriter->writeAttribute('use', 'encoded');
            $oXMLWriter->writeAttribute('namespace', 'urn:xmethods-delayed-quotes');
            $oXMLWriter->writeAttribute('encodingStyle', 'http://schemas.xmlsoap.org/soap/encoding/');
            $oXMLWriter->endElement();
            $oXMLWriter->endElement();
        }

        if (strcmp($type, "input") != 0) {
            $oXMLWriter->startElementNS('wsdl', $type, NULL);
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

if ($result = mysqli_query($mysqli, "SELECT function_name,variable_name,variable_input,type_namewsdl,server_name FROM access,client,function,variable,type,server WHERE client.client_id=access.client_id AND function.function_id=access.function_id AND access.access_right=1 AND function.server_id=server.server_id AND client.client_id=1 AND function.function_id=variable.function_id AND variable.type_id=type.type_id AND access.function_id=1 ")) {
    // printf("Select a retourné %d lignes.\n", mysqli_num_rows($result));
    $parcours = 0; // variable qui vaut 1 si on a déjà parcouru la boucle une fois
    while ($row = $result->fetch_assoc()) {
        $temp1 = $row['function_name'];
        $temp2 = $row['variable_name'];
        $temp3 = $row['variable_input'];
        $temp4 = $row['type_namewsdl'];
        $temp5 = $row['server_name'];
        $temp = "";
        if (strcmp($temp3, "1") == 0) {
            $temp = $temp1 . 'Request';
            $type = "input";
        } else {
            $temp = $temp1 . 'Response';
            $type = "output";
        }

        if ($parcours == 0) {

            $oXMLWriter->startElementNS('wsdl', 'service', NULL);
            $oXMLWriter->writeAttribute('name', $temp1 . 'Service');
            $oXMLWriter->startElementNS('wsdl', 'port', NULL);
            $oXMLWriter->writeAttribute('name', $temp1 . 'Port');
            $oXMLWriter->writeAttribute('binding', $temp1 . 'binding');
            $oXMLWriter->startElementNS('soap', 'adress', NULL);
            $oXMLWriter->writeAttribute('location', 'http://localhost/'.$temp5.'/wsdl_server.php');
            $oXMLWriter->endElement();
            $oXMLWriter->endElement();
            $oXMLWriter->endElement();
        }
        $parcours = 1;
    }
}


$oXMLWriter->endElement();

$oXMLWriter->endDocument();
echo $oXMLWriter->outputMemory(TRUE);
$mysqli->close();



