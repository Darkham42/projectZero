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

/**
 * Page d'accueil
 */
	public function makeHomePage() {
		
		$this->title = "HeroesMovies";
		array_push($this->style, "navbar.css");

	}

/**
 * Page d'infos sur un film
 */
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
		$this->content = $s;
		$this->content .= "<a href='".$this->router->filmModifPage($id)."' id ='mod-film' class='fab-button'><img src='http://darkham.net/iconeModify.png' alt='M'/></a>";
		$this->content .= "<a href='".$this->router->filmDeletionPage($id)."' id ='del-film' class='fab-button'><img src='http://darkham.net/iconeDelete.png' alt='^'/></a>";

		array_push($this->style, "navbar.css");
		array_push($this->style, "film.css");
		if($f->getUnivers() == "Marvel"){
			array_push($this->style, "marvel.css");
		} else {
			array_push($this->style, "dc.css");
		}
		array_push($this->style, "fab.css");
	}

/**
 * Page d'ajout d'un film
 */
	public function makeFilmCreationPage(FilmBuilder $builder) {
		$this->title = "Add a film";
		$s = '<form action="'.$this->router->saveCreatedFilm().'" method="POST" class="form-group">'."\n";
		$s .= self::getFormFields($builder);
		$s .= "<div class='form-group'>\n<button type='submit'>Créer</button>\n</div>\n";
		$s .= "</form>\n<br><br>\n";
		$this->content = $s;

		array_push($this->style, "cards.css");
		array_push($this->style, "navbar.css");
		array_push($this->style, "form.css");
	}

/**
 * Page de confirmation de suppression d'un film
 */
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

		array_push($this->style, "cards.css");
		array_push($this->style, "navbar.css");
		array_push($this->style, "form.css");
	}

	public function makeGalleryPage(array $films, $genre) {
		$this->title = "Tous les films";
		foreach ($films as $id=>$f) {
			$this->content .= "<ul class=\"listCardFilms\">\n<li><div class=\"cardFilms\">\n";
			$this->content .= $this->galleryFilm($f->getId(), $f);
			$this->content .= "</div>\n</li>\n</ul>\n";
		}
		$this->content .= "<a href='".$this->router->filmCreationPage()."' id ='add-film' class='fab-button'><img src='http://darkham.net/iconeAdd.png' alt='+'/></a>";
		$this->content .= "<a href='#top' id ='top' class='fab-button'><img src='http://darkham.net/iconeUp.png' alt='^'/></a>";
		array_push($this->style, "navbar.css");
		array_push($this->style, "cardsFilms.css");
		switch ( $genre ){
			case "marvel" : array_push($this->style, "marvel.css"); break;
			case "dc" : array_push($this->style, "dc.css"); break;
			default : break;
		}
		array_push($this->style, "fab.css");

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
		$res .= '<div class = "text"><br><h3 style="text-transform:uppercase">'.self::htmlesc($f->getName()).'</h3>Director<br>'.self::htmlesc($f->getRealisateur()).'<br><br>Cast<br>'.self::htmlesc($f->getCasting()).'<br><br>Release date<br>'.self::htmlesc($f->getDateSortie()).'<br><br>Universe<br>'.self::htmlesc($f->getUnivers()).'</div>';
		$res .= '<img src="'.self::htmlesc($f->getPoster()).'" class="posterSize" alt="'.self::htmlesc($f->getName()).' poster" />';
		$res .= '<p class="plus"><a href="'.$this->router->filmPage($id).'">More details</a></p>';
		return $res;
	}

	protected function getFormFields(FilmBuilder $builder) {
		$nameRef = $builder->getNameRef();
		$s = "";
		$s .= '<div class="card">
						<div class="form-group">
						<input id="title" spellcheck=false class="form-control" name="'.$nameRef.'" type="text" alt="film" required="">
						<span class="form-highlight"></span>
						<span class="form-bar"></span>
						<label for="title" class="float-label">Title
					';
		$s .= self::htmlesc($builder->getData($nameRef));
		$err = $builder->getErrors($nameRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></div>\n";

		$realRef = $builder->getRealisateurRef();
		$s .= '	<div class="form-group">
						<input id="director" spellcheck=false class="form-control" name="'.$realRef.'" type="text" alt="film" required="">
						<span class="form-highlight"></span>
						<span class="form-bar"></span>
						<label for="director" class="float-label">Director
					';
		$s .= self::htmlesc($builder->getData($realRef));
		$err = $builder->getErrors($realRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></div>\n";

		$dateRef = $builder->getDateSortieRef();
		$s .= '	<div class="form-group">
						<input id="date" spellcheck=false class="form-control" name="'.$dateRef.'" type="date" alt="film" required="">
						<span class="form-highlight"></span>
						<span class="form-bar"></span>
						<label for="date" class="float-label">Release date (YYYY-MM-DD)
					';
		$s .= self::htmlesc($builder->getData($dateRef));
		$err = $builder->getErrors($dateRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></div>\n";

		$poster = $builder->getPosterRef();
		$s .= '<p><label><span class="titrelabel">Lien URL de l\'affiche : </span><input type="date" name="'.$poster.'" value="';
		$s .= self::htmlesc($builder->getData($poster));
		$s .= "\" />";
		$err = $builder->getErrors($poster);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dureeRef = $builder->getDureeRef();
     	
		$s .= '	<div class="form-group">
						<input id="runtime" spellcheck=false class="form-control" name="'.$dureeRef.'" type="date" alt="film" required="">
						<span class="form-highlight"></span>
						<span class="form-bar"></span>
						<label for="runtime" class="float-label">Runtime (in minutes)
					';
		$s .= self::htmlesc($builder->getData($dureeRef));
		$err = $builder->getErrors($dureeRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></div>\n";

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
		$s .= '	<div class="form-group">
						<span>Universe : </span><select name="'.$universRef.'">
						<option value="Marvel">Marvel</option>
						<option value="DC Comics">DC Comics</option>
						</select>
					';
		$s .= self::htmlesc($builder->getData($universRef));
		$err = $builder->getErrors($universRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</div>\n";

		$genreRef = $builder->getGenreRef();
		$s .= '<div class="form-group">';
		$s .= '<span>Genres : </span><ul>';
		$s .= ' <li><label acceskey=C><input type=checkbox name="'.$genreRef.'[]" value="1" checked> Action </label></li>';
		$s .= '	<li><label acceskey=D><input type=checkbox name="'.$genreRef.'[]" value="2"> Aventure </label></li>';
		$s .= '</ul><ul>';
		$s .= '	<li><label acceskey=M><input type=checkbox name="'.$genreRef.'[]" value="3"> Comédie </label></li>';
		$s .= ' <li><label acceskey=M><input type=checkbox name="'.$genreRef.'[]" value="4"> Sci-Fi </label></li>';
		$s .= ' <li><label acceskey=M><input type=checkbox name="'.$genreRef.'[]" value="5"> Fantasy </label></li>';
		$s .= '</ul>';
		$err = $builder->getErrors($genreRef);
		if ($err !== null)
			$s .= '<span class="error">'.$err.'</span>';
		$s .="</label></div>\n";


		$synopsis = $builder->getSynopsisRef();
		$s .= '	<br><div class="form-group">
						<label class"label-textarea" for="storyline">Storyline</label>
						<span class="form-highlight"></span>
						<textarea spellcheck=false class="form-control-textarea" name="'.$synopsis.'" maxlength="500" alt="film" required=""></textarea>
					';
		$err = $builder->getErrors($synopsis);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .= "</div>";
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