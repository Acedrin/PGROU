<?php

/*@TODO control extension and change prova.txt in prova_1.txt
     
*/

class LogRotate{


private $file;
private $filesize;
private $variables_file; // file .ini for persistent variables
private $n=2;

	public function __construct($file){

		include './settings/settings.php';

		$this->file=$file;
		$this->filesize=$max_filesize*1048576;
		$this->variable_file=$variables;
	}


	public function checkFile(){


    	if(filesize($this->file) < $this->filesize){

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

		for(x=0;x<(count($name)-1);x++){ 
			$oldprefix.=$name[x]."/";
			} 

		$oldfile=$name[(count($name)-1)];
		$name=explode(".", $oldfile);

		echo count($name[0]);

		if(count(name)>1){
			// for(i=0, i<count($name),i++){

			// 	if((i==count($name)-2)) $newname.=name[i]."_".$this->n;
			// 	else  $newname.=$name[i].".";
		}else{
			$newname.=$name[0]."_".$this->n;
		}
		echo $oldprefix.$newname;
	}


}

?>