<?php ini_set("soap.wsdl_cache_ttl",1);
	$client = new SoapClient("http://localhost/github/PGROU/testsCedric/testWStoken/test.wsdl",array('trace'   => true));
	
	$token = $client->getToken();
	echo getLastHTTPRequest($client);
	echo $token."<br/>\n";
	
	$client->__setSoapHeaders(new SoapHeader("http://soapinterop.org/echoheader/","setToken",$token));
	
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

?>