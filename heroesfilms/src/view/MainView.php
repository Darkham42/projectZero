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
		$this->title = "Proposez vos films !";
		$this->content = "Bienvenue sur ce site de partage de films.";
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
			$this->content .= "<ul class=\"googleCard\">\n<li><div class=\"card\">\n";
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
		$res .= '<img src="'.self::htmlesc($f->getPoster()).'" class="full" />';
		$res .= '<p class="plus"><a href="'.$this->router->filmPage($id).'">More details</a></p>';
		$res .= '</a></li>'."\n";
		return $res;
	}

	protected function getFormFields(FilmBuilder $builder) {
		$nameRef = $builder->getNameRef();
		$s = "";

		$s .= '<p><label>Nom de la film : <input type="text" name="'.$nameRef.'" value="';
		$s .= self::htmlesc($builder->getData($nameRef));
		$s .= "\" />";
		$err = $builder->getErrors($nameRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$realRef = $builder->getRealisateurRef();
		$s .= '<p><label>Realisateur : <input type="text" name="'.$realRef.'" value="';
		$s .= self::htmlesc($builder->getData($realRef));
		$s .= '" ';
		$err = $builder->getErrors($realRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dateRef = $builder->getDateSortieRef();
		$s .= '<p><label>Date de sortie : <input type="date" name="'.$dateRef.'" value="';
		$s .= self::htmlesc($builder->getData($dateRef));
		$s .= '" ';
		$err = $builder->getErrors($dateRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dureeRef = $builder->getDureeRef();
		$s .= '<p><label>Duree : <input type="int" name="'.$dureeRef.'" value="';
		$s .= self::htmlesc($builder->getData($dureeRef));
		$s .= '" ';
		$err = $builder->getErrors($dureeRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$castRef = $builder->getCastingRef();
		$s .= '<p><label>Casting : <input type="date" name="'.$castRef.'" value="';
		$s .= self::htmlesc($builder->getData($castRef));
		$s .= '" ';
		$err = $builder->getErrors($castRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";


		$universRef = $builder->getUniversRef();
		$s .= '<p> Univers : <select name="'.$universRef.'">';
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
		$s .= '<P><label>Indiquez les genres : ';
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

