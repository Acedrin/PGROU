<?php ini_set("soap.wsdl_cache_ttl",1);
	$client = new SoapClient("http://localhost/github/PGROU/testsCedric/testWStoken/test.wsdl");
	$token = $client->getToken();
	//echo $client->__getLastResponseHeaders();
	$client->__setSoapHeaders(new SoapHeader('http://soapinterop.org/echoheader/','token',$token));
	
	$var = "Machine";
	$res = $client->hello(new SoapParam($var, "name"));
	print $res."<br/>\n";
?>