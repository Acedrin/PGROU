<?php

/*starving file
variabile n, se possibile divisa per i tre tipi di log
*/

class LogRotate{


private $file;
private $filesize;
private $variables_file; // file .ini for persistent variables
private $n=2;

	public function __construct($file){

		include './settings/settings.php';

		$this->file=$file;
		$this->filesize=$max_filesize*1048576; //from MB to bytes
		$this->variable_file=$variables;
	}


	public function checkFile(){


    	if(filesize($this->file) < $this->filesize){//girare disugualianza

			$newfile = $this->createFileName($this->file);

			if (copy($this->file, $newfile)) {

    			file_put_contents($this->file,"");

			}
    	}
    	
	}

	private function createFileName($oldfile){

		$name=explode("/", $oldfile);
		$oldprefix="";
		$newname="";

		for($x=0;$x<(count($name)-1);$x++){ 
			$oldprefix.=$name[$x]."/";
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
		return $oldprefix.$newname;
	}


}

?>