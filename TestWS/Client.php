<!DOCTYPE html>
<html>
    <head>
        <meta	charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        $client = new SoapClient("http://localhost/github/PGROU/TestWS/test.wsdl");
        //$client = new SoapClient("http://localhost/github/PGROU/TestWS/test2.wsdl");
		
        $var = "Machine";
		
        $res1 = $client->hello(new SoapParam($var, "name"));
        print $res1 . "<br/>\n";

        $res2 = $client->goodbye(new SoapParam($var, "name"));
        print $res2 . "<br/>\n";

        $var2 = 3;
        $var3 = 6;
        $res3 = $client->somme(new SoapParam($var2, "a"),
                              new SoapParam($var3, "b"));
        print $res3 . "<br/>\n";

        ?>
    </body>
</html>