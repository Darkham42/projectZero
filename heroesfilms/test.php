<form name="test" method="POST">
<h4>Ajouter un film</h4>
Saisir la référence du film via <a href="https://www.themoviedb.org/movie" target="_blank">TMDb</a> : 

<input id="filmRef" name="ref" type="text" maxlength="6" placeholder="209112">

<button type="submit" name="submit">Ajouter le film</button>
</form>

<?php
if(isset($_POST['submit'])) {
  $film = $_POST['ref'];

  //Récupération des données du film (titre, genre, affiche)
  $filmData = file_get_contents('http://api.themoviedb.org/3/movie/'.$film.'?api_key=0d54fe0f0576cb6a08751326b1ec4a98&language=en&include_image_language=en,null');
  $jsonData = json_decode($filmData, true);

  $nbrGenres = count($jsonData['genres']);
  echo "<br>Genres : <br>";
  echo $jsonData['genres'][0]['name'];
  for ($i = 1; $i < $nbrGenres; $i++) {
    echo ", ".$jsonData['genres'][$i]['name'];
  }

  //Récupération de l'image d'arrière plan le mieux noté
  $background = file_get_contents('https://api.themoviedb.org/3/movie/'.$film.'?api_key=0d54fe0f0576cb6a08751326b1ec4a98&append_to_response=images');
  $jsonbackground = json_decode($background, true);

  $affiche = 'https://image.tmdb.org/t/p/original'.$jsonData['poster_path'];
  echo "<br>Affiche : <br><img src=".$affiche.">";

  $background = 'https://image.tmdb.org/t/p/original'.$jsonbackground['images']['backdrops'][0]['file_path'];
  echo "<br>Background : <br><img src='".$background."'>";
}
?>