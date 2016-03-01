<?php ini_set("soap.wsdl_cache_ttl",1);
	$client = new SoapClient("http://localhost/github/PGROU/testsCedric/testWStoken/test.wsdl");
	$token = $client->getToken();
	$var = "Machine";
	$res = $client->hello(new SoapParam($var, "name"),new SoapParam($token, "token"));
	print $res."<br/>\n";
?>