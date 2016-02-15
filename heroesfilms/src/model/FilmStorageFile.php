<?php

require_once("Film.php");
require_once("FilmStorage.php");

/* Gère le stockage de films dans un fichier. Plus simple que l'utilisation
 * d'une base de données, mais très peu robuste en l'état : les modifications
 * concurrentes ne sont pas du tout gérées. */

class FilmStorageFile implements FilmStorage {

	/* le tableau qui sert de base de données */
	private $db;
	/* le fichier dans lequel le tableau est sérialisé */
	private $file;
	/* prochain id à utiliser */
	private $curid;

	/* Construit une nouvelle instance, qui utilise le fichier donné
	 * en paramètre. */
	public function __construct($file) {
		$this->file = $file;
		if (file_exists($this->file)) {
			$this->db = unserialize(base64_decode(file_get_contents($this->file)));
		} else {
			$this->db = array();
		}
		end($this->db);
		$lastkey = key($this->db);
		if ($lastkey === null)
			$this->curid = 0;
		else
			$this->curid = intval($lastkey) + 1;
	}

	/* Sérialise la base avant de détruire l'instance. */
	public function __destruct() {
		file_put_contents($this->file, base64_encode(serialize($this->db)));
	}

	/* Insère une nouvelle film dans la base. Renvoie l'identifiant
	 * de la nouvelle film. */
	public function create(Film $c) {
		$id = "{$this->curid}"; // attention : l'id est une chaîne
		if (array_key_exists($id, $this->db))
			throw new Exception("The next id is already used");
		$this->db[$id] = $c;
		$this->curid++;
		return $id;
	}

	/* Renvoie la film d'identifiant $id, ou null
	 * si l'identifiant ne correspond à aucune film. */
	public function read($id) {
		if (array_key_exists($id, $this->db))
			return $this->db[$id];
		else
			return null;
	}

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les films de la base. */
	public function readAll() {
		return $this->db;
	}

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les films de la base. */
	public function readMy($user) {
		return $this->db;
	}

	/* Met à jour une film dans la base. Renvoie
	 * true si la modification a été effectuée, false
	 * si l'identifiant ne correspond à aucune film. */
	public function update($id, Film $c) {
		if (array_key_exists($id, $this->db)) {
			$this->db[$id] = $c;
			return true;
		}
		return false;
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

}

?>
