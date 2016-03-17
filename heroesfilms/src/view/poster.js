var $poster = document.getElementsByClassName("posterSize");
for (var i = 0; i < $poster.length; i++) {
  $poster[i].addEventListener("click", infoFilm);
}

function infoFilm(e) {
  e.currentTarget.classList.toggle("bright"); 
}