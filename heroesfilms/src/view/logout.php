<?php

session_destroy();
session_start();
header('Location: .?action=deconnecte');
exit();

?>