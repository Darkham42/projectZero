<?php

class Menu {
	
	protected $table;
	protected $router;
	
	public function __construct(Router $router){
	
		$this->router = $router;
		
		$this->table = array(
			"Movies" => $this->router->allFilmsPage(),
			"Ajouter un film" => $this->router->filmCreationPage(),
			
			//"MARVEL" => $this->router->filmsMARVELPage(),
			//"DC" => $this->router->filmsDCPage(),
			//Page profile seulement si connecté
			//"Profile" => $this->router->profilePage(),
			//Page de connection
			//"Log In" => $this->router->logIn(),
			//sinon il faudrait afficher juste un lien de logout
		);
		
	}
	
	public function getMenu(){
		return $this->table;
	}
	
}


?>
