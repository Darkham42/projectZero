<?php

set_include_path("./src");

//require_once("model/FilmStorageFile.php");
require_once("model/FilmStorageDB.php");
require_once("Router.php");

//$r = new Router(new FilmStorageFile($_SERVER['TMPDIR'].'/film_db.txt'));

$r = new Router(new FilmStorageDB());
$r->main();

include_once ('model/PDO/db.class.php');

$db = new Db();
$searchProject = $db->query("SELECT * FROM FILMS");
$db->CloseConnection();

/*
$json_data=array();

foreach($searchProject as $projet) {
    $json_array['nom']=$projet['nom'];
    $json_array['date_sortie']=$projet['date_sortie'];
    $json_array['univers']=$projet['univers'];
    $json_array['realisateur']=$projet['realisateur'];
    array_push($json_data,$json_array);
}

echo json_encode($json_data);

// cas de recherche spécifique pour éviter injection SQL
$db = new Db();

// Select
$person = $db->query("SELECT * FROM FILMS WHERE titre = :titre AND id = :id",array("titre"=>"Deadpool","id"=>"1"));

// Delete
$delete =  $db->query("DELETE FROM FILMS WHERE Id = :id", array("id"=>"1"));

// Update
$update =  $db->query("UPDATE FILMS SET titre = :t WHERE Id = :id", array("t"=>"dadadad","id"=>"32122"));

// Insert
$insert =  $db->query("INSERT INTO FILMS(Titre,Annee) VALUES(:t,:annee)", array("t"=>"ebvebe","anne"=>"2020"));

$db->CloseConnection();
*/
?>