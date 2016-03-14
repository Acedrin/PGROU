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

private $filesize;
private $variables_file; // file .ini for persistent variables
private $n;
private $FILEAGE;

public $debug=false;


public function __construct(){

    include './settings/settings.php';

    $this->LOGDEVICE=$logDevice;
    $this->USERLOG=$userLog_file;
    $this->FUNCLOG=$funcLog_file;
    $this->ERRORLOG=$errorLog_file;
    $this->DB=$db;
    $this->USER=$user;
    $this->PASSWD=$passwd;
    $this->PORT=$port;


    $this->filesize=$max_filesize*1048576; //from MB to bytes
    $this->variable_file=$variables;
    $this->FILEAGE=$max_fileage*86400;//days to seconds

    if($this->debug) echo "costruttore";
}

//public function to log Users and token
public function LogClient($ip,$client,$modality,$action){
 
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$client." ".$modality." ".$action;

    if($this->debug) echo "</br>".$txt;
    if($this->debug) echo "</br>case";

    switch($this->LOGDEVICE){
        case 1:
            $check=$this->checkFile($this->USERLOG);
            $this->userLog_file($txt);
            break;
        case 2:
           $this->userLog_db($ip,$client,$modality,$action);
            break;
        case 3:
            $check=$this->checkFile($this->USERLOG);
            $this->userLog_file($txt);
            $this->userLog_db($ip,$client,$modality,$action);
            break;
    }
}

//public function to log function and request from an user/app
public function LogServ($client, $ip, $modality, $service, $action){


   
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$client." ".$modality." ".$service." ".$action;

    if($this->debug) echo "</br>".$txt;
    if($this->debug) echo "</br>case";

    switch($this->LOGDEVICE){
        case 1:
            $check=$this->checkFile($this->FUNCLOG);
            $this->funcLog_file($txt);
            break;
        case 2:
            $this->funcLog_db($client, $ip, $modality, $service, $action);
            break;
        case 3:
            $check=$this->checkFile($this->FUNCLOG);
            $this->funcLog_file($txt);
            $this->funcLog_db($client, $ip, $modality, $service, $action);
            break;
    }
}

//public function to log errors
public function LogError($login, $ip, $token, $error){


   
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$login." ".$token." ".$error;

    if($this->debug) echo "</br>".$txt;
    if($this->debug) echo "</br>case";

    switch($this->LOGDEVICE){
        case 1:
            $check=$this->checkFile($this->ERRORLOG);
            $this->errorLog_file($txt);
            break;
        case 2:
           $this->errorLog_db($login, $ip, $token,$error);
            break;
        case 3:
            $check=$this->checkFile($this->ERRORLOG);
            $this->errorLog_file($txt);
            $this->errorLog_db($login, $ip, $token,$error);
            break;
    }
}


//private functions to store log messages on file or DB
private function userLog_file($log){
    if($this->debug) echo "</br>fileLog";

    $myfile = file_put_contents($this->USERLOG, $log.PHP_EOL , FILE_APPEND);
}

private function funcLog_file($log){
    if($this->debug) echo "</br>funcLog";

    $myfile = file_put_contents($this->FUNCLOG, $log.PHP_EOL , FILE_APPEND);
}

private function errorLog_file($log){
    if($this->debug) echo "</br>errorLog";

    $myfile = file_put_contents($this->ERRORLOG, $log.PHP_EOL , FILE_APPEND);
}

private function userLog_db($ip,$client,$modality,$action){
    if($this->debug) echo "</br>userLog_db";

    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);

    $connect->exec("INSERT INTO `userLog`(`userLog_time`,`userLog_ip`, `userLog_client`, `userLog_modalite`, `userLog_action`) VALUES ( NOW(),'".$ip."','".$client."','".$modality."','".$action."')");
    $connect=null;
}

private function funcLog_db($client, $ip, $modality, $service, $action){
    if($this->debug) echo "</br>funcLog_db";

    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);

    $connect->exec("INSERT INTO `servLog`(`servLog_time`,`servLog_ip`, `servLog_client`, `servLog_modalite`,`servLog_service`) VALUES ( NOW(),'".$ip."','".$client."','".$modality."','".$service."','".$action."')");
    $connect=null;
}

private function errorLog_db($client, $ip, $token, $error){
    if($this->debug) echo "</br>errorLog_db";

    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);

    $connect->exec("INSERT INTO `errorLog`(`errorLog_time`,`errorLog_ip`, `errorLog_client`, `errorLog_token`,`errorLog_error`) VALUES ( NOW(),'".$ip."','".$client."','".$token."','".$error."')");
    $connect=null;
}

//-------------------------logrotate funcions--------------------------------------------

    public function checkFile($file){

        $this->maxAgedFile($file);

        if(filesize($file) > $this->filesize){

            $newfile = $this->createFileName($file);

            if (copy($file, $newfile)) {

                file_put_contents($file,"");

            }
        }
        
    }

    private function createFileName($oldfile){

        $this->n=$this->variableOnFile("read");
        $name=explode("/", $oldfile);
        $path="";
        $newname="";

        for($x=0;$x<(count($name)-1);$x++){ 
            $path.=$name[$x]."/";
            } 

        $oldfile=$name[(count($name)-1)];
        $name=explode(".", $oldfile);

        if(count(name)>1){
            for($i=0; $i<count($name);$i++){

                if($i==count($name)-1) $newname.=$this->n.".".$name[$i];

                else  $newname.=$name[$i].".";
            }
        }else{
            $newname.=$name[0].".".$this->n;
        }
        $this->variableOnFile("write",$this->n+1);
        return $path.$newname;
    }

//function to delete file over the maxAge
    private function maxAgedFile($file){
            $fullname=explode("/", $file);
            $path="";
            $number=0;

            for($x=0;$x<(count($fullname)-1);$x++){ 
                $path.=$fullname[$x]."/";
            }

            $name=$fullname[count($fullname)-1];

            if ($handle = opendir($path)) {

            //This is the correct way to loop over the directory.
            while (false !== ($entry = readdir($handle))) {

                $pos=strpos($entry,$name);

                if($pos===false){
                    //do nothing
                }else{
                        clearstatcache();
                        if((time()-filemtime($path.$entry)) > $this->FILEAGE){
                        
                        if($this->debug) echo "</br>$entry ".date ("F d Y H:i:s.", filemtime($path.$entry))."</br>";
                        unlink($path.$entry);

                        }
                        
                    }
                
                }

             closedir($handle);
            }

            //rename stocked logFiles to have a count that make sense
            if ($handle = opendir($path)) {

            //This is the correct way to loop over the directory.
            while (false !== ($entry = readdir($handle))) {

                $pos=strpos($entry,$name.'.');

                if($pos===false){
                    //do nothing
                }else{
                      clearstatcache();
                      rename($path.$entry, $path.$name.".".$number);
                      $number++;
                    }
                
                }

             closedir($handle);
             $this->variableOnFile("write",$number);
            }
    }

    private function variableOnFile($mode,$var=null){

        if($mode==="write"){
            file_put_contents($this->variable_file, "[n=".$var."]");
        }
        elseif($mode==="read"){
            $str=file_get_contents($this->variable_file);
            preg_match("/\[n=(.*?)]/",$str,$m);
            return $m[1];
        }
    }

}

?>