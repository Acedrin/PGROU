<?php ini_set("soap.wsdl_cache_ttl",1);
	$client = new SoapClient("http://localhost/github/PGROU/testsCedric/testWStoken/test.wsdl",array('trace'   => true));
	$token = $client->getToken();
	echo "<pre>";
	print "getToken request:\n\n";
	print_r(htmlentities($client->__getLastRequestHeaders()));
	print_r(htmlentities($client->__getLastRequest()));
	print "\ngetToken response:\n\n";
	print_r(htmlentities($client->__getLastResponseHeaders()));
	print "\n";
	print_r(htmlentities($client->__getLastResponse()));
	echo "\n\n</pre>";
	$client->__setSoapHeaders(new SoapHeader("http://soapinterop.org/echoheader/","setToken",$token));
	
	$var = "Machine";
	$res = $client->hello(new SoapParam($var, "name"));
	echo "<pre>";
	print "hello request:\n\n";
	print_r(htmlentities($client->__getLastRequestHeaders()));
	print_r(htmlentities($client->__getLastRequest()));
	print "\nhello response:\n\n";
	print_r(htmlentities($client->__getLastResponseHeaders()));
	print "\n";
	print_r(htmlentities($client->__getLastResponse()));
	echo "\n\n</pre>";
	print $res."<br/>\n";
	
	sleep(2);
	
	$var = "Machine2";
	$res = $client->hello(new SoapParam($var, "name"));
	echo "<pre>";
	print "hello request:\n\n";
	print_r(htmlentities($client->__getLastRequestHeaders()));
	print_r(htmlentities($client->__getLastRequest()));
	print "\nhello response:\n\n";
	print_r(htmlentities($client->__getLastResponseHeaders()));
	print "\n";
	print_r(htmlentities($client->__getLastResponse()));
	echo "\n\n</pre>";
	print $res."<br/>\n";
?>