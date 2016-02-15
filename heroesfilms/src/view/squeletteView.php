<!DOCTYPE html>
<html lang="fr">
<head>
	<title><?php echo $this->title; ?></title>
	<meta charset="UTF-8" />
	<link rel="stylesheet" href="skin/screen.css" />
	<style>
<?php echo $this->style; ?>
	</style>
</head>
<body>
	<nav class="menu">
		<ul>
			<?php
			/* Construit le menu à partir d'un tableau associatif texte=>lien. */
			foreach ($this->getMenu() as $text => $link) {
				echo "<li><a href=\"$link\">$text</a></li>";
			}
			?>
		</ul>
	</nav>
	<?php if (!empty($this->feedback)) { ?>
		<div class="feedback"><?php echo $this->feedback; ?></div>
	<?php } ?>
	<main>
		<h1><?php echo $this->title; ?></h1>
		<?php
		echo $this->content;
		?>
	</main>
</body>
</html>
