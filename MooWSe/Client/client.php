<?php

//param�tre d'intitialisation de la mise en cahce du WSDL, son time to live est de 1s (permet d'�viter la mise en cache du WSDL
ini_set("soap.wsdl_cache_ttl", 1);
//appel des param�tres initiaux d'erreurs
ini_set("display_startup_errors", true);
ini_set("display_errors", true);
ini_set("html_errors", true);
//extension Zend (inutilise)
//ini_set("zend_extension","C:\xampp\php\ext\php_xdebug.dll"); // inutile, à activer directement dans php.ini, avant de redémarrer XAMPP
//chargement des erreurs de logs
ini_set("log_errors", false);
//reporter toutes les erreurs
error_reporting(E_ALL);
//chargment de l'URL du dossier contenant le WSDL
$MooWSe_URL = "http://localhost/github/PGROU/MooWSe";
//nom du fichier contenant le code WSDL de MooWSe qui est le serveur du web service entre ce client et lui
$MooWSe_WSDL_file = "MooWSe.wsdl";
//ajout du contenu du fichier WSDL, il est extrait de l'URL indiqu� avec le nom de fichier associ� au premier argument
//le fichier WSDL est indispensable au bon fonctionnement de la classe 
file_put_contents($MooWSe_WSDL_file, file_get_contents($MooWSe_URL));

//Ici le client entre ses mots de passe en brut (ce fichier n'est pas accessible par un pirate, seules les informations transmises le sont)
//informations relatives � AGAP qui veut se connecter � Moodle par batch
//concat�nation de ces informations
$username1 = "agap" . "," . "batch";
$password1 = "kangourou87"; //mot de passe de agap
$hash1 = sha1($password1); //Il faut maintenant hacher une premiere fois le mot de passe (on utilisera simplement sha1 pour le moment)
$service1 = "moodle"; //service appell� 
$service1_WSDL_file = "moodle.wsdl"; //nom du fichier WSDL du service en question si authentification
$service1_failed_WSDL_file = "moodle_error.wsdl"; //nom du fichier renvoy� si erreur (ici token non valide) voir plus loin
//informations relatives � moodle qui veut se connecter � AGAP par batch
//m�mes informations, mais dans le cas d'une connexion � AGAP
$username2 = "moodle" . "," . "batch";
$password2 = "mouton44";
$hash2 = sha1($password2);
$service2 = "agap";
$service2_WSDL_file = "agap.wsdl";

//options affect�es au soap header
$options = array(
    //"location" => NULL,
    //"uri" => NULL,
    //"style" => NULL,
    //"use" => NULL,
    //"soap_version" => SOAP_1_1,
    //"login" => $login,
    //"password" => $password,
    //"proxy_host" => NULL,
    //"proxy_port" => NULL,
    //"proxy_login" => NULL,
    //"proxy_password" => NULL,
    //"local_cert" => NULL,
    //"passphrase" => NULL,
    //"authentication" =>  SOAP_AUTHENTICATION_DIGEST,
    //"compression" => NULL,
    //"encoding" => NULL,
    "trace" => true,
    //"classmap" => array("wsdl_type" => "php_class"),
    "exceptions" => true,
        //"connection_timeout"=>5,
        //"typemap" => array(
        //	"type_name" => NULL,
        //	"type_ns" => NULL,
        //	"from_xml" => NULL,
        //	"to_xml" => NULL
        //),
        //"cache_wsdl" => NULL,
        //"user_agent" => NULL,
        //"stream_context" => NULL,
        //"features" => NULL,
        //"keep_alive" => NULL,
        //"ssl_method" => NULL,
);

//Chargement du WSDL de MooWse
$MooWSe_WSDL = file_get_contents($MooWSe_WSDL_file);

//appel du soap client MooWse avec son fichier et les options, recuperation de l'erreur
try {
    $MooWSe_client = new SoapClient($MooWSe_WSDL_file, $options);
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}
//normes d'envoi des headers
$wsse = "http://schemas.xmlsoap.org/ws/2002/07/secext";
$wsu = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd";
$mustUnderstand = true; // oblige le récepteur à traiter l'header
$actor = "http://schemas.xmlsoap.org/soap/actor/next"; // le destinataire est le premier récepteur
//$actor = "http://localhost/github/PGROU/testsCedric/testWStoken/server.php"; // le destinataire est ce récepteur (ne marche pas)
//$actor = ""; // le destinataire est le dernier récepteur (marche mais lance un warning)
//=======================================================================================================================================================
//Premiere demande
// AGAP -> Moodle
//=======================================================================================================================================================
$Username = $username1; //on utilise le premier username (celui d'AGAP) comme Username 
$crypto_strong = false;
while (!$crypto_strong) { //generation d'un sel (aleatoire pour le moment), le sel va permettre de brouiller le mot de passe aux yeux d'un HDM (Homme Du Milieu)
    $Nonce = base64_encode(bin2hex(openssl_random_pseudo_bytes(16, $crypto_strong))); //encode 64 bit d'un mot de passe al�atoire de 16 bits converti du binaire � l'hexad�cimal
    $Created = time(); // temps actuel
}
$Password = base64_encode(sha1($Nonce . $Created . $hash1)); //on envoie le mot de passe crypte, sel = chaine al�atoire concatenee avec le hash sha1 du mot de passe natif
//le tout est de nouveau hashe et encod� dans une base 64
//Utilisation d'un array contenant les infos utilisateurs. On envoie le mot de passe encrypt� plus les sels qui vont permettre � MooWSe d'authentifier le client sans mot de passe en clair
$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);
$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordDigest", $wsse, "Password", $wsse);
$UsernameToken["Nonce"] = new SoapVar($Nonce, XSD_STRING, "Base64Binary", $wsse, "Nonce", $wsse); //il faut aussi envoyer le sel
$UsernameToken["Created"] = new SoapVar($Created, XSD_STRING, NULL, $wsu, "Created", $wsu); //on envoie la date
//le array Security va contenir UsernameToken 
$Security = array();
$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, "UsernameToken", $wsse);
$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, "Security", $wsse);
//creation du header avec envoi de $security sur la fonction security
$Header = array();
$Header[] = new SoapHeader($wsse, "Security", $Security, $mustUnderstand, $actor);
//envoi du header, authentification automatique via la fonction security
$MooWSe_client->__setSoapHeaders(NULL);
$MooWSe_client->__setSoapHeaders($Header);
//affichage du header
//print_r_pre($Header);
//r�cup�ration d'un token par la fonction authenticate, sinon on imprime le dernier dialogue reseau et l'erreur
try {
    $token1 = $MooWSe_client->authenticate(); //obtention d'un token (chaine aleatoire) si authentification r�ussie avec les donnees envoyees dans le header sinon msg d'erreur
} catch (SoapFault $fault) {
    echo getLastHTTPDialogue($MooWSe_client); // affiche requete et reponse avec le nom du client et le header SOAP
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}
//appel du dernier dialogue r�seau
echo getLastHTTPDialogue($MooWSe_client); // affiche requete et reponse avec le nom du client et le header SOAP
//on imprime le token
echo "<pre>" . htmlspecialchars($token1) . "</pre>";

//=======================================================================================================================================================
//Deuxieme demande
// Moodle -> AGAP
//=======================================================================================================================================================

$Username = $username2; // le fonctionnement est tr�s semblable
$crypto_strong = false;
while (!$crypto_strong) {
    $Nonce = base64_encode(bin2hex(openssl_random_pseudo_bytes(16, $crypto_strong)));
    $Created = time();
}
$Password = base64_encode(sha1($Nonce . $Created . $hash2));

$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);
$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordDigest", $wsse, "Password", $wsse);
$UsernameToken["Nonce"] = new SoapVar($Nonce, XSD_STRING, "Base64Binary", $wsse, "Nonce", $wsse);
$UsernameToken["Created"] = new SoapVar($Created, XSD_STRING, NULL, $wsu, "Created", $wsu);

$Security = array();
$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, "UsernameToken", $wsse);
$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, "Security", $wsse);
$Header = array();
$Header[] = new SoapHeader($wsse, "Security", $Security, $mustUnderstand, $actor);
$MooWSe_client->__setSoapHeaders(NULL);
$MooWSe_client->__setSoapHeaders($Header);

//print_r_pre($Header);

try {
    $token2 = $MooWSe_client->authenticate();
} catch (SoapFault $fault) {
    echo getLastHTTPDialogue($MooWSe_client);
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($token2) . "</pre>";

//=======================================================================================================================================================
//Premiere demande (apr�s r�cup du token)
// AGAP -> Moodle
//=======================================================================================================================================================

$Username = $username1; //on repasse dans le cas AGAP -->Moodle on a d�j� un token mais on r�intialise les param�tres
$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);

$Security = [];
$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, "UsernameToken", $wsse);
//ajout du deuxieme champ de security
$Security["BinarySecurityToken"] = new SoapVar($token1, XSD_STRING, "Base64Binary", $wsse, "BinarySecurityToken", $wsse); //ce champ va permettre d'envoyer le token par soap header � la focntion Security
$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, "Security", $wsse); //creation d'un objet soap pour envoi
$Header = [];
$Header[] = new SoapHeader($wsse, "Security", $Security, $mustUnderstand, $actor); //envoi d'un nouvel header � deux champs arrays maintenant
$MooWSe_client->__setSoapHeaders(NULL); //passage � NULL avant envoi pour nettoyer
$MooWSe_client->__setSoapHeaders($Header); //va appeler la deuxi�me partie de security qui 
//Appel de la fonction GetWSDL qui check le token et renvoie un fichier WSDL s'il est bon
try {
    $service1_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service1, "service")), ENT_XML1); //recuperation de lachaine de caract�re associee au WSDL demande
} catch (SoapFault $fault) {
    echo getLastHTTPDialogue($MooWSe_client);
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}
//impression du dernier dialogue r�seau
echo getLastHTTPDialogue($MooWSe_client); //affichage reponse et requete, si le WSDL est renvoye on n'affiche pas la reponse hors header
echo "<pre>" . htmlspecialchars($service1_WSDL) . "</pre>"; //affichagedu WSDL renvoye
//on envoie le contenu de $service1_WSDL qui est un string dans le fichier dont le nom est $service1_WSDL_file initialise plus haut
file_put_contents($service1_WSDL_file, $service1_WSDL); //placer le code renvoyer dans un fichier avec le nom specifie precedement

//=======================================================================================================================================================
//Deuxieme demande (apr�s recuperation de Token), premier appel de WSDL
// Moodle -> AGAP
//=======================================================================================================================================================
$Username = $username2; // meme fonctionnement
$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);

$Security = [];
$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, "UsernameToken", $wsse);
$Security["BinarySecurityToken"] = new SoapVar($token2, XSD_STRING, "Base64Binary", $wsse, "BinarySecurityToken", $wsse);
$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, "Security", $wsse);
$Header = [];
$Header[] = new SoapHeader($wsse, "Security", $Security, $mustUnderstand, $actor);
$MooWSe_client->__setSoapHeaders(NULL);
$MooWSe_client->__setSoapHeaders($Header);

try {
    $service2_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service2, "service")), ENT_XML1);
} catch (SoapFault $fault) {
    echo getLastHTTPDialogue($MooWSe_client);
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($service2_WSDL) . "</pre>";
file_put_contents($service2_WSDL_file, $service2_WSDL);

//attente de 2 secondes, qui invlaide les token
//sleep(2);
$date=time();
while(time()<($date+2)){
    
}
//=======================================================================================================================================================
//Deuxieme demande (apr�s recuperation de Token), deuxieme appel de WSDL, TOKEN NON VALABLE !
// Moodle -> AGAP
//=======================================================================================================================================================
// a ce stade le client a un token qui n'est plus valide et redemande un WSDL
try {
    $service2_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service1, "service")), ENT_XML1); //demande le WSDL avec f=token non valide. Ceci renvoie le WSDL de hello
} catch (SoapFault $fault) {
    echo getLastHTTPDialogue($MooWSe_client);
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client); //affichage de la requete et de la reponse
echo "<pre>" . htmlspecialchars($service2_WSDL) . "</pre>"; // affichage WSDL
file_put_contents($service1_failed_WSDL_file, $service2_WSDL); //creation du fichier avec le nom associe

//=======================================================================================================================================================
// Fonctions
//=======================================================================================================================================================
//fonction permettant d'obtenir une architecture XML 
function format($xml) {
    $dom = new DomDocument;
    $dom->loadXML($xml);
    $dom->formatOutput = true;
    return $dom->saveXML();
}

//fonction qui affiche les echanges entre les services, en partant dernier message �mis par le client en parametre. C'est le nom du client qui est en parametre
function getLastHTTPDialogue($client) {

    $string = "";
    $string .= "<pre>\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= "* request from client: ".$client->getClientName()."\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= htmlentities($client->__getLastRequestHeaders()) . "\n"; //renvoie les caracteristiques headers
    $string .= htmlentities(format($client->__getLastRequest())) . "\n"; //renvoit le contenu SOAP de la derniere requete
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= "* response to client: ".$client->getClientName()."\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= htmlentities($client->__getLastResponseHeaders()) . "\n"; // header de la reponse
    //si la reponse renvoit une chaine trop grande (un WSDL) on prefera l'afficher manuellement
    if (strlen(htmlentities(format($client->__getLastResponse()))) < 1000) {
        $string .= htmlentities(format($client->__getLastResponse())) . "\n";
    }
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= "</pre>";
    return $string;
}

//fonction d'afichage d'un array
function print_r_pre($thing) {
    echo "<pre>";
    print_r($thing);
    echo "</pre>";
}

?>