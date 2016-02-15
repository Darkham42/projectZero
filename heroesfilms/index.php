<?php

set_include_path("./src");

require_once("model/FilmStorageFile.php");
require_once("Router.php");

$r = new Router(new FilmStorageFile($_SERVER['TMPDIR'].'/film_db.txt'));
$r->main();

?>
