<?php

require_once("model/FilmStorage.php");
require_once("view/MainView.php");
require_once("view/Menu.php");
require_once("ctl/Controller.php");


class Router {

	public function __construct(FilmStorage $filmdb) {
		$this->filmdb = $filmdb;
	}

	public function main() {
		session_start();

		$feedback = isset($_SESSION['feedback'])? $_SESSION['feedback']: '';
		$_SESSION['feedback'] = '';

		$user = isset($_SESSION['user'])? $_SESSION['user']: '';
		// ici, ce sera un objet User
		
		$menu = new Menu($this);
		$view = new MainView($this, $feedback, $menu);
		$ctl = new Controller($this, $view, $this->filmdb);

		/* Analyse de l'URL */
		$filmId = isset($_GET['film'])? $_GET['film']: null;
		$action = isset($_GET['action'])? $_GET['action']: null;
		if ($action === null) {
			/* Pas d'action demandée : par défaut on affiche
	 	 	 * la page d'accueil, sauf si une film est demandée,
	 	 	 * auquel cas on affiche sa page. */
			$action = ($filmId === null)? "accueil": "voir";
		}

		try {
			switch ($action) {
			case "voir":
				if ($filmId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->filmPage($filmId);
				}
				break;

			case "creerFilm":
				$ctl->newFilm();
				break;

			case "sauverNouveauFilm":
				echo "sauver !";
				$filmId = $ctl->saveNewFilm($_POST);
				break;

			case "supprimer":
				if ($filmId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->deleteFilm($filmId);
				}
				break;

			case "confirmerSuppression":
				if ($filmId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->confirmFilmDeletion($filmId);
				}
				break;

			case "modifier":
				if ($filmId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->modifyFilm($filmId);
				}
				break;

			case "sauverModifs":
				if ($filmId === null) {
					$view->makeUnknownActionPage();
				} else {
					$ctl->saveFilmModifications($filmId, $_POST);
				}
				break;

			case "galerie":
				$ctl->allFilmsPage();
				break;

			case "galeriePerso":
				$ctl->myFilmsPage($user);
				break;

			case "accueil":
				$view->makeHomePage();
				break;

			case "login":
				$view->makeLoginPage();
				break;

			default:
				/* L'internaute a demandé une action non prévue. */
				$view->makeUnknownActionPage();
				break;
			}
		} catch (Exception $e) {
			/* Si on arrive ici, il s'est passé quelque chose d'imprévu
	 	 	 * (par exemple un problème de base de données) */
			$view->makeUnexpectedErrorPage($e);
		}

		/* Enfin, on affiche la page préparée */
		$view->render();
	}

	/* URL de la page d'accueil */
	public function homePage() {
		return ".";
	}

	/* URL de la page de log in */
	public function logIn() {
		return ".?action=login";
	}

	/* URL de la page de la film d'identifiant $id */
	public function filmPage($id) {
		return ".?film=$id";
	}

	/* URL de la page avec toutes les films */
	public function allFilmsPage() {
		return ".?action=galerie";
	}

	/* URL de la page de création d'une film */
	public function filmCreationPage() {
		return ".?action=creerFilm";
	}

	/* URL de la page de création d'une film */
	public function mesFilmsPage() {
		return ".?action=galeriePerso";
	}

	/* URL d'enregistrement d'une nouvelle film
	 * (champ 'action' du formulaire) */
	public function saveCreatedFilm() {
		return ".?action=sauverNouveauFilm";
	}

	/* URL de la page d'édition d'une film existante */
	public function filmModifPage($id) {
		return ".?film=$id&amp;action=modifier";
	}
	
	/* URL d'enregistrement des modifications sur une
	 * film (champ 'action' du formulaire) */
	public function updateModifiedFilm($id) {
		return ".?film=$id&amp;action=sauverModifs";
	}

	/* URL de la page demandant la confirmation
	 * de la suppression d'une film */
	public function filmDeletionPage($id) {
		return ".?film=$id&amp;action=supprimer";
	}

	/* URL de suppression effective d'une film
	 * (champ 'action' du formulaire) */
	public function confirmFilmDeletion($id) {
		return ".?film=$id&amp;action=confirmerSuppression";
	}

	/* URL d'un élément statique de présentation */
	public function skinFile($filename) {
		return $this->staticBase."/skin/".$filename;
	}


	/* Fonction pour le POST-redirect-GET,
 	 * destinée à prendre des URL du routeur
 	 * (dont il faut décoder les entités HTML) */
	public function POSTredirect($url) {
		header("Location: ".htmlspecialchars_decode($url), true, 303);
		die;
	}

}

?>
