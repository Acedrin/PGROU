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

private $_time;

private $filesize;
private $variables_file; // file .ini for persistent variables
private $n;
private $FILEAGE;

public $debug=false;


public function __construct(){

    //include settings file and import settings
    include './settings/settings.php';

    $this->LOGDEVICE=$logDevice;
    $this->USERLOG=$userLog_file;
    $this->FUNCLOG=$funcLog_file;
    $this->ERRORLOG=$errorLog_file;
    $this->DB=$db;
    $this->USER=$user;
    $this->PASSWD=$passwd;
    $this->PORT=$port;


    $this->filesize=$max_filesize*1048576; //from MB to bytes to compare with the log files size
    $this->variable_file=$variables;
    $this->FILEAGE=$max_fileage*86400;//days to seconds for age comparison with log files

    if($this->debug) echo "costruttore";
}

//public function to log user and servers login
public function LogClient($ip,$client,$modality,$action){

    //create the string of actual datetime and text string for log file
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$client." ".$modality." ".$action;

    if($this->debug) echo "</br>".$txt;

    //evaluate value of $LOGDEVICE to decide where we will save the log entry
    switch($this->LOGDEVICE){
        case 1:
            //first we check the log file to verify if it's too big
            $check=$this->checkFile($this->USERLOG);
            //than write in the log file
            $this->userLog_file($txt);
            break;
        case 2:
           $this->userLog_db($ip,$client,$modality,$action);
            break;
        case 3:
            //first we check the log file to verify if it's too big
            $check=$this->checkFile($this->USERLOG);
            //than write in the log file and in DB
            $this->userLog_file($txt);
            $this->userLog_db($ip,$client,$modality,$action);
            break;
    }
}

//public function to log services requested from an user/app
public function LogServ($ip, $client, $modality, $service, $action){

    //create the string of actual datetime and text string for log file
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$client." ".$modality." ".$service." ".$action;

    if($this->debug) echo "</br>".$txt;

    //evaluate value of $LOGDEVICE to decide where we will save the log entry
    switch($this->LOGDEVICE){
        case 1:
            //first we check the log file to verify if it's too big
            $check=$this->checkFile($this->FUNCLOG);
            //than write in the log file
            $this->funcLog_file($txt);
            break;
        case 2:
            $this->funcLog_db($client, $ip, $modality, $service, $action);
            break;
        case 3:
            //first we check the log file to verify if it's too big
            $check=$this->checkFile($this->FUNCLOG);
            //than write in the log file and in DB
            $this->funcLog_file($txt);
            $this->funcLog_db($client, $ip, $modality, $service, $action);
            break;
    }
}

//public function to log errors
public function LogError($ip, $client, $error){

    //create the string of actual datetime and text string for log file
    $this->time="[".date('Y/m/d - H:i:s', time())."]";
    $txt=$this->time." ".$ip." ".$client." ".$error;

    if($this->debug) echo "</br>".$txt;

    //evaluate value of $LOGDEVICE to decide where we will save the log entry
    switch($this->LOGDEVICE){
        case 1:
            //first we check the log file to verify if it's too big
            $check=$this->checkFile($this->ERRORLOG);
            //than write in the log file
            $this->errorLog_file($txt);
            break;
        case 2:
           $this->errorLog_db($ip, $client, $error);
            break;
        case 3:
            //first we check the log file to verify if it's too big
            $check=$this->checkFile($this->ERRORLOG);
            //than write in the log file and in DB
            $this->errorLog_file($txt);
            $this->errorLog_db($ip, $client, $error);
            break;
    }
}


//private functions to store userLog messages on file
private function userLog_file($log){
    if($this->debug) echo "</br>fileLog";

    $myfile = file_put_contents($this->USERLOG, $log.PHP_EOL , FILE_APPEND);
}

//private functions to store servLog messages on file
private function funcLog_file($log){
    if($this->debug) echo "</br>funcLog";

    $myfile = file_put_contents($this->FUNCLOG, $log.PHP_EOL , FILE_APPEND);
}

//private functions to store errorLog messages on file
private function errorLog_file($log){
    if($this->debug) echo "</br>errorLog";

    $myfile = file_put_contents($this->ERRORLOG, $log.PHP_EOL , FILE_APPEND);
}

//private functions to store userLog messages on DB
private function userLog_db($ip,$client,$modality,$action){
    if($this->debug) echo "</br>userLog_db";

    //instantiate mysql connection object with PDO
    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);
    //crete query string
    $query="INSERT INTO `userLog`(`userLog_time`,`userLog_ip`, `userLog_client`, `userLog_modalite`, `userLog_action`) VALUES ( NOW(),'".$ip."','".$client."','".$modality."','".$action."')";
    //query execution
    $connect->exec($query);
    if($this->debug) echo $query;

    //close connection
    $connect=null;
}

//private functions to store servLog messages on DB
private function funcLog_db($client, $ip, $modality, $service, $action){
    if($this->debug) echo "</br>funcLog_db";

    //instantiate mysql connection object with PDO
    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);
    
    //crete query string
    $query="INSERT INTO `servLog`(`servLog_time`,`servLog_ip`, `servLog_client`, `servLog_modalite`,`servLog_service`,`servLog_action`) VALUES ( NOW(),'".$ip."','".$client."','".$modality."','".$service."','".$action."')";
   
    //query execution
    $connect->exec($query);
    if($this->debug) echo $query;

    //close connection
    $connect=null;
}

//private functions to store errorLog messages on DB
private function errorLog_db($ip, $client, $error){
    if($this->debug) echo "</br>errorLog_db";

    //instantiate mysql connection object with PDO
    $connect = new PDO('mysql:host=localhost;port='.$this->PORT.';dbname='.$this->DB, $this->USER, $this->PASSWD);
   
    //crete query string
    $query="INSERT INTO `errorLog`(`errorLog_time`,`errorLog_ip`, `errorLog_client`,`errorLog_error`) VALUES ( NOW(),'".$ip."','".$client."','".$error."')";

    //query execution
    $connect->exec($query);

    if($this->debug) echo $query;
    
    //close connection
    $connect=null;
}


/*-------------------------Logrotate Functions--------------------------------------------*/

//private function to state the size of log file in the argument
    private function checkFile($file){

        //first verify if the file is too old
        $this->maxAgedFile($file);

        //verify if the file is bigger then the max size
        if(filesize($file) > $this->filesize){

            /*this function is some sort of rename, it creates a new file
            to store all the old entries and cleans the logfile*/
            $newfile = $this->createFileName($file);

            //copy all text lines in the newfile and clear the standard log file
            if (copy($file, $newfile)) {

                file_put_contents($file,"");

            }
        }
        
    }

//private function to create a newfile from a old file, the name of new file
//will be newfile=oldfile.n , where n is an incremental integer value
    private function createFileName($oldfile){

        //first read the actual value of n with the private function variableOnFile
        $this->n=$this->variableOnFile("read");
        //create an array where each entry is a folder in the path
        $name=explode("/", $oldfile);
        $path="";
        $newname="";

        //retrieve the path for the file (path without the last entry, wich is the oldfile name)
        for($x=0;$x<(count($name)-1);$x++){ 
            $path.=$name[$x]."/";
            } 
        //retrive the name of the file without the path
        $oldfile=$name[(count($name)-1)];
        /*create an array where each entry is a part of the oldfile name divided by
        a dot. Ex: userlog.txt -> name==["userlog","txt"]*/
        $name=explode(".", $oldfile);

        //if the name of the file has dots in his name
        if(count(name)>1){
            for($i=0; $i<count($name);$i++){

                //if we are in the ultimate entry, add the n variable Ex: userLog.1.txt
                if($i==count($name)-1) $newname.=$this->n.".".$name[$i];

                //else add the entry with a dot
                else  $newname.=$name[$i].".";
            }
        }else{
            //if oldfile name don't have dots
            $newname.=$name[0].".".$this->n;
        }
        //write the variable on file increased by one
        $this->variableOnFile("write",$this->n+1);
        //return the newfile name with the path
        return $path.$newname;
    }

//private function to delete check the file age and delete the overAged
    private function maxAgedFile($file){

            //create an array where each entry is a folder in the path
            $fullname=explode("/", $file);
            $path="";
            $number=0;

            //retrieve the path for the file (path without the last entry, wich is the file name)
            for($x=0;$x<(count($fullname)-1);$x++){ 
                $path.=$fullname[$x]."/";
            }

            //retrieve the name of the file without path
            $name=$fullname[count($fullname)-1];

            //function to read the content of the folder specified in the path
            if ($handle = opendir($path)) {

            //This is the correct way to loop over the directory and read each entry
            while (false !== ($entry = readdir($handle))) {

                /*looking for filenames wich have the name of the used logfile in their name
                ex: name=="userLog" finds positive for userLog userLog.0, userLog.2 ...*/
                $pos=strpos($entry,$name);

                if($pos===false){
                    //do nothing
                }else{
                        //clear the cache beacause PHP staged the result of filetime function in cache
                        clearstatcache();
                        //check if the file is older than the maxAge
                        if((time()-filemtime($path.$entry)) > $this->FILEAGE){
                        
                        if($this->debug) echo "</br>$entry ".date ("F d Y H:i:s.", filemtime($path.$entry))."</br>";
                        //PHP function to delete file
                        unlink($path.$entry);

                        }
                        
                    }
                
                }
            //close directory handler
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
                      //clear the cache beacause PHP staged the result of filetime function in cache
                      clearstatcache();
                      //rename file adding the increasing number $number
                      rename($path.$entry, $path.$name.".".$number);
                      $number++;
                    }
                
                }
             //close directory handler
             closedir($handle);
             //write the new value of n in the file
             $this->variableOnFile("write",$number);
            }
    }

//private function to read and write n variable from the text file for storing
    private function variableOnFile($mode,$var=null){

        if($mode==="write"){
            //write the value of n in the file
            file_put_contents($this->variable_file, "[n=".$var."]");
        }
        elseif($mode==="read"){
            //regEx to find n in the file and return its value
            $str=file_get_contents($this->variable_file);
            preg_match("/\[n=(.*?)]/",$str,$m);
            return $m[1];
        }
    }

}

?>