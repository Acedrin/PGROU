<?php

class Logger{

// logDevice= 1-> file
//            2-> db
//            3-> file+db

private $LOGDEVICE;
private $LOGFILE;
private $DB;
private $USER;
private $PASSWD;


public function __construct(){

    include 'settings.php';

    $this->LOGDEVICE=$logDevice;
    $this->LOGFILE=$logFile;
    $this->DB=$db;
    $this->USER=$user;
    $this->PASSWD=$passwd;

    echo "costruttore";
}

public function Logging($ip,$nom,$function,$op){

    echo $this->LOGDEVICE;
    echo $this->LOGFILE;


    $time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$time." ".$ip." ".$nom." ".$function." ".$op;
    echo "</br>".$txt;
    echo "</br>case";
    switch($this->LOGDEVICE){
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
    $myfile = file_put_contents($this->LOGFILE, $log.PHP_EOL , FILE_APPEND);
}

private function createTable(){
    echo "</br>createTable";
    $connect=mysqli_connect("localhost",$this->$USER,$this->$PASSWD,$this->$DB);
    $val = mysqli_query($connect,'select 1 from `logTable`');

    if(!$val){
        $sql=mysqli_query($connect,'CREATE TABLE logTable (id INT AUTO_INCREMENT PRIMARY KEY NOT NULL, time DATETIME, ip VARCHAR(15), nome VARCHAR(50), function VARCHAR(50),operation VARCHAR(50))');
        if($sql) echo "</br>ok table created!";
    }
    mysqli_close($connect);
}

private function dbLog($ip,$nom,$function,$op){
    echo "</br>dbLog";
    $this->createTable();

    $connect=mysqli_connect("localhost",$this->$USER,$this->$PASSWD,$this->$DB);
    $query="INSERT INTO `logTable`(`time`,`ip`, `nome`, `function`, `operation`) VALUES ( NOW(),'".$ip."','".$nom."','".$function."','".$op."')";
    $sql = mysqli_query($connect,$query);
    if($sql) echo "</br>inserted row";
    else echo "</br>error query: ".$query;
}

}

?>