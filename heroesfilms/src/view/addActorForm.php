<script>
 
  var counter = 4;
  var limit = 9;
  function addInput(divName){
       if (counter == limit)  {
            alert("You have reached the limit of inputs.");
            document.getElementById("creer").style.display = "none";
       }
       else {
            var newdiv = document.createElement('div');
            newdiv.classList.add('form-group');
            newdiv.innerHTML =  '<input id="actor'+(counter)+'" spellcheck=false class="form-control" name="casting[]" type="text" alt="film"required=""><span class="form-highlight"></span><span class="form-bar"></span><label for="actor1" class="float-label">Actor ' + (counter) + ' :</label>';
            document.getElementById(divName).appendChild(newdiv);
            counter++;
       }
  }

</script>

<div class="form-group">
  <input id="actor1" spellcheck=false class="form-control" name="casting[]" type="text" alt="film"required="">
  <span class="form-highlight"></span>
  <span class="form-bar"></span>
  <label for="actor1" class="float-label">Actor 1 :</label>
</div>

<div class="form-group">
  <input id="actor2" spellcheck=false class="form-control" name="casting[]" type="text" alt="film" required="">
  <span class="form-highlight"></span>
  <span class="form-bar"></span>
  <label for="actor2" class="float-label">Actor 2 :</label>
</div>

<div class="form-group">
  <input id="actor3" spellcheck=false class="form-control" name="casting[]" type="text" alt="film" required="">
  <span class="form-highlight"></span>
  <span class="form-bar"></span>
  <label for="actor3" class="float-label">Actor 3 :</label>
</div>

<div id="dynamicInput"></div>


<div class="form-group">
  <input class="buttonActor" id="creer" type="button" value="Add an actor" onClick="addInput('dynamicInput');">
  <br>
</div>