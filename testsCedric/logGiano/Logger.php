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

const LOGDEVICE=2;
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
            $this->dbLog($ip,$nom,$function,$op);
            break;
        case 3:
            $this->fileLog($txt);
            $this->dbLog($ip,$nom,$function,$op);
            break;
    }
}

private function fileLog($log){
    echo "</br>fileLog";
    $myfile = file_put_contents(Logger::LOGFILE, $log.PHP_EOL , FILE_APPEND);
}

private function createTable(){
    echo "</br>createTable";
    $connect=mysqli_connect("localhost","root","giano","log");
    $val = mysqli_query($connect,'select 1 from `logTable`');

    if(!$val){
        $sql=mysqli_query($connect,'CREATE TABLE logTable (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, ip VARCHAR(15), nome VARCHAR(50), function VARCHAR(50),operation VARCHAR(50))');
        if($sql) echo "</br>ok table created!";
    }
    mysqli_close($connect);
}

private function dbLog($ip,$nom,$function,$op){
    echo "</br>dbLog";
    $this->createTable();

    $connect=mysqli_connect("localhost","root","giano","log");
    $query="INSERT INTO `logTable`(`ip`, `nome`, `function`, `operation`) VALUES ('".$ip."','".$nom."','".$function."','".$op."')";
    $sql = mysqli_query($connect,$query);
    if($sql) echo "</br>inserted row";
    else echo "</br>error query: ".$query;
}

}

?>