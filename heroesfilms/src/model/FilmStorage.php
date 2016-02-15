<?php

require_once("Film.php");

interface FilmStorage {

	/* Insère une nouvelle films dans la base. Renvoie l'identifiant
	 * de la nouvelle films. */
	public function create(Film $c);

	/* Renvoie la films d'identifiant $id, ou null
	 * si l'identifiant ne correspond à aucune films. */
	public function read($id);

	/* Renvoie un tableau associatif id => Film
	 * contenant toutes les filmss de la base. */
	public function readAll();

	/* Renvoie un tableau associatif id => Film
	 * contenant tous mes films dans la base. */
	public function readMy($user);

	/* Met à jour une films dans la base. Renvoie
	 * true si la modification a été effectuée, false
	 * si l'identifiant ne correspond à aucune films. */
	public function update($id, Film $c);

	/* Supprime une films. Renvoie
	 * true si la suppression a été effectuée, false
	 * si l'identifiant ne correspond à aucune films. */
	public function delete($id);

	/* Vide la base. */
	public function deleteAll();

}

?>
