<?php

/*$LOGDDEVICE take one of the following three integer values:
- 1 = log in file
- 2 = log in db
- 3 = log either in file and db*/
$logDevice=3;

//relative ora absolute path to the log file
$userLog_file="./logs/userLog";
$funcLog_file="./logs/funcLog";
$errorLog_file="./logs/errorLog";

//credentials for the database
$db="log";
$user="root";
$passwd="giano";
$port="3306";


?>