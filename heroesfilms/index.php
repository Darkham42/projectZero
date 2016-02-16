<?php

set_include_path("./src");

require_once("model/FilmStorageFile.php");
require_once("Router.php");

$r = new Router(new FilmStorageFile($_SERVER['TMPDIR'].'/film_db.txt'));
$r->main();



include_once ('model/PDO/db.class.php');

$db = new Db();
$searchProject = $db->query("SELECT * FROM FILMS");
$db->CloseConnection();

$json_data=array();

foreach($searchProject as $projet) {
    $json_array['nom']=$projet['nom'];
    $json_array['date_sortie']=$projet['date_sortie'];
    $json_array['univers']=$projet['univers'];
    $json_array['realisateur']=$projet['realisateur'];
    array_push($json_data,$json_array);
}

echo json_encode($json_data);

?>