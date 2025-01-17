<?php

require_once("model/Film.php");

/* Fonctions de manipulation des films via des formulaires */
class FilmBuilder {

	protected $data;
	protected $errors;

	/* Crée une nouvelle instance, avec les données passées en argument si
 	 * elles existent, et sinon avec
 	 * les valeurs par défaut des champs de création d'un film. */
	public function __construct($data=null) {
		if ($data === null) {
			$data = array(
				"name" => "",
				"date_sortie" => "",
				"duree" => "",
				"poster" => "",
				"background" => "",
				"synopsis" => "",
				"realisateur" => "",
				"casting" => "",
				"univers" => "",
				"genre" => "",
			);
		}
		$this->data = $data;
		$this->errors = array();
	}

	/* Renvoie une nouvelle instance de FilmBuilder avec les données
 	 * modifiables de la film passée en argument. */
	public static function buildFromFilm(Film $film) {
		return new FilmBuilder(array(
			"name" => $film->getName(),
			"date_sortie" => $film->getDateSortie(),
			"synopsis" => $film->getSynopsis(),
			"duree" => $film->getDuree(),
			"poster" => $film->getPoster(),
			"background" => $film->getBackground(),
			"realisateur" => $film->getRealisateur(),
			"casting" => $film->getCasting(),
			"univers" => $film->getUnivers(),
			"genre" => $film->getGenre(),
		));
	}

	/* Vérifie la validité des données envoyées par le client,
	 * et renvoie un tableau des erreurs à corriger. */
	public function isValid() {
		$this->errors = array();
		
		if (!isset($this->data["name"]) || $this->data["name"] === "")
			$this->errors["name"] = "Add a name";
		else if (!isset($this->data["date_sortie"]) )					//&& isValidDate($this->data["name"])
			$this->errors["date_sortie"] = "Add a date";
		else if (!isset($this->data["realisateur"]) || $this->data["realisateur"] === "")
			$this->errors["realisateur"] = "Add a director";
		else if (!isset($this->data["univers"]) || $this->data["univers"] === "")
			$this->errors["univers"] = "Add a universe";
		else if (!isset($this->data["synopsis"]) || $this->data["synopsis"] === "")
			$this->errors["synopsis"] = "Add some words about the movie";
		else if (!isset($this->data["genre"]) || $this->data["genre"] === "")
			$this->errors["genre"] = "Chek a genre";
		else if (mb_strlen($this->data["name"], 'UTF-8') >= 30)
			$this->errors["name"] = "Too many characters, 30 max";
		
		return count($this->errors) === 0;
	}

	public function getNameRef() {
		return "name";
	}

	public function getDureeRef() {
		return "duree";
	}

	public function getRealisateurRef() {
		return "realisateur";
	}

	public function getDateSortieRef() {
		return "date_sortie";
	}

	public function getGenreRef() {
		return "genre";
	}

	public function getUniversRef() {
		return "univers";
	}

	public function getCastingRef() {
		return "casting";
	}

	public function getPosterRef() {
		return "poster";
	}

	public function getSynopsisRef(){
		return "synopsis";
	}

	/* Renvoie la valeur d'un champ en fonction de la référence passée en argument. */
	public function getData($ref) {
		//echo "\n ajout : " . $ref ;
		return isset($this->data[$ref])? $this->data[$ref]: '';
	}

	/* Renvoie les erreurs associées au champ de la référence passée en argument,
 	 * ou null s'il n'y a pas d'erreur.
 	 * Nécessite d'avoir appelé isValid() auparavant. */
	public function getErrors($ref) {
		return isset($this->errors[$ref])? $this->errors[$ref]: null;
	}

	/* Crée une nouvelle instance de Film avec les données
	 * fournies. Si toutes ne sont pas présentes, une exception
	 * est lancée. */
	public function createFilm() {
		echo '<br/> DATA : ';
		var_dump($this->data);
		echo '<br/> ERRORS : ';
		var_dump($this->errors);
		echo '<br/>';
		
		if(!isset($this->data["name"])){
			echo "\n erreur nom";
			sayError("Erreur nom");
		}
		if(!isset($this->data["date_sortie"])){
			echo "\n erreur date";
		}
		if(!isset($this->data["duree"])){
			echo "\n erreur duree";
		}
		if(!isset($this->data["realisateur"])){
			echo "\n erreur realisateur";
		}
		if(!isset($this->data["casting"])){
			echo "\n erreur casting";
			sayError("Erreur casting");
		}
		if(!isset($this->data["genre"])){
			echo "\n erreur genre";
		}
		if(!isset($this->data["univers"])){
			echo "\n erreur univers";
		}
		if(!isset($this->data["poster"])){
			echo "\n erreur poster";
		}
		if(!isset($this->data["synopsis"])){
			echo "\n erreur synopsis";
		}
		
		if (!isset($this->data["name"], $this->data["date_sortie"],$this->data["synopsis"], $this->data["duree"], $this->data["realisateur"], $this->data["casting"],
			$this->data["genre"], $this->data["univers"], $this->data["poster"])){
			echo "Erreur builder";
			throw new Exception("Missing fields for film creation");
		}
		echo "<br/> => Builder pour le Film. <br/>";
		return new Film(-1, $this->data["name"], $this->data["poster"], "", $this->data["synopsis"], $this->data["date_sortie"], $this->data["duree"], $this->data["realisateur"], $this->data["casting"], $this->data["univers"], $this->data["genre"], null, null, $_SESSION["id"], null);
	}

	function sayError($txt){
		trigger_error($txt, E_USER_ERROR);
	}

	/* Met à jour une instance de Film avec les données
	 * fournies. */
	public function updateFilm(Film $f) {
		if (isset($this->data["name"]))
			$f->setName($this->data["name"]);
		if (isset($this->data["duree"]))
			$f->setDuree($this->data["duree"]);
		if (isset($this->data["poster"]))
			$f->setPoster($this->data["poster"]);
		if (isset($this->data["background"]))
			$f->setBackground($this->data["background"]);
		if (isset($this->data["synopsis"]))
			$f->setSynopsis($this->data["synopsis"]);
		if (isset($this->data["realisateur"]))
			$f->setRealisateur($this->data["realisateur"]);
		if (isset($this->data["casting"]))
			$f->setCasting($this->data["casting"]);
		if (isset($this->data["univers"]))
			$f->setUnivers($this->data["univers"]);
		if (isset($this->data["genre"]))
			$f->setGenre($this->data["genre"]);

	}

	public function isValidDate($date){
	    $d = DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') == $date;
	}

	public static function isValidURL($url){
		if (preg_match('#^http://[w-]+[w.-]+.[a-zA-Z]{2,6}#i', $url)) {
		    return true;
		} else {
		    return false;
		}
	}

}

?>
