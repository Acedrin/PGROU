<?php

class Logger{

// logDevice= 1-> file
//            2-> db
//            3-> file+db

private $LOGDEVICE;
private $USERLOG;
private $FUNCLOG;
private $ERRORLOG;
private $DB;
private $USER;
private $PASSWD;
private $PORT;

private $time;


public function __construct(){

    include 'settings.php';

    $this->LOGDEVICE=$logDevice;
    $this->USERLOG=$userLog_file;
    $this->FUNCLOG=$funcLog_file;
    $this->ERRORLOG=$errorLog_file;
    $this->DB=$db;
    $this->USER=$user;
    $this->PASSWD=$passwd;
    $this->PORT=$port;

    echo "costruttore";
}

public function LogUser($login, $ip, $token){
   
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$login." ".$token;

    echo "</br>".$txt;
    echo "</br>case";

    switch($this->LOGDEVICE){
        case 1:
        echo "</br>case 1";
            $this->userLog_file($txt);
            break;
        case 2:
           $this->userLog_db($login, $ip, $token);
            break;
        case 3:
            $this->userLog_file($txt);
            $this->userLog_db($login, $ip, $token);
            break;
    }
}

public function LogFunc($login, $ip, $token, $function){
   
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$login." ".$token." ".$function;

    echo "</br>".$txt;
    echo "</br>case";

    switch($this->LOGDEVICE){
        case 1:
        echo "</br>case 1";
            $this->funcLog_file($txt);
            break;
        case 2:
            $this->funcLog_db($login, $ip, $token, $function);
            break;
        case 3:
            $this->funcLog_file($txt);
            $this->funcLog_db($login, $ip, $token, $function);
            break;
    }
}

public function LogError($login, $ip, $token, $error){
   
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$login." ".$token." ".$error;

    echo "</br>".$txt;
    echo "</br>case";

    switch($this->LOGDEVICE){
        case 1:
        echo "</br>case 1";
            $this->errorLog_file($txt);
            break;
        case 2:
           $this->errorLog_db($login, $ip, $token,$error);
            break;
        case 3:
            $this->errorLog_file($txt);
            $this->errorLog_db($login, $ip, $token,$error);
            break;
    }
}

private function userLog_file($log){
    echo "</br>fileLog";

    $myfile = file_put_contents($this->USERLOG, $log.PHP_EOL , FILE_APPEND);
}

private function funcLog_file($log){
    echo "</br>funcLog";

    $myfile = file_put_contents($this->FUNCLOG, $log.PHP_EOL , FILE_APPEND);
}

private function errorLog_file($log){
    echo "</br>errorLog";

    $myfile = file_put_contents($this->ERRORLOG, $log.PHP_EOL , FILE_APPEND);
}

public function userLog_db($login, $ip, $token){
    echo "</br>userLog_db";

    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);

    $connect->exec("INSERT INTO `userLog`(`userLog_time`,`userLog_ip`, `userLog_user`, `userLog_token`) VALUES ( NOW(),'".$ip."','".$login."','".$token."')");
    $connect=null;
}

public function funcLog_db($login, $ip, $token, $function){
    echo "</br>funcLog_db";

    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);

    $connect->exec("INSERT INTO `funcLog`(`funcLog_time`,`funcLog_ip`, `funcLog_user`, `funcLog_token`,`funcLog_func`) VALUES ( NOW(),'".$ip."','".$login."','".$token."','".$function."')");
    $connect=null;
}

public function errorLog_db($login, $ip, $token, $error){
    echo "</br>errorLog_db";

    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);
    
    $connect->exec("INSERT INTO `errorLog`(`errorLog_time`,`errorLog_ip`, `errorLog_user`, `errorLog_token`,`errorLog_error`) VALUES ( NOW(),'".$ip."','".$login."','".$token."','".$error."')");
    $connect=null;
}

}

?>