<?php

/*IP du client
Date/heure
Nom de l’application
Fonction/Service appelée
Description de l’opération effectuée
*/

include('settings.php');

class Logger{

// logDevice= 1-> file
//            2-> db
//            3-> file+db

const LOGDEVICE=1;
const LOGFILE="./prava.txt";

/*public function stampa($txt){
    echo $txt;
}*/


/*public function __construct(){
    $this->logDevice=$logDevice;
    $this->logFile=$logFile;
    echo "costruttore";
    // aggiungere lettura file settings per parametri
}*/

public function Logging($ip,$nom,$function,$op){

    echo Logger::LOGDEVICE;
    echo Logger:: LOGFILE;

    $time="[".date('H:i:s', time())."]";
    $txt=$time." ".$ip." ".$nom." ".$function." ".$op;
    echo "</br>".$txt;
    echo "</br>case";
    switch(Logger::LOGDEVICE){
        case 1:
        echo "</br>case 1";
            $this->fileLog($txt);
            break;
        case 2:
            $this->dbLog($txt);
            break;
        case 3:
            $this->fileLog($txt);
            $this->dbLog($txt);
            break;
    }
}

private function fileLog($log){
    echo "</br>fileLog";
    $myfile = file_put_contents(Logger::LOGFILE, $log.PHP_EOL , FILE_APPEND);
}

private function dbLog($log){

}

}

?>