<?php

require_once("Film.php");
require_once("FilmStorage.php");
include_once ('model/PDO/db.class.php');

/* Gère le stockage de films dans un fichier. Plus simple que l'utilisation
 * d'une base de données, mais très peu robuste en l'état : les modifications
 * concurrentes ne sont pas du tout gérées. */

class FilmStorageDB implements FilmStorage {

	/* le tableau qui sert de base de données */
	private $db;
	/* prochain id à utiliser */
	private $curid;

	/* Construit une nouvelle instance, qui utilise le fichier donné
	 * en paramètre. */
	public function __construct() {
		$this->db = new Db();

	}

	/* Sérialise la base avant de détruire l'instance. */
	public function __destruct() {
		//?
	}

	/* Insère une nouvelle film dans la base. Renvoie l'identifiant
	 * de la nouvelle film. */
	public function create(Film $f) {
		//ajout dans REALISATEUR
		var_dump($f->getRealisateur());
		$requestreal = $this->db->query("SELECT id FROM REALISATEUR WHERE direc = :r", array("r"=> $f->getRealisateur()));
		$json_data=array();
		$idreal = -1;
		echo "<br/> REQUEST ";
		var_dump($requestreal);
		echo "<br/>";
		foreach($requestreal as $recherche) {
			var_dump($recherche);
			if(isset($recherche['id'])) $idreal = $recherche['id'];
		}
		//si on n'a pas trouve un realisateur, on l'ajoute
		if($idreal == -1){
			$tab = array();
			$tab["r"] = $f->getRealisateur();

			foreach($this->db->query("SELECT count(*) as nb FROM REALISATEUR") as $count) {
				$tab["id"] = $count['nb'];
			}

			$this->db->query("INSERT INTO REALISATEUR(id, direc) VALUES(:id,:r)", $tab);

			$requestid = $this->db->query("SELECT id FROM REALISATEUR WHERE direc = :r", $tab);
			foreach($requestid as $recherche) {
				$idreal = $recherche['id'];
			}
		}

		$idUnivers = 0;
		switch($f->getUnivers()){
			case "DC Comics" : 
				$idUnivers = 1; break;
			case "Marvel" : 
				$idUnivers = 2; break;
			default: 
				$idUnivers = 3; break;
		}

		//idreal est normalement ok.

		$tab = array();
		$tab["name"] = $f->getName();
		$tab["poster"] = "";
		$tab["descr"] = "DESCRIPTION DE " . $f->getName();
		$tab["sortie"] = $f->getDateSortie();
		$tab["duree"] = $f->getDuree();
		$tab["univers"] = $idUnivers;
		$tab["reali"] = $idreal;
		$tab["genre"] = $f->getGenre();
		//$tab["creation"] = $f->getCreationDate();
		//$tab["modif"] = $f->getModifDate();

		//manque date crea et date modif
		//ajout dans FILMS
		echo "<br/>";
		var_dump($idreal);
		echo "<br/>";
		// Get size from array
		

		foreach($this->db->query("SELECT count(*) as nb FROM FILMS") as $count) {
				$tab["id"] = $count['nb'];
			}

		$this->db->query("INSERT INTO FILMS(id, nom, poster, synopsis, date_sortie, duree, univers, realisateur, genre1) 
			VALUES(:id, :name, :poster, :descr, :sortie, :duree, :univers, :reali, :genre)", $tab);

		//recuperation de l'id du film
		$requestid = $this->db->query("SELECT id FROM FILMS WHERE nom = :titre", array("titre"=> $f->getName()));
		$json_data=array();
		$id;
		foreach($requestid as $recherche) {
			$id = $recherche['id'];
		}

		//ajout dans CASTING
		$tab = array();
		$tab["id"] = $f->getCasting();
		$tab["casting"] = $f->getCasting();

		$this->db->query("INSERT INTO CASTING(idFilm, cast) VALUES(:id, :casting)", $tab);

		echo "FIN INSERT";
	}

	/* Renvoie la film d'identifiant $id, ou null
	 * si l'identifiant ne correspond à aucune film. */
	public function read($id) {
		//$id = $id +1;
		//$searchProject = $this->db->query("SELECT * FROM FILMS as f, REALISATEUR as r WHERE f.id = :i AND f.realisateur = r.id", array("i"=> $id));
		$searchProject = $this->db->query("SELECT * FROM FILMS WHERE id = :i", array("i"=> $id));
		//var_dump($searchProject);
		$searchCasting = $this->db->query("SELECT * FROM CASTING WHERE idFilm = :i", array("i"=> $id));
		$casting = "";
		foreach($searchCasting as $projet){
			$casting .= $projet['cast'] . " ";
		}
		$searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $id));
		$real = "";
		foreach($searchReal as $projet){
			$real .= $projet['direc'] . " ";
		}
		$name = "";
		$poster = "";
	    $date_sortie = "";
	    $duree = "";
	    $univers = "";
	    $genre = "";
		foreach($searchProject as $projet) {
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    $realisateur = $real;
		    $univers = $this->findUnivers($projet['univers']);
		    $genre = $this->findGenre($projet['genre1']) . $this->findGenre($projet['genre2']) . $this->findGenre($projet['genre3']);  
		}
		return new Film($name, $poster, $date_sortie, $duree, $real, $casting, $univers, $genre, $creationDate=null, $modifDate=null);
	}

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les films de la base. */
	public function readAll() {

		$searchProject = $this->db->query("SELECT * FROM FILMS as f, REALISATEUR as r WHERE  f.realisateur = r.id");

		$array = array();
		foreach($searchProject as $projet) {
			$searchCasting = $this->db->query("SELECT * FROM CASTING WHERE idFilm = :i", array("i"=> $projet["id"]));
			$casting = "";
			foreach($searchCasting as $acteur){
				$casting = $casting . " " . $acteur['cast'] . " ";
			}
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    $realisateur = $projet['realisateur'];
		    $casting = $casting;
		    $univers = $this->findUnivers($projet['univers']);
		    $genre = $this->findGenre($projet['genre1']) . $this->findGenre($projet['genre2']) . $this->findGenre($projet['genre3']);
		   
		    
		    array_push($array, new Film($name, $poster, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null));
		}
		return $array;
	}

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les films poste par une personne sur la base. */
	public function readMy($user) {
		//A ajouter un where sur id personnes.
		return $this->db>query("SELECT * FROM FILMS");
	}

	/* Met à jour une film dans la base. Renvoie
	 * true si la modification a été effectuée, false
	 * si l'identifiant ne correspond à aucune film. */
	public function update($id, Film $f) {
		//ajout dans REALISATEUR
		$requestreal = $db->query("SELECT id FROM FILMS WHERE realisateur = :r", array("r"=> $f->getRealisateur()));
		$json_data=array();
		$idreal = -1;
		foreach($requestreal as $recherche) {
			if(isset($recherche['id'])) $idreal = $recherche['id'];
		}
		//si on n'a pas trouve un realisateur, on l'ajoute
		if(idreal != -1){
			$tab = array();
			$tab["r"] = $f->getRealisateur();
			$db->query("INSERT INTO CASTING(idFilm, cast) VALUES( :r)", $tab);

			$requestid = $db->query("SELECT id FROM CASTING WHERE realisateur = :r", $tab);
			$json_data=array();
			foreach($requestid as $recherche) {
				$idreal = $recherche['id'];
			}
		}

		$idUnivers = 0;
		switch($f->getUnivers()){
			case "DC Comics" : 
				$idUnivers = 1; break;
			case "Marvel" : 
				$idUnivers = 2; break;
			default: 
				$idUnivers = 3; break;
		}

		//idreal est normalement ok.

		$tab = array();
		$tab["name"] = $f->getName();
		$tab["poster"] = "";
		$tab["descr"] = "DESCRIPTION DE " . $f->getName();
		$tab["sortie"] = $f->getDateSortie();
		$tab["duree"] = $f->getDuree();
		$tab["univers"] = $idUnivers;
		$tab["reali"] = $idreal;
		$tab["genre"] = $f->getGenre();
		//$tab["creation"] = $f->getCreationDate();
		//$tab["modif"] = $f->getModifDate();

		//manque date crea et date modif
		//ajout dans FILMS
		$db->query("INSERT INTO FILMS(nom, poster, synopsis, date_sortie, duree, univers, realisateur, genre1, genre2, genre3) 
			VALUES(:name, :poster, :descr, :sortie, :duree, :univers, :reali, :genre)", $tab);

		//recuperation de l'id du film
		$requestid = $db->query("SELECT id FROM FILMS WHERE titre = :titre", array("titre"=> $f->getName()));
		$json_data=array();
		$id;
		foreach($requestid as $recherche) {
			$id = $recherche['id'];
		}

		//ajout dans CASTING
		$tab = array();
		$tab["id"] = $f->getCasting();
		$tab["casting"] = $f->getCasting();
		$db->query("INSERT INTO CASTING(idFilm, cast) VALUES(:id, :casting)", $tab);

		echo "FIN INSERT";



	}

	/* Supprime une film. Renvoie
	 * true si la suppression a été effectuée, false
	 * si l'identifiant ne correspond à aucune film. */
	public function delete($id) {
		if (array_key_exists($id, $this->db)) {
			unset($this->db[$id]);
			return true;
		}
		return false;
	}

	/* Vide la base. */
	public function deleteAll() {
		$this->db = array();
	}

	public function findUnivers($id){
		switch($id){
			case 1 : 
				return "DC Comics";
			case 2 : 
				return "Marvel";
			default: 
				return "Autres";
		}
	}

	public function findGenre($id){
		switch($id){
			case 1 : 
				return "Action";
			case 2 : 
				return "Adventure";
			case 3 : 
				return "Comedy";
			case 4 : 
				return "Sci-Fi";
			case 5 : 
				return "Fantasy";
		}

	}
}

?>
