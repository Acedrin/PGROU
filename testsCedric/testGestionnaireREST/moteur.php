<?php ;
	class Moteur {
		private $_message = "Hello World !";
		function getMessage() {
			return $this->_message;
		}
		function hello() {
			return $this->getMessage();
		}
	}
?>