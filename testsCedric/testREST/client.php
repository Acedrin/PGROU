<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
		<?php require "RestClient.php";
			$client = new RestClient();
			$var = "Machine";
			$res = $client->hello($var);
			print $res."<br/>\n";
		?>
	</body>
</html>