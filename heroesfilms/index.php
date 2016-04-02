<?php

set_include_path("./src");

require_once("model/FilmStorageDB.php");
require_once("Router.php");

$r = new Router(new FilmStorageDB());
$r->main();

include_once ('model/PDO/db.class.php');

$db = new Db();
$searchProject = $db->query("SELECT * FROM FILMS");
$db->CloseConnection();

?>