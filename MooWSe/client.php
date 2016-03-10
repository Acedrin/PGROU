<?php

	ini_set("soap.wsdl_cache_ttl", 1);

	ini_set("display_startup_errors",true);
	ini_set("display_errors",true);
	ini_set("html_errors",true);
	//ini_set("zend_extension","C:\xampp\php\ext\php_xdebug.dll"); // inutile, à activer directement dans php.ini, avant de redémarrer XAMPP
	ini_set("log_errors",false);
	error_reporting(E_ALL);

//echo bin2hex(openssl_random_pseudo_bytes(8,true));
//Ici le client entre ses mots de passe en brut (ce fichier n'est pas accessible par un pirate, seules les informations transmises le sont)

$username1 = "client1" . "," . "modalite1";
$password1 = "password";

$username2 = "client2" . "," . "modalite2";
$password2 = "password";

//Il faut maintenant hacher une premi�re fois le mot de passe (on utilisera simplement sha1 pour le moment)

//$hashedPassword=sha1($password);

//g�n�ration d'un sel (al�atoire pour le moment, il est possible d'ajouter la date), le sel va permettre de brouiller le mot de passe aux yeux d'un HDM

//$salt=password_hash();

//cryatge du mot de passe avec la fonction crypt (incassable, mais r�cup�rable)

//$encryptedPassword=crypt($hashedPassword,$salt); //mot de passe crypt�

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

try {
    $MooWSe_WSDL = "http://localhost/github/PGROU/MooWSe/MooWSe.wsdl";
    $MooWSe_client = new SoapClient($MooWSe_WSDL, $options);
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

$wsse = "http://schemas.xmlsoap.org/ws/2002/07/secext";
$wsu = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd";
//$wsutpUsernameToken = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0 #UsernameToken";
//$wsutpPasswordText = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText";
//$wsutpPasswordDigest = "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordDigest";
$mustUnderstand = true; // oblige le récepteur à traiter l'header
$actor = "http://schemas.xmlsoap.org/soap/actor/next"; // le destinataire est le premier récepteur
//$actor = "http://localhost/github/PGROU/testsCedric/testWStoken/server.php"; // le destinataire est ce récepteur (ne marche pas)
//$actor = ""; // le destinataire est le dernier récepteur (marche mais lance un warning)

$Username = $username1;
//$Password = $encryptedPassword; //on envoie le mot de passe crypt�
//$Salt=$salt; //ajout du sel
$crypto_strong = false;
while (!$crypto_strong) {
	$Nonce = base64_encode(bin2hex(openssl_random_pseudo_bytes(16,$crypto_strong)));
	$Created = time();
}
$Password = base64_encode(sha1($Nonce.$Created.sha1($password1)));

$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);
//$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordText", $wsse, NULL, $wsse);
//$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordText", $wsse, NULL, $wsse);
$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordDigest", $wsse, "Password", $wsse);
//il faut aussi envoyer le sel
//$UsernameToken["Salt"] = new SoapVar($Salt, XSD_STRING, "SaltText", $wsse, NULL, $wsse);
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
    $token1 = $MooWSe_client->authenticate();
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($token1) . "</pre>";


$Username = $username2;
//$Password = $encryptedPassword; //on envoie le mot de passe crypt�
//$Salt=$salt; //ajout du sel
$crypto_strong = false;
while (!$crypto_strong) {
	$Nonce = base64_encode(bin2hex(openssl_random_pseudo_bytes(16,$crypto_strong)));
	$Created = time();
}
$Password = base64_encode(sha1($Nonce.$Created.sha1($password2)));

$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);
//$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordText", $wsse, NULL, $wsse);
//$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordText", $wsse, NULL, $wsse);
$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordDigest", $wsse, "Password", $wsse);
//il faut aussi envoyer le sel
//$UsernameToken["Salt"] = new SoapVar($Salt, XSD_STRING, "SaltText", $wsse, NULL, $wsse);
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
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($token2) . "</pre>";


$Username = $username1;

$UsernameToken = [];
$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, "Username", $wsse);

$Security = [];
$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, "UsernameToken", $wsse);
$Security["BinarySecurityToken"] = new SoapVar($token1, XSD_STRING, "Base64Binary", $wsse, "BinarySecurityToken", $wsse);
$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, "Security", $wsse);
$Header = [];
$Header[] = new SoapHeader($wsse, "Security", $Security, $mustUnderstand, $actor);
$MooWSe_client->__setSoapHeaders(NULL);
$MooWSe_client->__setSoapHeaders($Header);

try {
    $service = "service";
    $service_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service, "service")), ENT_XML1);
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($service_WSDL) . "</pre>";


$Username = $username2;

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
    $service = "service";
    $service_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service, "service")), ENT_XML1);
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($service_WSDL) . "</pre>";


sleep(2);

try {
    $service = "service2";
    $service_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service, "service")), ENT_XML1);
} catch (SoapFault $fault) {
    trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
}

echo getLastHTTPDialogue($MooWSe_client);
echo "<pre>" . htmlspecialchars($service_WSDL) . "</pre>";

function format($xml) {
    $dom = new DomDocument;
    $dom->loadXML($xml);
    $dom->formatOutput = true;
    return $dom->saveXML();
}

function getLastHTTPDialogue($client) {
    $string = "";
    $string .= "<pre>\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= "* request:\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= htmlentities($client->__getLastRequestHeaders()) . "\n";
    $string .= htmlentities(format($client->__getLastRequest())) . "\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= "* response:\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= htmlentities($client->__getLastResponseHeaders()) . "\n";
    $string .= htmlentities(format($client->__getLastResponse())) . "\n";
    $string .= "*---------------------------------------------------------------------------------\n";
    $string .= "</pre>";
    return $string;
}

function print_r_pre($thing) {
    echo "<pre>";
    print_r($thing);
    echo "</pre>";
}

?>