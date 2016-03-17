var $card = document.getElementsByClassName("cardFilms");

for (var i = 0; i < $card.length; i++) {
  $card[i].addEventListener("click", infoFilm);
}

function infoFilm(e) {
  var $poster = e.currentTarget.getElementsByClassName("posterSize")[0];
  var $txt = e.currentTarget.getElementsByClassName("text")[0];
  $poster.classList.toggle("bright");
  if ($txt.style.opacity === "1") {
    $txt.style.opacity = "0" ;
  } else {
    $txt.style.opacity = "1" ;
  }
  
}