<?php

/*starving file
variabile n, se possibile divisa per i tre tipi di log
*/

class LogRotate{


private $file;
private $filesize;
private $variables_file; // file .ini for persistent variables
private $n=2;

	public function __construct(){

		include './settings/settings.php';

		$this->filesize=$max_filesize*1048576; //from MB to bytes
		$this->variable_file=$variables;
	}


	public function checkFile($file){

		$this->file=$file;

    	if(filesize($this->file) < $this->filesize){//girare disugualianza

			$newfile = $this->createFileName($this->file);

			if (copy($this->file, $newfile)) {

    			file_put_contents($this->file,"");

			}
    	}
    	
	}

	private function createFileName($oldfile){

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
		return $path.$newname;
	}

//rimettere privata
	private function maxAgedFile($file){
			$fullname=explode("/", $file);
			$path="";

			for($x=0;$x<(count($fullname)-1);$x++){ 
				$path.=$fullname[$x]."/";
			}

			$name=$fullname[count($fullname)-1];

			if ($handle = opendir($path)) {
    			echo "Directory handle: $handle</br>";
    			// echo "Entries:</br>".$path.$name."</br>";

    		//This is the correct way to loop over the directory.
    		while (false !== ($entry = readdir($handle))) {
					// echo "$entry</br>";

				$pos=strpos($entry,$name);

    			if($pos===false){
    				//do nothing
    			}else{
    					if((time()-filemtime($path.$name)) > 1500){

    						echo "$entry</br>";
    					}
    					
    				}
       		 	
    			}

   			 closedir($handle);
			}
	}


}

?>