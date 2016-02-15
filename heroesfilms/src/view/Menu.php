<?php

class Menu {
	
	protected $table;
	protected $router;
	
	public function __construct(Router $router){
	
		$this->router = $router;
		
		$this->table = array(
			"Accueil" => $this->router->homePage(),
			"Films" => $this->router->allFilmsPage(),
			"Nouveau film" => $this->router->filmCreationPage(),
			"Mes films" => $this->router->mesFilmsPage(),
		);
		
	}
	
	public function getMenu(){
		return $this->table;
	}
	
}


?>
