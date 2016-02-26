<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
		<?php ini_set("soap.wsdl_cache_enabled", 0);
			$client = new SoapClient("http://localhost/github/PGROU/testsCedric/testWS/test.wsdl");
			$var = "Machine";
			$res = $client->hello(new SoapParam($var, "name"));
			print $res."<br/>\n";
		?>
	</body>
</html>