<?php 
	require "moteur.php";
	
	class Gestionnaire {
		private $_root;
		private $_moteur;
		public function Gestionnaire($root) {
			$this->_root = $root;
			$this->_moteur = new Moteur();
		}
		public function getRoute() {
			$method = $_SERVER["REQUEST_METHOD"];
			$URI = $_SERVER["REQUEST_URI"];
			
			$call = end(explode($this->_root,$URI));
			$route = explode('/', trim($call, '/'));
			$function = array_shift($route);
			$parameters = $route;
			$names = array_filter($parameters, function ($key) {return $key%2==0;});
			$values = array_filter($parameters, function ($key) {return $key%2==1;});
			$arguments = array_combine($names,$values);
			
			return [$method,$function,$arguments];
		}
		public function getAnswer() {
			list($method,$function,$arguments) = $this->getRoute();
			
			if ($method == "GET") {
				$answer = $this->_moteur->$function(...array_values($arguments));
			} else {
				$answer = "ERROR !";
			}
			
			return $answer;
		}
	}
?>