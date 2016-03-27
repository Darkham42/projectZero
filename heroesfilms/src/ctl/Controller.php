<?php

/*** Contrôleur du site des films. ***/

/* Inclusion des classes nécessaires */
require_once("model/Film.php");
require_once("model/FilmStorage.php");
require_once("model/FilmBuilder.php");
require_once("view/MainView.php");


class Controller {

	protected $v;
	protected $filmdb;

	public function __construct(Router $router, MainView $view, FilmStorage $filmdb) {
		$this->router = $router;
		$this->v = $view;
		$this->filmdb = $filmdb;
	}

	public function filmPage($id) {
		/* Une film est demandée, on la récupère en BD */
		$film = $this->filmdb->read($id);
		if ($film === null) {
			/* La film n'existe pas en BD */
			$this->v->makeUnknownFilmPage();
		} else {
			/* La film existe, on prépare la page */
			$this->v->makeFilmPage($id, $film);
		}
	}

	public function allFilmsPage() {
		$films = $this->filmdb->readAll();
		$this->v->makeGalleryPage($films, null);
	}

	public function allMarvelPage() {
		$films = $this->filmdb->readAllMarvel();
		$this->v->makeGalleryPage($films, "marvel");
	}

	public function allDCPage() {
		$films = $this->filmdb->readAllDC();
		$this->v->makeGalleryPage($films, "dc");
	}

	public function about() {
		$this->v->about();
	}

	public function myFilmsPage($user) {
		$films = $this->filmdb->readMy($user);
		$this->v->makeGalleryPage($films);
	}

	public function newFilm() {
		/* Affichage du formulaire de création
		* avec les données par défaut. */
		if (isset($_SESSION['currentFilmBuilder'])) {
			$builder = $_SESSION['currentFilmBuilder'];
		} else {
			$builder = new FilmBuilder();
		}
		$this->v->makeFilmCreationPage($builder);
	}

	public function saveNewFilm(array $data) {
		$builder = new FilmBuilder($data);
		if ($builder->isValid()) {
			echo "builder ok" ;
			/* On construit la nouvelle film */
			$film = $builder->createFilm();
			var_dump($film);
			/* On l'ajoute en BD */
			$filmId = $this->filmdb->create($film);
			/* On prépare la page de la nouvelle film */
			//$this->v->makeFilmPage($filmId, $film);
			unset($_SESSION['currentFilmBuilder']);
			$_SESSION["feedback"] = "Film create with success.";
			$this->router->POSTredirect($this->router->filmPage($filmId));
		} else {
			//$this->v->makeFilmCreationPage($builder);
			$_SESSION["feedback"] = "Error in the form.";
			$_SESSION['currentFilmBuilder'] = $builder;
			$this->router->POSTredirect($this->router->filmCreationPage());
		}
	}

	public function deleteFilm($filmId) {
		/* On récupère la film en BD */
		$film = $this->filmdb->read($filmId);
		if ($film === null) {
			/* La film n'existe pas en BD */
			$this->v->makeUnknownFilmPage();
		} else {
			/* La film existe, on prépare la page */
			$this->v->makeFilmDeletionPage($filmId, $film);
		}
	}

	public function confirmFilmDeletion($filmId) {
		/* L'utilisateur confirme vouloir supprimer
		* la film. On essaie. */
		$ok = $this->filmdb->delete($filmId);
		if (!$ok) {
			/* La film n'existe pas en BD */
			$this->v->makeUnknownFilmPage();
		} else {
			/* Tout s'est bien passé */
			//$this->v->makeFilmDeletedPage();
			$_SESSION["feedback"] = "Film exterminate.";
			$this->router->POSTredirect($this->router->allFilmsPage());
		}
	}

	public function modifyFilm($filmId) {
		if (isset($_SESSION['modifiedFilmBuilders'][$filmId])) {
			$builder = $_SESSION['modifiedFilmBuilders'][$filmId];
			/* Préparation de la page de formulaire */
			$this->v->makeFilmModifPage($filmId, $builder);
		} else {
			/* On récupère en BD la film à modifier */
			$f = $this->filmdb->read($filmId);
			if ($f === null) {
				$this->v->makeUnknownFilmPage();
			} else {
				/* Extraction des données modifiables */
				$builder = FilmBuilder::buildFromFilm($f);
				/* Préparation de la page de formulaire */
				$this->v->makeFilmModifPage($filmId, $builder);
			}
		}
	}

	public function saveFilmModifications($filmId, array $data) {
		/* On récupère en BD la film à modifier */
		$film = $this->filmdb->read($filmId);
		if ($film === null) {
			/* La film n'existe pas en BD */
			$this->v->makeUnknownFilmPage();
		} else {
			$builder = new FilmBuilder($data);
			/* Validation des données */
			if ($builder->isValid()) {
				/* Modification de la film */
				$builder->updateFilm($film);
				/* On essaie de mettre à jour en BD.
				* Normalement ça devrait marcher (on vient de
				* récupérer la film). */
				$ok = $this->filmdb->update($filmId, $film);
				if (!$ok)
					throw new Exception("Identifier has disappeared?!");
				//$this->v->makeFilmPage($filmId, $film);
				/* Redirection vers la page de la film */
				$_SESSION["feedback"] = "Film modified, thanks.";
				unset($_SESSION["modifiedFilmBuilders"][$filmId]);
				$this->router->POSTredirect($this->router->filmPage($filmId));
			} else {
				//$this->v->makeFilmModifPage($filmId, $builder);
				$_SESSION['modifiedFilmBuilders'][$filmId] = $builder;
				$_SESSION["feedback"] = "Error in form.";
				$this->router->POSTredirect($this->router->filmModifPage($filmId));
			}
		}
	}
}

?>
