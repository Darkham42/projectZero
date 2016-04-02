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
		
		$this->title = "HeroesFilms";
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
						if ($fdatem == '1970-01-01'){
							$s.= '<span>Page created :</span> '.$fdatec;
						} else {
							$s.= '<span>Last modification :</span> '.$fdatem;
						}
		$s.='</div>
  			</div>';
		$this->content = $s;

		if (isset($_SESSION['id']) && ($_SESSION['id'] === $f->getUserId())){
			$this->content .= "<a href='".$this->router->filmModifPage($id)."' id ='mod-film' class='fab-button'><img src='assets/icones/iconeModify.png' alt='M'/></a>";
			$this->content .= "<a href='".$this->router->filmDeletionPage($id)."' id ='del-film' class='fab-button'><img src='assets/icones/iconeDelete.png' alt='^'/></a>";
		}

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
		$s .= "<div class='form-group'>\n<button type='submit'>Create</button>\n</div>\n";
		$s .= "<br><br></div></form>\n";
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

		$this->title = "Delete a film";
		$this->content = "<div class='card'>
											<p class='card-title'>Delete {$fname}</p>
											<img src='assets/delete.jpg' class='full' />
											<form method='POST' action=".$this->router->confirmFilmDeletion($id).">
											<p>{$fname} will be erased from the database, do you valide this ?</p>
												<div class='form-group'>
													<button type='submit' name='submit'>EX-TER-MIN-ATE</button>
												</div><br>
											</form>
											</div>";

		array_push($this->style, "navbar.css");
		array_push($this->style, "cards.css");
		array_push($this->style, "form.css");
	}

	public function makeFilmDeletedPage() {
		$this->title = "Movie deleted.";
		$this->content = "<p>The movie has been correctly deleted : EX-TER-MI-NA-TED.</p>";

		array_push($this->style, "navbar.css");
	}

	public function makeFilmModifPage($id, FilmBuilder $builder) {
		$this->title = "Modify movie";

		$this->content = '<form action="'.$this->router->updateModifiedFilm($id).'" method="POST">'."\n";
		$this->content .= self::getFormFields($builder);
		$this->content .= '<button>Modifier</button>'."\n";
		$this->content .= '</form>'."\n";

		array_push($this->style, "cards.css");
		array_push($this->style, "navbar.css");
		array_push($this->style, "form.css");
	}

	public function makeGalleryPage(array $films, $genre) {
		$this->title = "HeroesFilms";
		if(sizeof($films) == 0){
			$this->content .= "This listing is empty. Fill it ! ;)";
		}
		foreach ($films as $id=>$f) {
			$this->content .= "<ul class=\"listCardFilms\">\n<li><div class=\"cardFilms\">\n";
			$this->content .= $this->galleryFilm($f->getId(), $f);
			$this->content .= "</div>\n</li>\n</ul>\n";
		}
		if (isset($_SESSION['id'])){
			$this->content .= "<a href='".$this->router->filmCreationPage()."' id ='add-film' class='fab-button'><img src='assets/icones/iconeAdd.png' alt='+'/></a>";
		}
		
		$this->content .= "<a href='#top' id ='topFAB' class='fab-button'><img src='assets/icones/iconeUp.png' alt='^'/></a>";
		array_push($this->style, "navbar.css");
		array_push($this->style, "cardsFilms.css");
		switch ( $genre ){
			case "marvel" : array_push($this->style, "marvel.css"); break;
			case "dc" : array_push($this->style, "dc.css"); break;
			default : break;
		}


	}

	public function makeUnknownFilmPage() {
		$this->title = "Error";
		$this->content = "The movie doesn't exist. 404 not found.";

		array_push($this->style, "navbar.css");
	}

	public function makeUnknownActionPage() {
		$this->title = "Error";
		$this->content = "The page doesn't exist. What do you want to see ?! ";

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
		$this->title = "Register";
		
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
		$this->content = "HeroesFilms is developed by agent 21102326 and agent 21303622";
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
		$this->content = "An unexpected error has been detected.";

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
						<input id="title" spellcheck=false class="form-control" name="'.$nameRef.'" type="text" required="" value = "'.$builder->getData($nameRef).'">
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
						<input id="director" spellcheck=false class="form-control" name="'.$realRef.'" type="text" required=""value = "'.$builder->getData($realRef).'">
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
						<input id="date" spellcheck=false class="form-control" name="'.$dateRef.'" type="date" required="" value = "'.$builder->getData($dateRef).'">
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
		$s .= ' <div class="form-group">
						<input id="poster" spellcheck=false class="form-control" name="'.$poster.'" type="number" maxlength="6" placeholder="209112">
						<span class="form-highlight"></span>
						<span class="form-bar"></span>
						<label for="poster" class="float-label">Poster (ref from <a href="https://www.themoviedb.org/movie" target="_blank">TMDb</a>)
					';
		//$s .= self::htmlesc($builder->getData($poster));
		$err = $builder->getErrors($poster);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";


		$dureeRef = $builder->getDureeRef(); 	
		$s .= '	<div class="form-group">
						<input id="runtime" spellcheck=false class="form-control" name="'.$dureeRef.'" type="number" min="90" max="240" required="" value = "'.$builder->getData($dureeRef).'">
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

		$universRef = $builder->getUniversRef();
		$s .= '	<div class="form-group">
						<span>Universe : </span><select name="'.$universRef.'" selected = "'.$builder->getData($nameRef).'">
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
		$s .= ' <li><label><input type=checkbox name="'.$genreRef.'[]" value="1"> Action </label></li>';
		$s .= '	<li><label><input type=checkbox name="'.$genreRef.'[]" value="2"> Adventure </label></li>';
		$s .= '</ul><ul>';
		$s .= '	<li><label><input type=checkbox name="'.$genreRef.'[]" value="3" checked> Comedy </label></li>';
		$s .= ' <li><label><input type=checkbox name="'.$genreRef.'[]" value="4"> Sci-Fi </label></li>';
		$s .= ' <li><label><input type=checkbox name="'.$genreRef.'[]" value="5"> Fantasy </label></li>';
		$s .= '</ul>';
		$err = $builder->getErrors($genreRef);
		if ($err !== null)
			$s .= '<span class="error">'.$err.'</span>';
		$s .="</label></div>\n";


		$synopsis = $builder->getSynopsisRef();
		$s .= '	<br><div class="form-group">
						<label for="storyline">Storyline</label>
						<span class="form-highlight"></span>
						<textarea spellcheck=false class="form-control-textarea" name="'.$synopsis.'" maxlength="500" required="">'.$builder->getData($synopsis).'</textarea>
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
		if(!isset($_SESSION["vu"])){
			$this->content  .= '
			<input type="checkbox" id="checkbox" name="checkbox" value="checkbox" checked="checked" style ="display:none;">
			<div id="topbar">By closing this message, you consent to our cookies on this device in accordance with our cookie policy unless you have disabled them.
			 <label for="checkbox" id="hideTop" title="Close"">x</label>
			</div>';
			array_push($this->style, "notifCookie.css");

		} 
		$_SESSION["vu"] = "ok";
		
		array_push($this->style, "fab.css");
		include("squeletteView.php");

	}
}