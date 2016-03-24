<?php

/*$LOGDDEVICE take one of the following three integer values:
- 1 = log in file
- 2 = log in db
- 3 = log either in file and db*/
$logDevice=1;

//relative or absolute path to the logs file
$userLog_file="./logs/userLog";
$funcLog_file="./logs/servLog";
$errorLog_file="./logs/errorLog";

//credentials for the database
$db="log";
$user="";
$passwd="";
$port="3306";

/*LOG SPLITTING PARAMETERS
When some log file reach the max size, it will be saved as logfile_n 
and a new logfile will be created

PARAMETERS:
$max_filesize - max file size in MB for log files, beyond this size the file is stocked
				and renamed as "exampleFile.n", where n is an incremental number managed by script; 
$max_fileage  - max age of logfile in days, all log files (including stocked ones) beyond this deadline
				will be deleted
$variables 	  - path to file to store some variables for creation of stocked log (e.g. n incremental variable) 
*/
$max_filesize=15;
$max_fileage=30;
$variables="./settings/settings.ini";

?>