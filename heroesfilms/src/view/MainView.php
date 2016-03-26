<?php

require_once("Router.php");
require_once("model/Film.php");
require_once("model/FilmBuilder.php");

class MainView {

	protected $router;
	protected $style;
	protected $title;
	protected $content;
	protected $menu;

	public function __construct(Router $router, $feedback, Menu $menu) {
		$this->feedback = $feedback;
		$this->router = $router;
		$this->style = array();
		$this->title = null;
		$this->content = null;
		$this->menu = $menu;
	}


	/******************************************************************************/
	/* Méthodes de génération des pages                                           */
	/******************************************************************************/

	public function makeHomePage() {
		$this->title = "HeroesMovies";
		$this->content = "Soon in theatres\nListe horizontal des prochains films";
		array_push($this->style, "navbar.css");
	}

	public function makeFilmPage($id, Film $f) {
		$fname = self::htmlesc($f->getName());
		$fclass = "film$id";
		$fdatec = self::htmlesc($f->getCreationDate());
		$fdatem = self::htmlesc($f->getModifDate());
		$length = strlen(self::htmlesc($f->getSynopsis()));

		$this->title = "$fname";
    $alt = preg_replace('/\s+/', '', $fname);
    $s = "<div class='film'>
					<div class='background' style='background-image:url(".self::htmlesc($f->getBackground()).")'>
						<h1>".self::htmlesc($f->getName())."</h1>
						<img src=".self::htmlesc($f->getPoster())." alt=".$alt."/>
					</div>
					<div class='filmInfos'>
    				<h4>Storyline :</h4><p>".self::htmlesc($f->getSynopsis())."</p><br>
    				<span>Director : </span> ".self::htmlesc($f->getRealisateur())."<br>
    				<span>Runtime :</span> ".self::htmlesc($f->getDuree())."min<br>
    				<span>Release date :</span> ".self::htmlesc($f->getDateSortie())."<br>
						<span>Cast :</span> ".self::htmlesc($f->getCasting())."<br>
						<span>Universe :</span> ".self::htmlesc($f->getUnivers())."<br><br>";
						if ($fdatem == '0000-00-00'){
							$s.= '<span>Page created :</span> '.$fdatec;
						} else {
							$s.= '<span>Last modification :</span> '.$fdatem;
						}
		$s.='</div>
  			</div>';
/*
		$s .= '<div class="vueFilm genre"> <span> Genre :</span> ';
		foreach($f->getGenre() as $genre){
			$s .= self::htmlesc($genre) . "  ";
		}
		$s .= ' ;
		$s .= "</div> <ul>\n";
		$s .= '<li><a href="'.$this->router->filmModifPage($id).'">Modifier</a></li>'."\n";
		$s .= '<li><a href="'.$this->router->filmDeletionPage($id).'">Supprimer</a></li>'."\n";
		$s .= "</ul>\n";*/
		$this->content = $s;

		array_push($this->style, "navbar.css");
		array_push($this->style, "film.css");
	}

	public function makeFilmCreationPage(FilmBuilder $builder) {
		$this->title = "Ajouter votre film";
		$s = '<form action="'.$this->router->saveCreatedFilm().'" method="POST" class="form-group">'."\n";
		$s .= self::getFormFields($builder);
		$s .= "<button>Créer</button>\n";
		$s .= "</form>\n";
		$this->content = $s;

		array_push($this->style, "cards.css");
		array_push($this->style, "navbar.css");
		array_push($this->style, "form.css");
	}

	public function makeFilmDeletionPage($id, Film $c) {
		$fname = self::htmlesc($c->getName());

		$this->title = "Suppression de la film $fname";
		$this->content = "<p>Le film « {$fname} » va être supprimée.</p>\n";
		$this->content .= '<form action="'.$this->router->confirmFilmDeletion($id).'" method="POST">'."\n";
		$this->content .= "<button>Confirmer</button>\n</form>\n";

		array_push($this->style, "navbar.css");
	}

	public function makeFilmDeletedPage() {
		$this->title = "Suppression effectuée";
		$this->content = "<p>La film a été correctement supprimée.</p>";

		array_push($this->style, "navbar.css");
	}

	public function makeFilmModifPage($id, FilmBuilder $builder) {
		$this->title = "Modifier le film";

		$this->content = '<form action="'.$this->router->updateModifiedFilm($id).'" method="POST">'."\n";
		$this->content .= self::getFormFields($builder);
		$this->content .= '<button>Modifier</button>'."\n";
		$this->content .= '</form>'."\n";

		array_push($this->style, "navbar.css");
	}

	public function makeGalleryPage(array $films) {
		$this->title = "Tous les films";
		$this->content = "<p>Click on a film for more details.</p>\n";
		foreach ($films as $id=>$f) {
			$this->content .= "<ul class=\"googleCardFilms\">\n<li><div class=\"cardFilms\">\n";
			$this->content .= $this->galleryFilm($id, $f);
			$this->content .= "<div>\n<li>\n</ul>\n";
		}

		array_push($this->style, "navbar.css");
		array_push($this->style, "cardsFilms.css");
	}

	public function makeUnknownFilmPage() {
		$this->title = "Erreur";
		$this->content = "La film demandée n'existe pas.";

		array_push($this->style, "navbar.css");
	}

	public function makeUnknownActionPage() {
		$this->title = "Erreur";
		$this->content = "La page demandée n'existe pas.";

		array_push($this->style, "navbar.css");
	}

	public function makeLoginPage() {
		$this->title = "Login";
		
		array_push($this->style, "form.css");
		array_push($this->style, "cards.css");
		array_push($this->style, "navbar.css");
		ob_start();
		include 'loginpage.php';
		$this->content = ob_get_clean(); 

		array_push($this->style, "navbar.css");
	}

	public function makeRegisterPage() {
		$this->title = "Login";
		
		array_push($this->style, "form.css");
		array_push($this->style, "cards.css");
		array_push($this->style, "navbar.css");
		ob_start();
		include 'registerForm.php';
		$this->content = ob_get_clean(); 

	}

	public function about(){
		$this->title = "About";
		array_push($this->style, "navbar.css");
		$this->content = "About blabla.";
	}

	public function makeLogoutPage() {
		$this->title = "Logout";
		ob_start();
		include 'logout.php';
		$this->content = ob_get_clean(); 
	}

	public function makeDeconnectedPage() {
		$this->title = "Disconnected";
		$this->content = "You have been disconnected.";
		array_push($this->style, "navbar.css");
	}

	/* Génère une page d'erreur inattendue. Peut optionnellement
	 * prendre l'exception qui a provoqué l'erreur
	 * en paramètre, mais n'en fait rien pour l'instant. */
	public function makeUnexpectedErrorPage(Exception $e=null) {
		$this->title = "Erreur";
		$this->content = "Une erreur inattendue s'est produite.";

		array_push($this->style, "navbar.css");
	}

	/******************************************************************************/
	/* Méthodes utilitaires                                                       */
	/******************************************************************************/

	protected function getMenu() {
		return $this->menu->getMenu();
	}

	protected function galleryFilm($id, $f) {
		$fclass = "film".$id;
		$res = '<p class="card-title">'.self::htmlesc($f->getName()).' ('.substr(self::htmlesc($f->getDateSortie()), 0, 4).')</p>';
		$res .= '<div class = "text"><br><h3 style="text-transform:uppercase">'.self::htmlesc($f->getName()).'</h3 style="text-transform:uppercase">Director<br>'.self::htmlesc($f->getRealisateur()).'<br><br>Cast<br>'.self::htmlesc($f->getCasting()).'<br><br>Release date<br>'.self::htmlesc($f->getDateSortie()).'<br><br>Universe<br>'.self::htmlesc($f->getUnivers()).'</div>';
		$res .= '<img src="'.self::htmlesc($f->getPoster()).'" class="posterSize" alt="'.self::htmlesc($f->getName()).' poster" />';
		$res .= '<p class="plus"><a href="'.$this->router->filmPage($id).'">More details</a></p>';
		$res .= '</a></li>'."\n";
		return $res;
	}

	protected function getFormFields(FilmBuilder $builder) {
		$nameRef = $builder->getNameRef();
		$s = "";
		$s .= '<p><label><span class="titrelabel">Nom du film : </span><input type="text" name="'.$nameRef.'" class="form-control" value="';
		//<input id="username" spellcheck=false class="form-control" name="username" type="email" size="20" alt="login" required="">
		$s .= self::htmlesc($builder->getData($nameRef)) . "\" />";
		$err = $builder->getErrors($nameRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$realRef = $builder->getRealisateurRef();
		$s .= '<p><label><span class="titrelabel">Realisateur : </span><input type="text" name="'.$realRef.'" value="';
		$s .= self::htmlesc($builder->getData($realRef));
		$s .= "\" />";
		$err = $builder->getErrors($realRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dateRef = $builder->getDateSortieRef();
		$s .= '<p><label><span class="titrelabel">Date de sortie : </span><input type="date" name="'.$dateRef.'" placeholder="AAAA-MM-JJ" value="';
		$s .= self::htmlesc($builder->getData($dateRef));
		$s .= "\" />";
		$err = $builder->getErrors($dateRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$poster = $builder->getPosterRef();
		$s .= '<p><label><span class="titrelabel">Lien URL de l\'affiche : </span><input type="date" name="'.$poster.'" value="';
		$s .= self::htmlesc($builder->getData($poster));
		$s .= "\" />";
		$err = $builder->getErrors($poster);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dureeRef = $builder->getDureeRef();
     	

		$s .= '<p><label><span class="titrelabel">Duree : </span><input type="date" name="'.$dureeRef.'" value="';
		$s .= self::htmlesc($builder->getData($dureeRef));
		$s .= "\" />";
		$err = $builder->getErrors($dureeRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$castRef = $builder->getCastingRef();
		
		//ajout du script js pour ajouter des champs acteurs
		ob_start();
		include 'addActorForm.php';
		$s .= ob_get_clean();

		$err = $builder->getErrors($castRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";


		$universRef = $builder->getUniversRef();
		$s .= '<p><span class="titrelabel"> Univers : </span><select name="'.$universRef.'">';
		$s .= '<option value="Marvel">Marvel</option>';
		$s .= '<option value="DC Comics">DC Comics</option>';
		$s .= '<option value="Autre">Autres</option>';
		$s .= '</select>';
		$s .= self::htmlesc($builder->getData($universRef));
		$err = $builder->getErrors($universRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$genreRef = $builder->getGenreRef();
		//$s .= '<p><label>Genre du film : <input type="date" name="'.$genreRef.'" value="' ;
		$s .= '<P><label><span class="titrelabel">Indiquez les genres : </span>';
		$s .= '	<LABEL ACCESSKEY=C><INPUT TYPE=checkbox name="'.$genreRef.'[]" VALUE="1" CHECKED> Action </LABEL>' ;
		$s .= '	<LABEL ACCESSKEY=D><INPUT TYPE=checkbox name="'.$genreRef.'[]" VALUE="2"> Aventure </LABEL>';
		$s .= '	<LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'[]" VALUE="3"> Comédie </LABEL>';
		$s .= ' <LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'[]" VALUE="4"> Sci-Fi </LABEL>';
		$s .= ' <LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'[]" VALUE="5"> Fantasy </LABEL>';
		$s .= '</label></P>';
		//$s .= $builder->getData($genreRef);
		$err = $builder->getErrors($genreRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";
		$synopsis = $builder->getSynopsisRef();
		$s .='<textarea name="' . $synopsis .'"rows="10" cols="50">Saisir le synopsis ici.</textarea>';
		$err = $builder->getErrors($synopsis);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .= "<br/>";
		return $s;
	}

	/* Une fonction pour échapper les caractères spéciaux de HTML,
	* car celle de PHP nécessite trop d'options. */
	public static function htmlesc($str) {
		return htmlspecialchars($str,
			/* on échappe guillemets _et_ apostrophes : */
			ENT_QUOTES
			/* les séquences UTF-8 invalides sont
			* remplacées par le caractère �
			* au lieu de renvoyer la chaîne vide…) */
			| ENT_SUBSTITUTE
			/* on utilise les entités HTML5 (en particulier &apos;) */
			| ENT_HTML5,
			'UTF-8');
	}

	/******************************************************************************/
	/* Rendu de la page                                                           */
	/******************************************************************************/

	public function render() {
		if ($this->title === null || $this->content === null) {
			$this->makeUnexpectedErrorPage();
		}

		include("squeletteView.php");

	}
}

