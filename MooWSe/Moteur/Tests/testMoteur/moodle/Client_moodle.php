<!DOCTYPE html>
<html>
    <head>
        <meta	charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $client = new SoapClient("http://localhost/moodle/agap.wsdl");
		
        $var = 4;
		
        $res1 = $client->info(new SoapParam($var, "StudentNumber"));
        print $res1 . "<br/>\n";

		$var = 'qdelamot';
		$var2= 'mot de passe';
        $res2 = $client->notes(new SoapParam($var, "StudentLogin"),new SoapParam($var2, "StudentPassword"));
        print $res2 . "<br/>\n";


        ?>
    </body>
</html>