<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $this->title; ?></title>
	<meta charset="UTF-8" />
	<!-- <link rel="stylesheet" href="skin/screen.css" /> -->
	
	<?php foreach($this->style as $link){
		echo '<link rel="stylesheet" href="skin/' . $link . '">';
	} ?>

</head>
<body>

<nav class="navbar">
  
  <div class="containerNavbar">
    <?php echo "<h1 class='logo'><a href='".$this->router->homePage()."'>HeroesFilms</a></h1>"; ?>
    <ul class="nav nav-right">
    	<?php
			/* Construit le menu à partir d'un tableau associatif texte=>lien. */
			foreach ($this->getMenu() as $text => $link) {
				echo "<li><a href=\"$link\">$text</a></li>";
			}
		?>
    </ul>
  </div>
  
</nav>

	
	<?php if (!empty($this->feedback)) { ?>
		<div class="feedback"><?php echo $this->feedback; ?></div>
	<?php } ?>
	<main class="main">
		<?php
		echo $this->content;
		?>
	</main>

<script src="src/view/poster.js"></script>
</body>
</html>
