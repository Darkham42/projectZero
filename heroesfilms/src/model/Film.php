<?php

/* Représente une film. */
class Film {

	protected $name;
	protected $date_sortie;
	protected $duree ;
	protected $univers;
	protected $realisateur;
	protected $genre;
	protected $creationDate;
	protected $modifDate;
	protected $casting;

	/* Construit une film. Si les paramètres de date ne sont pas passés,
	 * la film est considérée comme étant toute nouvelle.
	 * Le nom et le code hexa doivent être valides, au sens
	 * de isNameValid et isHexValid, sinon une exception est levée. */
	public function __construct($name, $date_sortie, $duree, $realisateur, $casting, $univers, $genre, $creationDate=null, $modifDate=null) {
		echo "Creation film ";
		if (!self::isValidName($name)){
			echo "Invalid Name";
			throw new Exception("Invalid film name");
		}
		$this->name = $name;
		if (!self::isValidDate($date_sortie)){
			echo "Invalid Date" ;
			throw new Exception("Invalid date");
		}
		$this->duree = $duree;
		$this->date_sortie = $date_sortie;
		$this->realisateur = $realisateur;
		$this->casting = $casting;
		$this->univers = $univers;
		$this->genre = $genre;
		$this->creationDate = $creationDate !== null? $creationDate: new DateTime();
		$this->modifDate = $modifDate !== null? $modifDate: new DateTime();

		echo "film ok";
	}

	public function getName() {
		return $this->name;
	}

	public function getDateSortie() {
		return $this->date_sortie;
	}

	public function getDuree(){
		return $this->duree;
	}

	public function getRealisateur() {
		return $this->realisateur;
	}

	public function getCasting() {
		return $this->casting;
	}

	public function getUnivers(){
		return $this->univers;
	}

	public function getGenre(){
		return $this->genre;
	}

	/* Renvoie un objet DateTime correspondant à
	 * la création de la film. */
	public function getCreationDate() {
		return $this->creationDate;
	}

	/* Renvoie un objet DateTime correspondant à
	 * la dernière modification de la film. */
	public function getModifDate() {
		return $this->modifDate;
	}

	/* Modifie le nom de la film. Le nouveau nom doit
	 * être valide au sens de isNameValid, sinon
	 * une exception est levée. */
	public function setName($name) {
		if (!self::isValidName($name))
			throw new Exception("Invalid film name");
		$this->name = $name;
		$this->modifDate = new DateTime();
	}

	public function setDateSortie($date_sortie) {
		if (!self::isValidDate($date_sortie))
			throw new Exception("Invalid date");
		$this->date_sortie = $date_sortie;
		$this->modifDate = new DateTime();
	}

	public function setDuree($duree){
		$this->duree = $duree;
		$this->modifDate = new DateTime();
	}

	public function setRealisateur($realisateur) {
		if (!self::isNameValid($realisateur))
			throw new Exception("Invalid realisator name");
		$this->realisateur = $realisateur;
		$this->modifDate = new DateTime();
	}

	public function setCasting($casting) {
		$this->casting = $casting;
		$this->modifDate = new DateTime();
	}

	public function setUnivers($univers){
		$this->univers = $univers;
		$this->modifDate = new DateTime();
	}

	public function setGenre($genre){
		$this->genre = $genre;
		$this->modifDate = new DateTime();
	}

	

	/* Indique si $name est un nom valide pour une film.
	 * Il doit faire moins de 30 caractères,
	 * et ne pas être vide. */
	public static function isValidName($name) {
		return true;
		//return mb_strlen($name, 'UTF-8') < 30 && $name !== "";
	}

	public static function isValidDate($date){
	    $d = DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') == $date;
	}

}

?>
