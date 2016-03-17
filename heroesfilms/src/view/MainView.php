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
		$this->style = "";
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
	}

	public function makeFilmPage($id, Film $f) {
		$cname = self::htmlesc($f->getName());
		$cclass = "film$id";
		$cdatec = self::fmtDate($f->getCreationDate());
		$cdatem = self::fmtDate($f->getModifDate());

		//$this->style .= ".$cclass { background-film: #$chex; }";
		$this->title = "Le film $cname";
		$s = "" ;
		$s .= '<h3>'.self::htmlesc($f->getName()).'</h3>';
		$s .= "<img src='".self::htmlesc($f->getPoster())."'>\n";
		$s .= '<div class="vueFilm realisateur"> <span class="labelChamp"> Réalisateur :</span> '.self::htmlesc($f->getRealisateur()).'</div>';
		$s .= '<div class="vueFilm duree"> <span class="labelChamp"> Duree :</span> '.self::htmlesc($f->getDuree()).'</div>';
		$s .= '<div class="vueFilm date_sortie"> <span class="labelChamp"> Date de sortie :</span> '.self::htmlesc($f->getDateSortie()).'</div>';
		$s .= '<div class="vueFilm casting"> <span class="labelChamp"> Casting :</span>'.self::htmlesc($f->getCasting()).'</div>';
		$s .= '<div class="vueFilm univers"> <span class="labelChamp"> Univers :</span> '.self::htmlesc($f->getUnivers()).'</div>';
		$s .= '<div class="vueFilm genre"> <span class="labelChamp"> Genre :</span> #ID (à modifier) '.self::htmlesc($f->getGenre()).'</div>';
		$s .= "<ul>\n";
		$s .= '<li><a href="'.$this->router->filmModifPage($id).'">Modifier</a></li>'."\n";
		$s .= '<li><a href="'.$this->router->filmDeletionPage($id).'">Supprimer</a></li>'."\n";
		$s .= "</ul>\n";
		$this->content = $s;
	}

	public function makeFilmCreationPage(FilmBuilder $builder) {
		$this->title = "Ajouter votre film";
		$s = '<form action="'.$this->router->saveCreatedFilm().'" method="POST">'."\n";
		$s .= self::getFormFields($builder);
		$s .= "<button>Créer</button>\n";
		$s .= "</form>\n";
		$this->content = $s;
	}

	public function makeFilmDeletionPage($id, Film $c) {
		$cname = self::htmlesc($c->getName());

		$this->title = "Suppression de la film $cname";
		$this->content = "<p>Le film « {$cname} » va être supprimée.</p>\n";
		$this->content .= '<form action="'.$this->router->confirmFilmDeletion($id).'" method="POST">'."\n";
		$this->content .= "<button>Confirmer</button>\n</form>\n";
	}

	public function makeFilmDeletedPage() {
		$this->title = "Suppression effectuée";
		$this->content = "<p>La film a été correctement supprimée.</p>";
	}

	public function makeFilmModifPage($id, FilmBuilder $builder) {
		$this->title = "Modifier le film";

		$this->content = '<form action="'.$this->router->updateModifiedFilm($id).'" method="POST">'."\n";
		$this->content .= self::getFormFields($builder);
		$this->content .= '<button>Modifier</button>'."\n";
		$this->content .= '</form>'."\n";
	}

	public function makeGalleryPage(array $films) {
		$this->title = "Tous les films";
		$this->content = "<p>Cliquer sur une film pour voir des détails.</p>\n";
		foreach ($films as $id=>$f) {
			$this->content .= "<ul class=\"googleCardFilms\">\n<li><div class=\"cardFilms\">\n";
			$this->content .= $this->galleryFilm($id, $f);
			$this->content .= "<div>\n<li>\n</ul>\n";
		}
	}

	public function makeUnknownFilmPage() {
		$this->title = "Erreur";
		$this->content = "La film demandée n'existe pas.";
	}

	public function makeUnknownActionPage() {
		$this->title = "Erreur";
		$this->content = "La page demandée n'existe pas.";
	}

	public function makeLoginPage() {
		$this->title = "Login";
		$this->content = '
			<div class="card">
        		<p class="card-title">Log In</p>
				<img src="http://s3.foxfilm.com/foxmovies/production/films/103/images/gallery/deadpool1-gallery-image.jpg" class="full" />
				<form action="" id="login-form">
					<div id="u" class="form-group">
					<input id="username" spellcheck=false class="form-control" name="username" type="email" size="20" alt="login" required="">
					<span class="form-highlight"></span>
					<span class="form-bar"></span>
					<label for="username" class="float-label">Email</label>
					</div>
					<div id="p" class="form-group">
						<input id="password" class="form-control" spellcheck=false name="password" type="password" size="20" alt="login" required="">
						<span class="form-highlight"></span>
						<span class="form-bar"></span>
						<label for="password" class="float-label">Password</label>
					</div>
					<div class="form-group">
						<button id="submit" type="submit" ripple>Sign in</button>
					</div>
				</form>
				<p class="url"><a href="#">Need new account ?</a></p>
				<p class="lock"><a href="#">Forgot Password ?</a></p>
      		</div>
		';
	}

	/* Génère une page d'erreur inattendue. Peut optionnellement
	 * prendre l'exception qui a provoqué l'erreur
	 * en paramètre, mais n'en fait rien pour l'instant. */
	public function makeUnexpectedErrorPage(Exception $e=null) {
		$this->title = "Erreur";
		$this->content = "Une erreur inattendue s'est produite.";
	}

	/******************************************************************************/
	/* Méthodes utilitaires                                                       */
	/******************************************************************************/

	protected function getMenu() {
		return $this->menu->getMenu();
	}

	protected function galleryFilm($id, $f) {
		$cclass = "film".$id;
		$res = '<p class="card-title">'.self::htmlesc($f->getName()).' ('.substr(self::htmlesc($f->getDateSortie()), 0, 4).')</p>';
		$res .= '<img src="'.self::htmlesc($f->getPoster()).'" class="posterSize" alt="'.self::htmlesc($f->getName()).' poster" />';
		$res .= '<p class="plus"><a href="'.$this->router->filmPage($id).'">More details</a></p>';
		$res .= '</a></li>'."\n";
		return $res;
	}

	protected function getFormFields(FilmBuilder $builder) {
		$nameRef = $builder->getNameRef();
		$s = "";
		$s .= '<p><label><span class="titrelabel">Nom du film : </span><input type="text" name="'.$nameRef.'" value="';
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
		

		ob_start();
		include 'addForm.php';
		$s .= ob_get_clean(); //note on ob_get_contents below
	
	/*
		$s .= '<div id="dynamicInput">
				<p><label><span class="titrelabel">Casting : </span>
					<input type="int" name="'.$dureeRef.'" placeholder="Acteur" value="' . self::htmlesc($builder->getData($castRef)) . "\" />
				</div>
				<input type='button' value='Add another text input' onClick=\"addInput('dynamicInput');\">";
	*/
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
		$s .= '	<LABEL ACCESSKEY=C><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="1" CHECKED> Action </LABEL>' ;
		$s .= '	<LABEL ACCESSKEY=D><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="2"> Aventure </LABEL>';
		$s .= '	<LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="3"> Comédie </LABEL>';
		$s .= ' <LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="4"> Sci-Fi </LABEL>';
		$s .= ' <LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="5"> Fantasy </LABEL>';
		$s .= '</label></P>';
		$s .= self::htmlesc($builder->getData($genreRef));
		$err = $builder->getErrors($genreRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		return $s;
	}

	protected static function fmtDate(DateTime $date) {
		return "le " . $date->format("Y-m-d") . " à " . $date->format("H:i:s");
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

