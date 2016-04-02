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

		//Usage de TMDB
		$film = $f->getPoster();

		//Récupération des données du film (titre, genre, affiche) on ne peut pas juste avoir l'affiche
		$filmData = file_get_contents('http://api.themoviedb.org/3/movie/'.$film.'?api_key=0d54fe0f0576cb6a08751326b1ec4a98&language=en&include_image_language=en,null');
	  $jsonData = json_decode($filmData, true);

	  //Récupération de l'image d'arrière plan le mieux noté
	  $background = file_get_contents('https://api.themoviedb.org/3/movie/'.$film.'?api_key=0d54fe0f0576cb6a08751326b1ec4a98&append_to_response=images');
	  $jsonbackground = json_decode($background, true);

		$affiche = 'https://image.tmdb.org/t/p/original'.$jsonData['poster_path'];
		$background = 'https://image.tmdb.org/t/p/original'.$jsonbackground['images']['backdrops'][0]['file_path'];

		if (strcmp($affiche,'https://image.tmdb.org/t/p/original') == 0) {
			$affiche = "http://localhost/projectZero/heroesFilms/assets/NoPoster.jpg";
			$background = "http://localhost/projectZero/heroesFilms/assets/NoBackground.jpg";
		} 

		$tab = array();
		$tab["name"] = $f->getName();
		$tab["poster"] = $affiche;
		$tab["descr"] = $f->getSynopsis();
		$tab["sortie"] = $f->getDateSortie();
		$tab["duree"] = $f->getDuree();
		$tab["univers"] = $idUnivers;
		$tab["reali"] = $idreal;
		$tab["background"] = $background;
		//$tab["modif"] = $f->getModifDate()->format('Y-m-d H:i:s');
		$tab["modif"] = "1970-01-01";
		$tab["creation"] = $f->getCreationDate()->format('Y-m-d H:i:s');
		$tab["idUser"] = $_SESSION["id"];

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
			echo $id . " " .  $idFilms['id'] . "<br/>";
			if($id != $idFilms['id']){
				$tab["id"] = $id;
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
			id, nom, poster, synopsis, date_sortie, duree, univers, realisateur, background, date_creation, date_last_modif, genre1, genre2, genre3, idUser) 
			VALUES(
			:id, :name, :poster, :descr, :sortie, :duree, :univers, :reali, :background, :creation, :modif, :genre1, :genre2, :genre3, :idUser)"
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
		$numActors = count($searchCasting);
		$i = 0;
		foreach($searchCasting as $projet){
			if(++$i === $numActors) {
				$casting .= $projet['cast'];
			} else {
				$casting .= $projet['cast'].", ";
			}	
		}
		$name = "";
		$poster = "";
		$background = "";
		$date_sortie = "";
		$duree = "";
		$idreal = "";
		$univers = "";
		$genre = "";
		$creationDate = "";
		$modifDate= "";
		$idtable = "";
		$synopsis = "";
		$idUser = "";
		$pseudoUser = "Unknown";

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
		    $creationDate = $projet['date_creation'];
		    $modifDate = $projet['date_last_modif'];
		    $idtable = $projet['id'];
		    $idUser= $projet['idUser'];
		    $searchUser = $this->db->query("SELECT * FROM USERS WHERE id = :i", array("i"=> $idUser));
			$realisateur = "";
			foreach($searchUser as $acteur){
				$pseudoUser = $acteur['pseudo'];
			}

		}

		$searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $projet["id"]));
		$realisateur = "";
		foreach($searchReal as $acteur){
			$realisateur = $acteur['direc'];
		}
		$film = new Film($idtable, $name, $poster, $background, $synopsis, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate, $modifDate, $idUser, $pseudoUser);
		//var_dump($film);
		return $film;
	}

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les films de la base. */
	public function readAll() {

		$searchProject = $this->db->query("SELECT * FROM FILMS ORDER BY date_sortie DESC");

		$array = array();
		foreach($searchProject as $projet) {
			$searchCasting = $this->db->query("SELECT * FROM CASTING WHERE idFilm = :i", array("i"=> $projet["id"]));
			$casting = "";
			foreach($searchCasting as $acteur){
				$casting = $casting." ".$acteur['cast']." ";
			}
			$searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $projet["id"]));
			$realisateur = "";
			foreach($searchReal as $acteur){
				$realisateur = $acteur['direc'];
			}
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $background = $projet['background'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    //$realisateur = $projet['direc'];
		    $casting = $casting;
		    $univers = $this->findUnivers($projet['univers']);
		    $synopsis = $projet['synopsis'];

		    $genre = array($this->findGenre($projet['genre1']),$this->findGenre($projet['genre2']),$this->findGenre($projet['genre3']));
		    $idtable = $projet["id"];
		    //var_dump($projet);

		     //echo  "<br/>" .$idtable . $name . $realisateur . "<br/>";
		    array_push($array, new Film($idtable, $name, $poster, $background, $synopsis, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null, null, null));
		}
		return $array;
	}

	public function readAllMarvel() {

		$searchProject = $this->db->query("SELECT * FROM FILMS WHERE univers = '2' ORDER BY date_sortie DESC");

		$array = array();
		foreach($searchProject as $projet) {
			$searchCasting = $this->db->query("SELECT * FROM CASTING WHERE idFilm = :i", array("i"=> $projet["id"]));
			$casting = "";
			foreach($searchCasting as $acteur){
				$casting = $casting." ".$acteur['cast']." ";
			}
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $background = $projet['background'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    $searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $projet["id"]));
			$realisateur = "";
			foreach($searchReal as $acteur){
				$realisateur = $acteur['direc'];
			}
		    $casting = $casting;
		    $univers = $this->findUnivers($projet['univers']);
		    $synopsis = $projet['synopsis'];

		    $genre = array($this->findGenre($projet['genre1']),$this->findGenre($projet['genre2']),$this->findGenre($projet['genre3']));
		    $idtable = $projet["id"];
		    //echo $idtable . $name . $realisateur . "<br/>";
		    array_push($array, new Film($idtable, $name, $poster, $background, $synopsis, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null, null, null));
		}
		return $array;
	}

	public function readAllDC() {

		$searchProject = $this->db->query("SELECT * FROM FILMS WHERE univers = '1' ORDER BY date_sortie DESC");

		$array = array();
		foreach($searchProject as $projet) {
			//var_dump($projet);
			$searchCasting = $this->db->query("SELECT * FROM CASTING WHERE idFilm = :i", array("i"=> $projet["id"]));
			$casting = "";
			foreach($searchCasting as $acteur){
				$casting = $casting." ".$acteur['cast']." ";
			}
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $background = $projet['background'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    $searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $projet["id"]));
			$realisateur = "";
			foreach($searchReal as $acteur){
				$realisateur = $acteur['direc'];
			}
		    $casting = $casting;
		    $univers = $this->findUnivers($projet['univers']);
		    $synopsis = $projet['synopsis'];
		    $genre = array($this->findGenre($projet['genre1']),$this->findGenre($projet['genre2']),$this->findGenre($projet['genre3']));
		    $idtable = $projet["id"];
		    //echo $idtable . $name . $realisateur . $duree. "<br/>";
		    array_push($array, new Film($idtable, $name, $poster, $background, $synopsis, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null, null, null));
		}
		return $array;
	}

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les films poste par une personne sur la base. */
	public function readMy($user) {

		$searchProject = $this->db->query("SELECT * FROM FILMS WHERE idUser = :id ORDER BY date_sortie DESC", array("id"=> $user));

		$array = array();
		foreach($searchProject as $projet) {
			$searchCasting = $this->db->query("SELECT * FROM CASTING WHERE idFilm = :i", array("i"=> $projet["id"]));
			$casting = "";
			foreach($searchCasting as $acteur){
				$casting = $casting." ".$acteur['cast']." ";
			}
			$searchReal = $this->db->query("SELECT * FROM REALISATEUR WHERE id = :i", array("i"=> $projet["id"]));
			$realisateur = "";
			foreach($searchReal as $acteur){
				$realisateur = $acteur['direc'];
			}
		    $name = $projet['nom'];
		    $poster = $projet['poster'];
		    $background = $projet['background'];
		    $date_sortie = $projet['date_sortie'];
		    $duree = $projet['duree'];
		    //$realisateur = $projet['direc'];
		    $casting = $casting;
		    $univers = $this->findUnivers($projet['univers']);
		    $synopsis = $projet['synopsis'];

		    $genre = array($this->findGenre($projet['genre1']),$this->findGenre($projet['genre2']),$this->findGenre($projet['genre3']));
		    $idtable = $projet["id"];
		    //var_dump($projet);

		     //echo  "<br/>" .$idtable . $name . $realisateur . "<br/>";
		    array_push($array, new Film($idtable, $name, $poster, $background, $synopsis, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null, null, null));
		}
		return $array;
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

		//on supprime le casting
		$searchCasting = $this->db->query("DELETE FROM CASTING WHERE idFilm = :i", array("i"=> $id));

		//ajout dans CASTING
		$tab = array();
		$tab["id"] = $id;
		//$tab["casting"] = $f->getCasting();
		foreach($f->getCasting() as $act) {
			$tab["act"] = $act;
			$this->db->query("INSERT INTO CASTING(idFilm, cast) VALUES(:id, :act)", $tab);
		}


		$tab = array();
		$tab["id"] = $f->getId();
		$tab["name"] = $f->getName();
		$tab["poster"] = $f->getPoster();
		$tab["descr"] = $f->getSynopsis();
		$tab["sortie"] = $f->getDateSortie();
		$tab["duree"] = $f->getDuree();
		$tab["univers"] = $idUnivers;
		$tab["reali"] = $idreal;
		$tab["background"] = "URLbackground";
		$tab["modif"] = $f->getModifDate();
		$tab["creation"] = $f->getCreationDate();

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

/*
		foreach($tab as $key => $value){
			echo "<br/> - " . $key . " : " . $value ;

		}*/

		/*
		date_creation, date_last_modif
		:creation, :modif*/
		echo " new " . $f->getSynopsis();
		$this->db->query('UPDATE FILMS SET
			 nom = "'. $f->getName() . '",
			 synopsis = "'. $f->getSynopsis() . '",
			 date_sortie = "'. $f->getDateSortie() . '",
			 duree = "'. $f->getDuree() . '",
			 realisateur = "'. $idreal . '",
			 genre1 = "'. $tab["genre1"] . '",
			 genre2 = "'. $tab["genre2"] . '",
			 genre3 = "'. $tab["genre3"] . '",
			 date_last_modif = "'. $f->getModifDate()->format('Y-m-d H:i:s') . '"
			 WHERE id = "'.$f->getId().'"'
			, $tab);

		echo "FIN INSERT";

		//univers = "'. $f->getUnivers() . '",
		return $id;
	}

	/* Supprime une film. Renvoie
	 * true si la suppression a été effectuée, false
	 * si l'identifiant ne correspond à aucune film. */
	public function delete($id) {

		$tab = array();
		$tab["id"] = $id;
		$request = $this->db->query("DELETE FROM CASTING WHERE idFilm = :id", $tab);
		$request = $this->db->query("DELETE FROM FILMS WHERE id = :id", $tab);
		$request = $this->db->query("SELECT id FROM FILMS WHERE id = :id", $tab);
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
