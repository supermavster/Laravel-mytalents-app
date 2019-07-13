window.addEventListener('load',function(){

    $('#selectMusic').click(function(){
      showCheckMusicales();
    });

    $('#selectSports').click(function(){
      showCheckDeportes();
    });

  function showCheckDeportes() {
    var deportes = document.getElementById("deportes");

    if(deportes.classList.contains("hide")) {
            deportes.classList.remove("hide");
        } else {
            deportes.classList.add("hide");
        }
  }

  function showCheckMusicales() {
    var musicales = document.getElementById("musicales");

    if(musicales.classList.contains("hide")) {
            musicales.classList.remove("hide");
        } else {
            musicales.classList.add("hide");
        }
  }

});