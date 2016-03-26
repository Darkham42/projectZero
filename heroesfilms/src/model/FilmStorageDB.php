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

		$tab = array();
		$tab["reali"] = $f->getRealisateur();
		$requestreal = $this->db->query("SELECT id FROM REALISATEUR WHERE direc = :reali", $tab);
		$json_data=array();
		$idreal = -1;
		foreach($requestreal as $recherche) {
			if(isset($recherche['id'])) $idreal = $recherche['id'];
		}
		//si on n'a pas trouve un realisateur, on l'ajoute
		if($idreal == -1){
			$tab = array();
			$tab["real2"] = $f->getRealisateur();

			foreach($this->db->query("SELECT count(*) as nb FROM REALISATEUR") as $count) {
				$tab["id"] = $count['nb'];
			}

			$this->db->query("INSERT INTO REALISATEUR(id, direc) VALUES(:id,:real2)", $tab);
			
			$newtab = array();
			$newtab["real2"] = $f->getRealisateur();
			$requestid = $this->db->query("SELECT id FROM REALISATEUR WHERE direc = :real2", $newtab);
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
		$tab["poster"] = $f->getPoster();
		$tab["descr"] = $f->getSynopsis();
		$tab["sortie"] = $f->getDateSortie();
		$tab["duree"] = $f->getDuree();
		$tab["univers"] = $idUnivers;
		$tab["reali"] = $idreal;
		$tab["background"] = $background;
		$tab["modif"] = $f->getModifDate()->format('Y-m-d H:i:s');;
		$tab["creation"] = $f->getCreationDate()->format('Y-m-d H:i:s');

		$i = 1;
		foreach($f->getGenre() as $genre){
			echo " <br/> genre " . $i . " <br/>";
			$tab["genre".$i] = $genre;
			$i ++;
		}
		for($j = 3; $j >= $i; $j--){
			$tab["genre".$j] = 1;
		}
		//Find id for new film
		$id = 0;
		foreach($this->db->query("SELECT id FROM FILMS ORDER BY id") as $idFilms) {
			//echo $id . " " .  $idFilms['id'] . "<br/>";
			if($id != $idFilms['id']){
				$tab["id"] = $idFilms['id'];
				break;
			}
			$id ++;
		}
		if(!isset($tab["id"])) {
			$tab["id"] = $id;
		}

		foreach($tab as $key => $value){
			echo "<br/> - " . $key . " : " . $value ;

		}

		/*
		date_creation, date_last_modif
		:creation, :modif*/
		
		$this->db->query("INSERT INTO FILMS(
			id, nom, poster, synopsis, date_sortie, duree, univers, realisateur, background, date_creation, date_last_modif, genre1, genre2, genre3) 
			VALUES(
			:id, :name, :poster, :descr, :sortie, :duree, :univers, :reali, :background, :creation, :modif, :genre1, :genre2, :genre3)"
			, $tab);

		//recuperation de l'id du film
		$requestid = $this->db->query("SELECT id FROM FILMS WHERE nom = :titre", array("titre"=> $f->getName()));
		$json_data=array();
		$id;
		foreach($requestid as $recherche) {
			$id = $recherche['id'];
		}

		//ajout dans CASTING
		$tab = array();
		$tab["id"] = $id;
		//$tab["casting"] = $f->getCasting();
		foreach($f->getCasting() as $act) {
			$tab["act"] = $act;
			$this->db->query("INSERT INTO CASTING(idFilm, cast) VALUES(:id, :act)", $tab);
		}

		echo "FIN INSERT";
		return $id;
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
		$name = "";
		$poster = "";
		$background = "";
		$date_sortie = "";
		$duree = "";
		$idreal = "";
		$univers = "";
		$genre = "";
		foreach($searchProject as $projet) {
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $background = $projet['background'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    $idreal = $projet['realisateur'];
		    $univers = $this->findUnivers($projet['univers']);
		    $synopsis = $projet['synopsis'];
		    $genre = array($this->findGenre($projet['genre1']),$this->findGenre($projet['genre2']),$this->findGenre($projet['genre3']));  
		}

		$searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $idreal));
		$real = "";
		foreach($searchReal as $projet){
			$real .= $projet['direc'];
		}
		return new Film($name, $poster, $background, $synopsis, $date_sortie, $duree, $real, $casting, $univers, $genre, $creationDate=null, $modifDate=null);
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
		    $background = $projet['background'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    $realisateur = $projet['realisateur'];
		    $casting = $casting;
		    $univers = $this->findUnivers($projet['univers']);
		    $synopsis = $projet['synopsis'];

		    $genre = array($this->findGenre($projet['genre1']),$this->findGenre($projet['genre2']),$this->findGenre($projet['genre3']));
		    
		    array_push($array, new Film($name, $poster, $background, $synopsis, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null));
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
		$tab = array();
		$tab["reali"] = $f->getRealisateur();
		$requestreal = $this->db->query("SELECT id FROM REALISATEUR WHERE direc = :reali", $tab);
		$json_data=array();
		$idreal = -1;
		foreach($requestreal as $recherche) {
			if(isset($recherche['id'])) $idreal = $recherche['id'];
		}
		//si on n'a pas trouve un realisateur, on l'ajoute
		if($idreal == -1){
			$tab = array();
			$tab["real2"] = $f->getRealisateur();

			foreach($this->db->query("SELECT count(*) as nb FROM REALISATEUR") as $count) {
				$tab["id"] = $count['nb'];
			}

			$this->db->query("INSERT INTO REALISATEUR(id, direc) VALUES(:id,:real2)", $tab);
			
			$newtab = array();
			$newtab["real2"] = $f->getRealisateur();
			$requestid = $this->db->query("SELECT id FROM REALISATEUR WHERE direc = :real2", $newtab);
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
		$tab["poster"] = $f->getPoster();
		$tab["descr"] = $f->getSynopsis();
		$tab["sortie"] = $f->getDateSortie();
		$tab["duree"] = $f->getDuree();
		$tab["univers"] = $idUnivers;
		$tab["reali"] = $idreal;
		$tab["background"] = "URLbackground";
		$tab["modif"] = $f->getModifDate()->format('Y-m-d H:i:s');;
		$tab["creation"] = $f->getCreationDate()->format('Y-m-d H:i:s');

		$i = 1;
		foreach($f->getGenre() as $genre){
			echo " <br/> genre " . $i . " <br/>";
			$tab["genre".$i] = $genre;
			$i ++;
		}
		for($j = 3; $j >= $i; $j--){
			$tab["genre".$j] = 1;
		}
		//Find id for new film
		$id = 0;
		foreach($this->db->query("SELECT id FROM FILMS ORDER BY id") as $idFilms) {
			//echo $id . " " .  $idFilms['id'] . "<br/>";
			if($id != $idFilms['id']){
				$tab["id"] = $idFilms['id'];
				break;
			}
			$id ++;
		}
		if(!isset($tab["id"])) {
			$tab["id"] = $id;
		}

		foreach($tab as $key => $value){
			echo "<br/> - " . $key . " : " . $value ;

		}

		/*
		date_creation, date_last_modif
		:creation, :modif*/
		
		$this->db->query("INSERT INTO FILMS(
			id, nom, poster, synopsis, date_sortie, duree, univers, realisateur, background, date_creation, date_last_modif, genre1, genre2, genre3) 
			VALUES(
			:id, :name, :poster, :descr, :sortie, :duree, :univers, :reali, :background, :creation, :modif, :genre1, :genre2, :genre3)"
			, $tab);

		//recuperation de l'id du film
		$requestid = $this->db->query("SELECT id FROM FILMS WHERE nom = :titre", array("titre"=> $f->getName()));
		$json_data=array();
		$id;
		foreach($requestid as $recherche) {
			$id = $recherche['id'];
		}

		//ajout dans CASTING
		$tab = array();
		$tab["id"] = $id;
		//$tab["casting"] = $f->getCasting();
		foreach($f->getCasting() as $act) {
			$tab["act"] = $act;
			$this->db->query("INSERT INTO CASTING(idFilm, cast) VALUES(:id, :act)", $tab);
		}

		echo "FIN INSERT";
		return $id;



	}

	/* Supprime une film. Renvoie
	 * true si la suppression a été effectuée, false
	 * si l'identifiant ne correspond à aucune film. */
	public function delete($id) {

		$tab = array();
		$tab["id"] = $id;

		$request = $this->db->query("DELETE FROM films WHERE id = :id", $tab);

		$tab = array();
		$tab["id"] = $id;
		$request = $this->db->query("SELECT id FROM films WHERE id = :id", $tab);
		$count = 0 ;
		foreach($request as $recherche) {
			$count += 1;
		}
		return $count == 0;
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
