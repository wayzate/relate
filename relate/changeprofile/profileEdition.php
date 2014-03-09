<?php
class ProfileEdition {

	var $MAX_AJOUT;
	var $NUM_SECTION;
	var $fgmembersite;

	function ProfileEdition($fgmembersite){
		$this->fgmembersite = $fgmembersite;
		$this->MAX_AJOUT = $fgmembersite->maxAjout;
		$this->NUM_SECTION = $fgmembersite->numSection;
		
	}
	
	function ChangeProfile() //ajout 01/12
	{
	
		/*if(!$this->fgmembersite->ValidateChangingProfileSubmission())  //à éditer
		 {
		return false;
		}*/


		if(empty($_POST["0_0_user_name"]))
		{
			$this->fgmembersite->HandleError("Case prénom vide !");
			return false;
		}
		$user_rec = array(array(array()));

		if(!$this->fgmembersite->GetProfileFromEmail($this->fgmembersite->UserEmail(),$user_rec))
		{
			return false;
		}
		
		

		/**
		 * Conversion des dates en format mySQL
		 */

		$dateList = array('student_birthdate', 'student_disponibility','AEtudieA_beggining','AEtudieA_end','ATravailleA_beggining','ATravailleA_end','AEteAPays_beggining','AEteAPays_end');

		// Date : Remplacement du jour non existant par '01', création du champ $_POST[$date] (ex : $_POST['birthdate'])
		//en format DATE(mySQL), suppression des dates type $_POST['birthdate_month']
		$this->formatDate($dateList);

		/**
		 * Suppression des valeurs du POST qui ne sont pas à changer dans la base de données
		 */

		//Tableaux de correspondance
		/*$fields = array(
		 //Champs des tables user et student
				'user_gender','user_surname','user_name','student_birthdate',
				'user_town','user_phonenumber','student_nationality','student_presentation',
				'student_seeking','student_disponibility','student_seekingDuration','student_seekingDomain',
					
				//Champs des autres tables de profil
				'establishment_name','diploma_name','town_name','AEtudieA_beggining','AEtudieA_end',
				'society_raisonSociale','proexperience_job',
				'ATravailleA_beggining','ATravailleA_end','proexperience_isASmallJob',
				'ATravailleA_mission','tongue_name','Parle_estimatedLevel'
		);*/

		//Retrait des valeurs non modifiées
		/*	foreach($fields as $value) {
		 $info = explode('_',$value);

		$i= 0; $j = 0;

		for($j=0; $j <= 8; $j++) {
		for($i=0; $i < $this->MAX_AJOUT; $i++) {
		if( isset($_POST["$i"."_"."$j"."_"."$value"]) && isset($user_rec[$i][$j][$info[0]][$info[1]]) && ($_POST["$i"."_"."$j"."_"."$value"] == $user_rec[$i][$j][$info[0]][$info[1]] ) ) {
		unset($_POST["$i"."_"."$j"."_"."$value"]);
		};
		}
		}
		}*/

		/**
		 * Mise en forme des variables :  $profile_vars[index][index][nomTable][nomChamps] = valeur et $delete[fromTable] = index
		 */

		//Construction de $delete
		$delete = array();
		$actionDelete = false;
		
		for($j=0; $j <= 8; $j++) {
			for($i = 0; $i < $this->MAX_AJOUT ;$i++)
			{
				if(isset($_POST['delete_'.$i.'_'.$j])) {
					$delete[$i][$j] = 1;					// 1 : valeur non utilisée 
					$actionDelete = true;
					unset($_POST['delete_'.$i.'_'.$j]);
				}
			}
		}
		
		unset($i,$j);

		if($actionDelete) {
			foreach($delete as $i => $val) {
				foreach($val as $j => $value) {
					$this->fgmembersite->DeleteInProfile($i,$j,$user_rec);

				}
			}
		}
		else {

			//Construction de $profile_vars

			foreach($_POST as $key => $value) {
				if($key != 'submitted' && $key != $this->fgmembersite->GetSpamTrapInputName() && $key !=$this->GetOk()
						&& $key != "Ajout")
				{
					//$info[0]:$i, $info[1]:$j,$info[2]:nomTable, $info[3]:nomChamps
					//Dans le POST les points sont remplacés par des _
					$info = explode('_',$key);
						
					if($value != "")
					{
						////var_dump($value, $info,$key);
						$profile_vars[$info[0]][$info[1]][$info[2]][$info[3]] = mysql_real_escape_string($value);
					}
				}
			}
			
			/*
			 * Envoi des requêtes de modification du profil
			 */
			//Début de la transaction
			if(!mysql_query("BEGIN;",$this->fgmembersite->connection)) {
				$this->HandleDBError("Echec dans le début de la transaction");;
			}

			if(!$this->fgmembersite->ChangeProfileInDB($user_rec,$profile_vars))
			{
				//ECHEC de la transaction
				if(!mysql_query("ROLLBACK;;",$this->fgmembersite->connection)) {
					$this->HandleDBError("Echec du rollback");;
				}
				return false;
			}
			else {
				//SUCCES de la transaction !
				if(!mysql_query("COMMIT;",$this->fgmembersite->connection)) {
					$this->HandleDBError("Echec du commit");
				}
			}
			


			return true;
		}
	}

	function GetOk()
	{
		return 'Valider';
	}

	function PopupFormation($user_rec, $i,$j) {

		$name = isset($user_rec[$i][$j]['establishment']['name'])?
		trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['establishment']['name']),ENT_QUOTES))
		:'';
		
		$dipname = isset($user_rec[$i][$j]['diploma']['name'])?
		trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['diploma']['name']),ENT_QUOTES))
		:'';
		$town = isset($user_rec[$i][$j]['town']['name'])?
		trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['town']['name']),ENT_QUOTES))
		:'';

		
		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

		echo "
		<a href=\"#?w=500\" rel=\"".$i."_formation\" class=\"poplight\" $idcss> $name </a>
		
		<div class=\"popup_block\" id=\"".$i."_formation\">
			<fieldset>
			<legend> $name </legend>";
			if($name == "Ajout") {
				$name ='';
			};
			echo"         
			<div class='container'>
				<label for='formname' >Nom de l'établissement</label>
				<input type='text' name='$i.$j.establishment.name' id='formname' value='".$name."' maxlength=\"128\" />				
				<br/>
				<span id='changeprofile_formname_errorloc' class='error'></span>
			</div>

			<div class='container'>

				<label for='formdipname' >Intitulé du diplôme</label>
				<input type='text' name='$i.$j.diploma.name' id='formdipname' value='".$dipname."' maxlength=\"256\" />
				<br/>
				<span id='changeprofile_formdipname_errorloc' class='error'></span>
		</div>

		<div class='container'>

				<label for='formtown' >Lieu</label>
				<input type='text' name='$i.$j.town.name' id='formtown' value='".$town."' maxlength=\"20\" />
				<br/>
				<span id='changeprofile_formtown_errorloc' class='error'></span>
		</div>
		
		<div class='container'>

				<label for='formbeggining' >Début  </label>
				<a id='marge'>
				";
		
				$this->fgmembersite->afficheMois($i,$j,'AEtudieA','beggining_month',$user_rec);
		
				$this->fgmembersite->afficheAnnee($i,$j,'AEtudieA','beggining_year',$user_rec);
		
		echo "	</a>
				<span id='changeprofile_formbeg_errorloc' class='error'></span>
		</div>

		<div class='container'>
			<label for='formend' >Fin  </label>
			<a id='marge'>
			";
		
			$this->fgmembersite->afficheMois($i,$j,'AEtudieA','end_month',$user_rec);
		
			$this->fgmembersite->afficheAnnee($i,$j,'AEtudieA','end_year',$user_rec);
		
			echo "
			</a>
			<span id='changeprofile_formend_errorloc' class='error'></span>
		</div>
		<input type='submit' name='Ajout' value='Ajouter' class='boutonvalider'/>
		</fieldset>
		</div>";
	}


	function PopupProexp($user_rec, $i, $j) {
		$name = isset($user_rec[$i][$j]['society']['raisonSociale']) ? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['society']['raisonSociale']),ENT_QUOTES)) :'';
		$namejob = isset($user_rec[$i][$j]['proexperience']['job'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['proexperience']['job']),ENT_QUOTES)) : '';
		$add = isset($user_rec[$i][$j]['ATravailleA']['mission'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['ATravailleA']['mission']),ENT_QUOTES)) : '';
		$town = isset($user_rec[$i][$j]['town']['name']) ? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['town']['name']),ENT_QUOTES)) : '';

		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

		echo "<a href=\"#?w=500\" rel=\"".$i."_exppro\" class=\"poplight\" $idcss> $name </a>
		<div class=\"popup_block\" id=\"".$i."_exppro\">
		<fieldset>
		<legend> $name </legend>";
		if($name == "Ajout") {
			$name = '';
		};

		echo  "
		<div class='container'>
			<label for='proexpname' >Nom de l'entreprise  </label>
			<input type='text' name='$i.$j.society.raisonSociale' value='$name' maxlength=\"32\"/><br/>
			<span id='changeprofile_proexpname_errorloc' class='error'></span>
		</div>

		<div class='container'>
			<label for='proexpnamejob' >Intitulé du poste  </label>
			<input type='text' name='$i.$j.proexperience.job' id='proexpnamejob' value='".$namejob."' maxlength=\"20\" /><br/>
			<span id='changeprofile_proexpnamejob_errorloc' class='error'></span>
		</div>

		<div class='container'>
			<label for='proexptown' >Lieu  </label>
			<input type='text' name='$i.$j.town.name' value='$town' maxlength=\"32\"/><br/>
			<span id='changeprofile_proexptown_errorloc' class='error'></span>
		</div>

		<div class='container'>
		<label for='proexpbeggining' >Début  </label>
		<a id='marge'>
				";
		
		$this->fgmembersite->afficheMois($i,$j,'ATravailleA','beggining_month',$user_rec);
		
		$this->fgmembersite->afficheAnnee($i,$j,'ATravailleA','beggining_year',$user_rec);
		
		echo "</a>
		<span id='changeprofile_proexpbeg_errorloc' class='error'></span>
		</div>

		<div class='container'>
		<label for='proexpend' >Fin  </label>
		<a id='marge'>
		";
		
		$this->fgmembersite->afficheMois($i,$j,'ATravailleA','end_month',$user_rec);
		
		$this->fgmembersite->afficheAnnee($i,$j,'ATravailleA','end_year',$user_rec);
		
		echo "
		</a>
		<span id='changeprofile_proexpend_errorloc' class='error'></span>
		</div>

		<div class='container'>

		<input type='checkbox' name='$i.$j.proexperience.isASmallJob' value='1' id='smalljob' ";
		if(isset($user_rec[$i][$j]['proexperience']['isASmallJob']) && $user_rec[$i][$j]['proexperience']['isASmallJob']=='1') echo '\"checked =\"checked\"';
		echo ">Petit boulot ?
		<span id='changeprofile_smalljob_errorloc' class='error'></span>
		</div>

		<div class='container'>
		<label for='proexpmission'> Expliquez votre mission </label> <br/>
		<textarea name=\"$i.$j.ATravailleA.mission\" id=\"proexpmission\" maxlength=\"2000\">".$add."</textarea>
		</div>
		<input type='submit' name='Ajout' value='Ajouter' class='boutonvalider'/>
		</fieldset>
		</div>";

	}

	function PopupInfo($user_rec, $i, $j) {

		$name = isset($user_rec[$i][$j]['infoLanguage']['name'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['infoLanguage']['name']),ENT_QUOTES)):'';
		$lvl = isset($user_rec[$i][$j]['SaitProgrammerEn']['level'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['SaitProgrammerEn']['level']),ENT_QUOTES)):'';

		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

		echo "<a href=\"#?w=500\" rel=\"".$i."_info\" class=\"poplight\" $idcss> $name </a>
		<div class=\"popup_block\" id=\"".$i."_info\">
		<fieldset>
		<legend> $name </legend>";
		if($name == "Ajout") {
			$name = "";
		};

		echo "			<div class='container'>

		<label for='infolanguages' >Language/Domaine  </label>
		<input type='text' name='$i.$j.infoLanguage.name' id='infolanguages' value='$name' maxlength='20' /><br/>
		<span id='changeprofile_infolanguages_errorloc' class='error'></span>
		</div>

		<div class='container'>
		<label for='infolanguageslvl' >Niveau  </label>
		<a id='marge'>
		<select name='$i.$j.SaitProgrammerEn.level'>
		<option value='' "; if($lvl == '') {
		echo "selected='selected'";
		}; echo ">Niveau</option>
		<option value='0' "; if($lvl == 'Basique') {
		echo "selected='selected'";
		}; echo ">Basique</option>
		<option value='1' "; if($lvl == 'Intermédiaire') {
		echo "selected='selected'";
		}; echo ">Intermédiaire</option>
		<option value='2' "; if($lvl == 'Avancé') {
		echo "selected='selected'";
		}; echo ">Avancé</option>
		<option value='3' "; if($lvl == 'Maîtrise') {
		echo "selected='selected'";
		}; echo ">Maîtrise</option>

		</select></a>
		<span id='changeprofile_infolanguageslvl_errorloc' class='error'></span>
		</div>
		<input type='submit' name='Ajout' value='Ajouter' class='boutonvalider'/>
		</fieldset>


		</div>

		<span id='changeprofile_info_errorloc' class='error'></span>";
	}
	
	function PopupSoftware($user_rec, $i, $j) {
	
		$name = isset($user_rec[$i][$j]['software']['name'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['software']['name']),ENT_QUOTES)):'';
		$lvl = isset($user_rec[$i][$j]['SaitUtiliserLogiciel']['level'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['SaitUtiliserLogiciel']['level']),ENT_QUOTES)):'';
	
		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};
	
		echo "<a href=\"#?w=500\" rel=\"".$i."_software\" class=\"poplight\" $idcss> $name </a>
		<div class=\"popup_block\" id=\"".$i."_software\">
		<fieldset>
		<legend> $name </legend>";
		if($name == "Ajout") {
		$name = "";
		};
	
		echo "			<div class='container'>
	
		<label for='software' >Logiciel </label>
		<input type='text' name='$i.$j.software.name' id='infolanguages' value='$name' maxlength='20' />				<br/>
		<span id='changeprofile_software_errorloc' class='error'></span>
		</div>
	
		<div class='container'>
		<label for='softwarelvl' >Niveau  </label>
		<a id='marge'>
		<select name='$i.$j.SaitUtiliserLogiciel.level'>
		<option value='' "; if($lvl == '') {
		echo "selected='selected'";
		}; echo ">Niveau</option>
		<option value='Basique' "; if($lvl == 'Basique') {
		echo "selected='selected'";
		}; echo ">Basique</option>
		<option value='Intermédiaire' "; if($lvl == 'Intermédiaire') {
		echo "selected='selected'";
		}; echo ">Intermédiaire</option>
		<option value='Avancé' "; if($lvl == 'Avancé') {
		echo "selected='selected'";
		}; echo ">Avancé</option>
		<option value='Maîtrise' "; if($lvl == 'Maîtrise') {
		echo "selected='selected'";
		}; echo ">Maîtrise</option>
	
		</select></a>
		<span id='changeprofile_softwarelvl_errorloc' class='error'></span>
		</div>
		<input type='submit' name='Ajout' value='Ajouter' class='boutonvalider'/>
		</fieldset>
	
	
		</div>
	
		<span id='changeprofile_software_errorloc' class='error'></span>";
	}

	function PopupTongue($user_rec, $i, $j) {

		$name = isset($user_rec[$i][$j]['tongue']['name'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['tongue']['name']),ENT_QUOTES)):'';
		$lvl =  isset($user_rec[$i][$j]['Parle']['estimatedLevel'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['Parle']['estimatedLevel']),ENT_QUOTES)):'';

		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

		echo "<a href=\"#?w=500\" rel=\"".$i."_tongue\" class=\"poplight\" $idcss> $name </a>
		<div class=\"popup_block\" id=\"".$i."_tongue\">
		<fieldset>
		<legend> $name </legend>";
		if($name == "Ajout") {
			$name = "";
		};

		echo '			<div class="container">

		<label for="tonguename" >Langue  </label>
		<input type="text" name="'.$i.'.'.$j.'.tongue.name" id="tonguename" value="'.$name.'" maxlength="20" />				<br/>
		<span id="changeprofile_tonguename_errorloc" class="error"></span>
		</div>

		<div class="container">
		<label for="tonguelvl" >Niveau  </label>
		<a id="marge">
		<select name="'.$i.'.'.$j.'.Parle.estimatedLevel">
		<option value=""'; if($lvl == "") {
		echo 'selected="selected"';
		}; echo '>Niveau</option>
		<option value="0"'; if($lvl == "0") {
		echo 'selected="selected"';
		}; echo '>Notions</option>
		<option value="1" '; if($lvl == "1") {
		echo 'selected="selected"';
		}; echo '>Intermédiaire</option>
		<option value="2"'; if($lvl == "2") {
		echo 'selected="selected"';
		}; echo '>Courant</option>
		<option value="3"'; if($lvl == "3") {
		echo 'selected="selected"';
		}; echo '>Bilingue</option>
		<option value="4"'; if($lvl == "4") {
		echo 'selected="selected"';
		}; echo '>Maternelle</option>

		</select></a>
		<span id="changeprofile_tonguelvl_errorloc" class="error"></span>
		</div>
		<input type="submit" name="Ajout" value="Ajouter" class="boutonvalider"/>
		</fieldset>


		</div>

		<span id="changeprofile_tongues_errorloc" class="error"></span>';
	}

	function PopupSport($user_rec,$i,$j) {

	$name = isset($user_rec[$i][$j]['sport']['name'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['sport']['name']),ENT_QUOTES)) : "";
	$duration = isset($user_rec[$i][$j]['APratiqueSport']['duration'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['APratiqueSport']['duration']),ENT_QUOTES)) : "";
	$qual = isset($user_rec[$i][$j]['APratiqueSport']['quality'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['APratiqueSport']['quality']),ENT_QUOTES)) : "";
	$addsth = isset($user_rec[$i][$j]['APratiqueSport']['addSth'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['APratiqueSport']['addSth']),ENT_QUOTES)) : "";

		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

	echo "<a href=\"#?w=500\" rel=\"".$i."_sport\" class=\"poplight\" $idcss> $name </a>
	<div class=\"popup_block\" id=\"".$i."_sport\">
	<fieldset>
	<legend> $name </legend>";
	if($name == "Ajout") {$name = "";};

	echo '             <div class="container">
	<label for="sportname" >Nom du sport  </label>
	<input type="text" name="'.$i.'.'.$j.'.sport.name" id="sportname" value="'.$name.'" maxlength="20" />				<br/>
	<span id="changeprofile_sportname_errorloc" class="error"></span>
	</div>

	<div class="container">
	<label for="sportduration" >Durée  </label>
	<input type="text" name="'.$i.'.'.$j.'.APratiqueSport.duration" id="sportduration" value="'.$duration.'" maxlength="20" />
	<span id="changeprofile_sportduration_errorloc" class="error"></span>
	</div>

	<div class="container">
	<label for="sportqual"> Quelle qualité en-as tu tiré ?</label> <br/>
	<textarea name="'.$i.'.'.$j.'.APratiqueSport.quality" id="sportqual" maxlength="2000">'.$qual.'</textarea>
	</div>

	<div class="container">
	<label for="sportaddsth"> Envie d\'en dire plus ?</label> <br/>
	<textarea name="'.$i.'.'.$j.'.APratiqueSport.addSth" id="sportaddsth" maxlength="2000">'.$addsth.'</textarea>
	</div>
	<input type="submit" name="Ajout" value="Ajouter" class="boutonvalider"/>
	</fieldset>

	</div>

	<span id="changeprofile_sportaddsth_errorloc" class="error"></span>';

	}

	function PopupArt($user_rec,$i,$j) {

	$name = isset($user_rec[$i][$j]['art']['name'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['art']['name']),ENT_QUOTES)) : "";
	$duration = isset($user_rec[$i][$j]['APratiqueArt']['duration'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['APratiqueArt']['duration']),ENT_QUOTES)) : "";
	$qual = isset($user_rec[$i][$j]['APratiqueArt']['quality'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['APratiqueArt']['quality']),ENT_QUOTES)) : "";
	$addsth = isset($user_rec[$i][$j]['APratiqueArt']['addSth'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['APratiqueArt']['addSth']),ENT_QUOTES)) : "";
		
		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

	echo "<a href=\"#?w=500\" rel=\"".$i."_art\" class=\"poplight\" $idcss> $name </a>
	<div class=\"popup_block\" id=\"".$i."_art\">
	<fieldset>
	<legend> $name </legend>";
	if($name == "Ajout") {$name = "";};


	echo '			<div class="container">

	<label for="artname" >Nom de l\'activité  </label>
	<input type="text" name="'.$i.'.'.$j.'.art.name" id="artname" value="'.$name.'" maxlength="20" />				<br/>
	<span id="changeprofile_artname_errorloc" class="error"></span>
	</div>

	<div class="container">
	<label for="artduration" >Durée  </label>
	<input type="text" name="'.$i.'.'.$j.'.APratiqueArt.duration" id="artduration" value="'.$duration.'" maxlength="20" />
	<span id="changeprofile_artduration_errorloc" class="error"></span>
	</div>

	<div class="container">
	<label for="artqual"> Quelle qualité en-as tu tiré ?</label><br/> 
	<textarea name="'.$i.'.'.$j.'.APratiqueArt.quality" id="artqual" maxlength="2000">'.$qual.'</textarea>
	</div>

	<div class="container">
	<label for="artaddsth"> Envie d\'en dire plus ?</label><br/> 
	<textarea name="'.$i.'.'.$j.'.APratiqueArt.addSth" id="artaddsth" maxlength="2000">'.$addsth.'</textarea>
	</div>
	<input type="submit" name="Ajout" value="Ajouter" class="boutonvalider"/>
	</fieldset>
	</form>

	</div>

	<span id="changeprofile_arts_errorloc" class="error"></span>';


	}

	function PopupAssoc($user_rec,$i,$j) {

	$name = isset($user_rec[$i][$j]['association']['name'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['association']['name']),ENT_QUOTES)) : "";
	$goals = isset($user_rec[$i][$j]['association']['goals'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['association']['goals']),ENT_QUOTES)) : "";
	$budget = isset($user_rec[$i][$j]['association']['budget'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['association']['budget']),ENT_QUOTES)) : "";
	$numberOfMembers = isset($user_rec[$i][$j]['association']['numberOfMembers'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['association']['numberOfMembers']),ENT_QUOTES)) : "";
	$role = isset($user_rec[$i][$j]['AFaitPartieDeAssociation']['role'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['AFaitPartieDeAssociation']['role']),ENT_QUOTES)) : "";
	$quality = isset($user_rec[$i][$j]['AFaitPartieDeAssociation']['quality'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['AFaitPartieDeAssociation']['quality']),ENT_QUOTES)) : "";
	$description = isset($user_rec[$i][$j]['AFaitPartieDeAssociation']['description'])? trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['AFaitPartieDeAssociation']['description']),ENT_QUOTES)) : "";
	
		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

	echo "<a href=\"#?w=500\" rel=\"".$i."_assoc\" class=\"poplight\" $idcss> $name </a>
	<div class=\"popup_block\" id=\"".$i."_assoc\">
	<fieldset>
	<legend> $name </legend>";
	if($name == "Ajout") {$name = "";};


	echo '			
	<div class="container">
	<label for="assocname" >Nom complet de l\'association  </label>
	<input type="text" name="'.$i.'.'.$j.'.association.name" id="assocname" value="'.$name.'" maxlength="20" />				<br/>
	<span id="changeprofile_assocname_errorloc" class="error"></span>
	</div>
	
	<div class="container">
	<label for="assocname" >But(s) de l\'association </label>
	<input type="text" name="'.$i.'.'.$j.'.association.goals" id="assocname" value="'.$goals.'" maxlength="200" />				<br/>
	<span id="changeprofile_assocname_errorloc" class="error"></span>
	</div>
	
	<div class="container">
	<label for="assocname" >Budget l\'association  </label>
	<input type="text" name="'.$i.'.'.$j.'.association.budget" id="assocname" value="'.$budget.'" maxlength="20" />				<br/>
	<span id="changeprofile_assocname_errorloc" class="error"></span>
	</div>
	
	<div class="container">
	<label for="assocname" >Nombre de membres  </label>
	<input type="text" name="'.$i.'.'.$j.'.association.numberOfMembers" id="assocname" value="'.$numberOfMembers.'" maxlength="6" />				<br/>
	<span id="changeprofile_assocname_errorloc" class="error"></span>
	</div>

	<div class="container">
	<label for="assocduration" >Rôle/Poste dans l\'association  </label>
	<input type="text" name="'.$i.'.'.$j.'.AFaitPartieDeAssociation.role" id="assocrole" value="'.$role.'" maxlength="64" />
	<span id="changeprofile_assocrole_errorloc" class="error"></span>
	</div>

	<div class="container">
	<label for="assocdescr"> Décrivez votre activité au sein de l\'association</label><br/> 
	<textarea name="'.$i.'.'.$j.'.AFaitPartieDeAssociation.description" id="assocdescr" maxlength="2000">'.$description.'</textarea>
	</div>

	<div class="container">
	<label for="assocqual"> Quelle qualité en-avez-vous tiré ?</label><br/> 
	<textarea name="'.$i.'.'.$j.'.AFaitPartieDeAssociation.quality" id="assocqual" maxlength="2000">'.$quality.'</textarea>
	</div>


	<input type="submit" name="Ajout" value="Ajouter" class="boutonvalider"/>
	</fieldset>
	</form>

	</div>

	<span id="changeprofile_assocs_errorloc" class="error"></span>';


	}

	function PopupTravel($user_rec, $i, $j) {

	$name = isset($user_rec[$i][$j]['country']['name'])?trim(htmlspecialchars(stripslashes($user_rec[$i][$j]['country']['name']),ENT_QUOTES)):"";

		$idcss = "";
		if($name == '') {
			$name = "Ajout";
			$idcss = "id = 'ajout'";
		};

	echo "<a href=\"#?w=500\" rel=\"".$i."_travel\" class=\"poplight\" $idcss> $name </a>
	<div class=\"popup_block\" id=\"".$i."_travel\">
	<fieldset>
	<legend> $name </legend>";
	if($name == "Ajout") {$name = "";};

	echo  "<div class='container'>
	<label for='travelplace' >Lieu  </label>
	<input type='text' name='$i.$j.country.name' id='travelplace' value='".$name."' maxlength=\"20\" />				<br/>
	<span id='changeprofile_travelplace_errorloc' class='error'></span>
	</div>

	<div class='container'>
	<label for='travelbeggining' >Début  </label>
	<a id='marge'>
		";
		
		$this->fgmembersite->afficheMois($i,$j,'AEteAPays','beggining_month',$user_rec);
		
		$this->fgmembersite->afficheAnnee($i,$j,'AEteAPays','beggining_year',$user_rec);
		
		echo "</a></a>
	<span id='changeprofile_travelbeg_errorloc' class='error'></span>
	</div>

	<div class='container'>
	<label for='travelend' >Fin  </label>
	<a id='marge'>
	";
		
		$this->fgmembersite->afficheMois($i,$j,'AEteAPays','end_month',$user_rec);
		
		$this->fgmembersite->afficheAnnee($i,$j,'AEteAPays','end_year',$user_rec);
		
		echo "</a>
	
	<span id='changeprofile_travelend_errorloc' class='error'></span>
	</div>

	<div class='container'>
	<input type='submit' name='Ajout' value='Ajouter' class='boutonvalider'/>
	</fieldset>
	</div>";

	}
	
	function formatDate($dateList) {
		foreach($dateList as $date){
			$i=0;
			$j=0;		//$j est un indice servant à identifier le type d'ajout : 0=formation, 1=experience professionnelle...

			for($i=0;$i < $this->MAX_AJOUT;$i++) {
				for($j=0;$j<=8;$j++) {
					if (isset($_POST["$i"."_"."$j"."_"."$date".'_month']) && isset($_POST["$i"."_"."$j"."_"."$date".'_year'])
							&& $_POST["$i"."_"."$j"."_"."$date".'_month'] != '' && $_POST["$i"."_"."$j"."_"."$date".'_month'] != '') {

						if (isset($_POST["$i"."_"."$j"."_"."$date".'_day']))
						{
							$day = $_POST["$i"."_"."$j"."_"."$date".'_day'];
							unset($_POST["$i"."_"."$j"."_"."$date".'_day']);
						}
						else
						{
							$day = '01';
						}

						$year = $_POST["$i"."_"."$j"."_"."$date".'_year'];
						$month =$_POST["$i"."_"."$j"."_"."$date".'_month'];
						unset($_POST["$i"."_"."$j"."_"."$date".'_year'],$_POST["$i"."_"."$j"."_"."$date".'_month']);


						$_POST["$i"."_"."$j"."_"."$date"] = $year.'-'.$month.'-'.$day;
						////var_dump($_POST["$i"."_"."$j"."_"."$date"]);

					}
				}
			}
		}
	}
}

// Fin de la classe ProfileEdition

?>