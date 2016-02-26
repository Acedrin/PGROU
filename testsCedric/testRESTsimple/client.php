<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title></title>
	</head>
	<body>
		<?php 
			
			$var = "Machine";
			$res = file_get_contents("http://localhost/github/PGROU/testsCedric/testREST/hello/".$var);
			print $res."<br/>\n";
		?>
	</body>
</html>