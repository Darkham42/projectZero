<?php

// Fonctions retirant les accents----------------------

function retireAcc($string) {
	return $string = utf8_encode(strtr(utf8_decode($string),utf8_decode("ÀÁÂÃÄÅàáâãäåÒÓÔÕÖØòóôõöøÈÉÊËèéêëÇçÌÍÎÏìíîïÙÚŬÛÜùúûüÿÑñ"),
		utf8_decode("AAAAAAaaaaaaOOOOOOooooooEEEEeeeeCcIIIIiiiiUUUUUuuuuyNn")));
}

// Fonctions pour les erreurs SQL
function toSQL($string){
	return utf8_decode(str_replace(array("\'","'"), "''", $string));
}

// Fonction testant le prénom--------------------------

function testPseudo($nom, $prenom) {

	$prenom = retireAcc($prenom);
	$prenom = substr($prenom,0,1);
	$prenom = strtoupper($prenom);

	$nom = retireAcc($nom);
	$nom = substr($nom,0,2);
	$nom = ucfirst(strtolower($nom));

	$pseudo = $prenom.$nom;

	if(ctype_alpha($pseudo) == true) {
		return $pseudo;
	}
	else {
		return false;
	}
}

?>