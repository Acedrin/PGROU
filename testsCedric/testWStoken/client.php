<?php ini_set("soap.wsdl_cache_ttl",1);

	$login = "login";
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
	$wsdl = "http://localhost/github/PGROU/testsCedric/testWStoken/test.wsdl";
	$client = new SoapClient($wsdl,$options);
	
	$wsse = "http://schemas.xmlsoap.org/ws/2002/07/secext";
	$mustUnderstand = true; // oblige le récepteur à traiter l'header
	$actor = "http://schemas.xmlsoap.org/soap/actor/next"; // le destinataire est le premier récepteur
	//$actor = "http://localhost/github/PGROU/testsCedric/testWStoken/server.php"; // le destinataire est ce récepteur (ne marche pas)
	//$actor = ""; // le destinataire est le dernier récepteur (marche mais lance un warning)
	
	$Username = $login;
	$Password = $password;
	$UsernameToken["Username"] = new SoapVar($Username, XSD_STRING, NULL, $wsse, NULL, $wsse);
	$UsernameToken["Password"] = new SoapVar($Password, XSD_STRING, "PasswordDigest", $wsse, NULL, $wsse);
	$Security = array();
	$Security["UsernameToken"] = new SoapVar($UsernameToken, SOAP_ENC_OBJECT, NULL, $wsse, NULL, $wsse);
	$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, NULL, $wsse);
	$Header = array();
	$Header[] = new SoapHeader($wsse,"Security",$Security,$mustUnderstand,$actor);
	$client->__setSoapHeaders(NULL);
	$client->__setSoapHeaders($Header);
	
	print_r_pre($Header);
	
	$token = $client->authenticate();
	echo getLastHTTPRequest($client);
	echo $token."<br/>\n";
	
	$Security = array();
	$Security["BinarySecurityToken"] = new SoapVar($token, XSD_STRING, NULL, $wsse, NULL, $wsse);
	$Security = new SoapVar($Security, SOAP_ENC_OBJECT, NULL, $wsse, NULL, $wsse);
	$Header = array();
	$Header[] = new SoapHeader($wsse,"Security",$Security,$mustUnderstand,$actor);
	$client->__setSoapHeaders(NULL);
	$client->__setSoapHeaders($Header);
	
	$var = "Machine";
	$res = $client->hello(new SoapParam($var, "name"));
	echo getLastHTTPRequest($client);
	print $res."<br/>\n";
	
	sleep(2);
	
	$var = "Machine2";
	$res = $client->hello(new SoapParam($var, "name"));
	echo getLastHTTPRequest($client);
	print $res."<br/>\n";
	
	
	
	
	
	
	
	
	
	function format($xml) {
		$dom = new DomDocument;
		$dom->loadXML($xml);
		$dom->formatOutput = true;
		return $dom->saveXML();	
	}
	
	function getLastHTTPRequest($client) {
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