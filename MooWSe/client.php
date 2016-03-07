<?php ini_set("soap.wsdl_cache_ttl",1);

	//echo bin2hex(openssl_random_pseudo_bytes(8,true));

	$username = "client".","."modalite";
	$password = "password";
	
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
        "exceptions"=>true,
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
		$MooWSe_client = new SoapClient($MooWSe_WSDL,$options);
	} catch (SoapFault $fault) {
		trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	}
	
	$wsse = "http://schemas.xmlsoap.org/ws/2002/07/secext";
	$mustUnderstand = true; // oblige le récepteur à traiter l'header
	$actor = "http://schemas.xmlsoap.org/soap/actor/next"; // le destinataire est le premier récepteur
	//$actor = "http://localhost/github/PGROU/testsCedric/testWStoken/server.php"; // le destinataire est ce récepteur (ne marche pas)
	//$actor = ""; // le destinataire est le dernier récepteur (marche mais lance un warning)
	
	$Username = $username;
	$Password = $password;
	$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, NULL, $wsse);
	$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordText", $wsse, NULL, $wsse);
	$Security = array();
	$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, NULL, $wsse);
	$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, NULL, $wsse);
	$Header = array();
	$Header[] = new SoapHeader($wsse,"Security",$Security,$mustUnderstand,$actor);
	$MooWSe_client->__setSoapHeaders(NULL);
	$MooWSe_client->__setSoapHeaders($Header);
	
	//print_r_pre($Header);
	
	try {
		$token = $MooWSe_client->authenticate();
	} catch (SoapFault $fault) {
		trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	}
	
	echo getLastHTTPDialogue($MooWSe_client);
	echo "<pre>".htmlspecialchars($token)."</pre>";
	
	$Security = array();
	$Security["BinarySecurityToken"] = new SoapVar($token, XSD_STRING, NULL, $wsse, NULL, $wsse);
	$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, NULL, $wsse);
	$Header = array();
	$Header[] = new SoapHeader($wsse,"Security",$Security,$mustUnderstand,$actor);
	$MooWSe_client->__setSoapHeaders(NULL);
	$MooWSe_client->__setSoapHeaders($Header);
	
	try {
		$service = "service";
		$service_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service, "service")),ENT_XML1);
	} catch (SoapFault $fault) {
		trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	}
	
	echo getLastHTTPDialogue($MooWSe_client);
	echo "<pre>".htmlspecialchars($service_WSDL)."</pre>";
	
	sleep(2);
	
	try {
		$service = "service2";
		$service_WSDL = htmlspecialchars_decode($MooWSe_client->getWSDL(new SoapParam($service, "service")),ENT_XML1);
	} catch (SoapFault $fault) {
		trigger_error("SOAP Fault: (faultcode: {$fault->faultcode}, faultstring: {$fault->faultstring})", E_USER_ERROR);
	}
	
	echo getLastHTTPDialogue($MooWSe_client);
	echo "<pre>".htmlspecialchars($service_WSDL)."</pre>";
	
	
	
	
	
	
	
	
	
	
	
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