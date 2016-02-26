<?php
	class RestServer {
		private $_class;
		private $_functions = [];
		public function setClass($class) {
			$this->_class = $class;
			$this->addFunction(get_class_methods($this->_class));
		}
		public function addFunction($function) {
			if (is_array($function)) {
				array_push($this->_functions,...$function);
				//$this->_functions = array_merge($this->_functions,$function);
			} else {
				array_push($this->_functions,$function);
			}
		}
		public function getFunctions() {
			return $this->_functions;
		}
		public function handle() {
			$URI = $_SERVER['REQUEST_URI'];
			$args = explode('/', trim($URI, '/'));
			$github = array_shift($args);
			$PGROU = array_shift($args);
			$folder = array_shift($args);
			$subfolder = array_shift($args);
			$function = array_shift($args);
			$params = $args;
			
			if (in_array($function,$this->_functions)) {
				if (in_array($function,get_class_methods($this->_class))) {
					$class = $this->_class;
					$instance = new $class();
					$answer = $instance->$function(...$params);
				} else {
					$answer = $function(...$params);
				}
				echo $answer;
			}
		}
	}
?>