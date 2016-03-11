<?php

/*$LOGDDEVICE take one of the following three integer values:
- 1 = log in file
- 2 = log in db
- 3 = log either in file and db*/
$logDevice=3;

//relative or absolute path to the log file
$userLog_file="./logs/userLog";
$funcLog_file="./logs/funcLog";
$errorLog_file="./logs/errorLog";

//credentials for the database
$db="log";
$user="root";
$passwd="giano";
$port="3306";

/*LOG SPLITTING PARAMETERS
When some log file reach the max size, it will be saved as logfile_n 
and a new logfile will be created

PARAMETERS:
$max_filesize - max file size in MB for log files, for 

*/
$max_filesize=15;
$variables="./settings.ini";

?>