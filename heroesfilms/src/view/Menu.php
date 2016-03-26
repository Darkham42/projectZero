<?php

class Menu {
	
	protected $table;
	protected $router;
	
	public function __construct(Router $router){
	
		$this->router = $router;
		
		$this->table = array(
			"All Movies" => $this->router->allFilmsPage(),
			"DEBUGAjouter un film" => $this->router->filmCreationPage(),
			//"MARVEL" => $this->router->filmsMARVELPage(),
			//"DC" => $this->router->filmsDCPage(),
			//Page profile seulement si connecté
			//"Profile" => $this->router->profilePage(),
			//Page de connection
			"Log In" => $this->router->logIn(),
			"Register" => $this->router->register(),
			"Logout" => $this->router->logout(),
		);
		
	}
	
	public function getMenu(){
		return $this->table;
	}

	public function setMenuConnected($name){
		$this->table = array(
			"All Movies" => $this->router->allFilmsPage(),
			"MARVEL" => $this->router->filmsMARVELPage(),
			"DC" => $this->router->filmsDCPage(),
			"Logout " . $name  => $this->router->logout(),
			"About" => $this->router->about(),
		);
	}

	public function setMenuDisconnected(){
		$this->table = array(
			"All Movies" => $this->router->allFilmsPage(),
			"MARVEL" => $this->router->filmsMARVELPage(),
			"DC" => $this->router->filmsDCPage(),
			"Ajouter un film" => $this->router->filmCreationPage(),
			"Log In" => $this->router->logIn(),
			"Register" => $this->router->register(),
			"About" => $this->router->about(),
		);
	}
	
}


?>
