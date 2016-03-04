 <script>
   
    var counter = 1;
    var limit = 7;
    function addInput(divName){
         if (counter == limit)  {
              alert("You have reached the limit of adding " + counter + " inputs");
         }
         else {
              var newdiv = document.createElement('label');
              newdiv.innerHTML =  '<p><span class="titrelabel"> Acteur ' +  (counter + 1) + '</span><input type="text" name="castRef" placeholder="Nom d\'acteur" value="" /></p>';
              document.getElementById(divName).appendChild(newdiv);
              counter++;
         }
    }

 </script>


         <p id="dynamicInput">
              <label><span class="titrelabel">Acteur 1 : </span><input type="text" name="castRef" placeholder="Nom acteur" value="" />
         </p>
         <input type="button" value="Add champ" onClick="addInput('dynamicInput');">
