<?=$texte?>

<p><label><span class="titrelabel">Nom du film : </span><input type="text" name= <?php echo '"' . $nameRef . '"' ?> class="form-control" value="';
		//<input id="username" spellcheck=false class="form-control" name="username" type="email" size="20" alt="login" required="">
		$s .= self::htmlesc($builder->getData($nameRef)) . "\" />";
		$err = $builder->getErrors($nameRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$realRef = $builder->getRealisateurRef();
		$s .= '<p><label><span class="titrelabel">Realisateur : </span><input type="text" name="'.$realRef.'" value="';
		$s .= self::htmlesc($builder->getData($realRef));
		$s .= "\" />";
		$err = $builder->getErrors($realRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dateRef = $builder->getDateSortieRef();
		$s .= '<p><label><span class="titrelabel">Date de sortie : </span><input type="date" name="'.$dateRef.'" placeholder="AAAA-MM-JJ" value="';
		$s .= self::htmlesc($builder->getData($dateRef));
		$s .= "\" />";
		$err = $builder->getErrors($dateRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$poster = $builder->getPosterRef();
		$s .= '<p><label><span class="titrelabel">Lien URL de l\'affiche : </span><input type="date" name="'.$poster.'" value="';
		$s .= self::htmlesc($builder->getData($poster));
		$s .= "\" />";
		$err = $builder->getErrors($poster);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$dureeRef = $builder->getDureeRef();
     	

		$s .= '<p><label><span class="titrelabel">Duree : </span><input type="date" name="'.$dureeRef.'" value="';
		$s .= self::htmlesc($builder->getData($dureeRef));
		$s .= "\" />";
		$err = $builder->getErrors($dureeRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$castRef = $builder->getCastingRef();
		

		ob_start();
		include 'addActorForm.php';
		$s .= ob_get_clean(); //note on ob_get_contents below
	
	/*
		$s .= '<div id="dynamicInput">
				<p><label><span class="titrelabel">Casting : </span>
					<input type="int" name="'.$dureeRef.'" placeholder="Acteur" value="' . self::htmlesc($builder->getData($castRef)) . "\" />
				</div>
				<input type='button' value='Add another text input' onClick=\"addInput('dynamicInput');\">";
	*/
		$err = $builder->getErrors($castRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";


		$universRef = $builder->getUniversRef();
		$s .= '<p><span class="titrelabel"> Univers : </span><select name="'.$universRef.'">';
		$s .= '<option value="Marvel">Marvel</option>';
		$s .= '<option value="DC Comics">DC Comics</option>';
		$s .= '<option value="Autre">Autres</option>';
		$s .= '</select>';
		$s .= self::htmlesc($builder->getData($universRef));
		$err = $builder->getErrors($universRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";

		$genreRef = $builder->getGenreRef();
		//$s .= '<p><label>Genre du film : <input type="date" name="'.$genreRef.'" value="' ;
		$s .= '<P><label><span class="titrelabel">Indiquez les genres : </span>';
		$s .= '	<LABEL ACCESSKEY=C><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="1" CHECKED> Action </LABEL>' ;
		$s .= '	<LABEL ACCESSKEY=D><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="2"> Aventure </LABEL>';
		$s .= '	<LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="3"> Comédie </LABEL>';
		$s .= ' <LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="4"> Sci-Fi </LABEL>';
		$s .= ' <LABEL ACCESSKEY=M><INPUT TYPE=checkbox name="'.$genreRef.'" VALUE="5"> Fantasy </LABEL>';
		$s .= '</label></P>';
		$s .= self::htmlesc($builder->getData($genreRef));
		$err = $builder->getErrors($genreRef);
		if ($err !== null)
			$s .= ' <span class="error">'.$err.'</span>';
		$s .="</label></p>\n";