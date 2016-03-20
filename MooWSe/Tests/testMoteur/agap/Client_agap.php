<!DOCTYPE html>
<html>
    <head>
        <meta	charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $client = new SoapClient("http://localhost/agap/moodle.wsdl");
		
        $var = 4;
		
        $res1 = $client->matiere(new SoapParam($var, "NumMatiere"));
        print $res1 . "<br/>\n";

		$var2 = 'PAMEF';
		
        $res2 = $client->description(new SoapParam($var2, "DesMatiere"));
        print $res2 . "<br/>\n";


        ?>
    </body>
</html>