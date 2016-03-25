<script>
 
  var counter = 4;
  var limit = 9;
  function addInput(divName){
       if (counter == limit)  {
            alert("You have reached the limit of inputs.");
       }
       else {
            var newdiv = document.createElement('label');
            newdiv.innerHTML =  '<p><span class="titrelabel"> Acteur ' +  (counter) + '</span><input type="text" name="casting[]" placeholder="Nom d\'acteur" value="" /></p>';
            document.getElementById(divName).appendChild(newdiv);
            counter++;
       }
  }

</script>

<p>
  <label><span class="titrelabel">Acteur 1 : </span><input type="text" name="casting[]" placeholder="Nom acteur" value="" />
</p>
<p>
  <label><span class="titrelabel">Acteur 2 : </span><input type="text" name="casting[]" placeholder="Nom acteur" value="" /></label>
<p>
<p>
  <label><span class="titrelabel">Acteur 3 : </span><input type="text" name="casting[]" placeholder="Nom acteur" value="" /></label>
<p>
<p id="dynamicInput">
  
</p>

<input type="button" value="Add champ" onClick="addInput('dynamicInput');">
