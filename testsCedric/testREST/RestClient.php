<?php
	class RestClient {
		public function __call($function, $args)
		{
			return file_get_contents("http://localhost/github/PGROU/testsCedric/testREST/".$function."/".implode('/',$args));
		}
	}
?>