<?php ini_set("soap.wsdl_cache_ttl",1);
	$client = new SoapClient("http://localhost/github/PGROU/testsCedric/testWS/test.wsdl");
	$var = "Machine";
	$res = $client->hello(new SoapParam($var, "name"));
	print $res."<br/>\n";
?>