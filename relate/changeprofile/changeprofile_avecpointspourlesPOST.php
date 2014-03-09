<?PHP
require_once("../include/membersite_config.php");
require_once("../upload/maxImageUpload.class.php");


class ProfileEdition {

  var $MAX_AJOUT;
	  
  function ChangeProfile($fgmembersite) //ajout 01/12
  {
  	$this->MAX_AJOUT = 20;

  	if(!$fgmembersite->ValidateChangingProfileSubmission())  //à éditer
  	{
  		return false;
  	}


  	/*if(empty($_POST["0.0.user.name"]))
  	{
  		$fgmembersite->HandleError("Case prénom vide !");
  		return false;
  	}*/
  	$user_rec = array(array(array()));

  	if(!$fgmembersite->GetProfileFromEmail($fgmembersite->UserEmail(),$user_rec,$this->MAX_AJOUT))
  	{
  		return false;
  	}
  	 
  	/**
  	 * Conversion des dates en format mySQL
  	 */
  	 
  	$dateList = array('student.birthdate', 'student.disponibility','AEtudieA.beggining','AEtudieA.end','ATravailleA.beggining','ATravailleA.end');

  	// Date : Remplacement du jour non existant par '01', création du champ $_POST[$date] ($_POST['birthdate'] 
  	//par exemple en format DATE(mySQL), suppression des dates fractionnées type $_POST['birthdate_month']
  	foreach($dateList as $date){
  		$i = 0;
  		$j=0;		//$j est un indice servant à identifier le type d'ajout : 0=formation, 1=experience professionnelle...

  		foreach($dateList as $date){
  			 
  			while(isset($_POST["$i.$j.$date".'_year'])){


  				if (isset($_POST["$i.$j.$date".'_day'])){
  					$day = $_POST["$i.$j.$date".'_day'];
  					unset($_POST["$i.$j.$date".'_day']);
  				}
  				else {
  					$day = '01';
  				}
  				$year = $_POST["$i.$j.$date".'_year'];
  				$month = $_POST["$i.$j.$date".'_month'];

  				$_POST[$date] = $year.'-'.$month.'-'.$day;

  				unset($_POST["$i.$j.$date".'_year'],$_POST["$i.$j.$date".'_month']);
  			}
  		}
  	}
  	 
  	/**
  	 * Suppression des valeurs du POST qui ne sont pas à changer dans la base de données
  	 */
  	 
  	//Tableaux de correspondance
  	$fields = array(
  			//Champs des tables user et student
  			'user.gender','user.surname','user.name','student.birthdate',
  			'user.town','user.phonenumber','student.nationality','student.presentation',
  			'student.seeking','student.disponibility','student.seekingDuration','student.seekingDomain',
  			
  			//Champs des autres tables de profil
  			'establishment.name','diploma.name','town.name','AEtudieA.beggining','AEtudieA.end',
  			'society.raisonSociale','proexperience.job',
  			'ATravailleA.beggining','ATravailleA.end','proexperience.isASmallJob',
  			'ATravailleA.mission','tongue.name','Parle.estimatedLevel'
  	);
  	 
  	//Retrait des valeurs non modifiées
  	foreach($fields as $value) {
  		$info = explode('.',$value);
  		
  		$i= 0; $j = 0;
		
  		for($j=0; $j < 8; $j++) {
  			for($i=0; $i < $this->MAX_AJOUT; $i++) {
  				if( isset($_POST["$i.$j.$value"]) && ($_POST["$i.$j.$value"] == $user_rec[$i][$j][$info[0]][$info[1]]) ) {
  					unset($_POST["$i.$j.$value"]);
  				};
  			}
  		}
  	}

  	/**
  	 * Mise en forme des variables :  $profile_vars[nomTable][nomChamps][index] = valeur et $delete[fromTable] = index
  	 */

  	//Construction de $delete
  	$delete = array(array());
  	for($i = 0; $i < $this->MAX_AJOUT ;$i++)
  	{
  		$delete[$i][$j] = (isset($_POST['delete_'.$i.'_'.$j]))?1:0;
  	}


  	//Construction de $profile_vars
  	$profile_vars = array(array(array(array())));
  	
  	foreach($_POST as $key => $value) {
  		//Pour tester ensuite si $key est du genre delete_formation_0 et ne pas le copier dans profile_vars
  		$array_testdel = (explode('_',$key));			

  		if	($key != 'submitted' && $key != $fgmembersite->GetSpamTrapInputName() && $key !=$this->GetOk()
  				&& $key != "Ajout" && $array_testdel[0] != "delete")
  		{
  			//$info[0]:$i, $info[1]:$j,$info[2]:nomTable, $info[3]:nomChamps
  			$info = explode('_',$key); //Dans le POST les points sont remplacés par des _
  			
  			$profile_vars[$info[0]][$info[1]][$info[2]]['id'] = 
  				isset($user_rec[$info[0]][$info[1]][$info[2]]['id'])?$user_rec[$info[0]][$info[1]][$info[2]]['id']:'new';
  			
  			$profile_vars[$info[0]][$info[1]][$info[2]][$info[3]] = $value;
  		}
  	}

  	var_dump($profile_vars);
  	
  	if(!$fgmembersite->ChangeProfileInDB($user_rec,$profile_vars,$delete,$this->MAX_AJOUT))
  	{
  		return false;
  	}

  	return true;
  }
 
	function GetOk()
	{
		return 'Valider';
	}

	function PopupFormation($userrec, $i,$j) {
	

	$name = isset($userrec[$i][$j]['establishment']['name'])?
				trim(htmlspecialchars(stripslashes($userrec[$i][$j]['establishment']['name']),ENT_QUOTES))
				:'';
	$dipname = isset($userrec[$i][$j]['diploma']['name'])?
				trim(htmlspecialchars(stripslashes($userrec[$i][$j]['diploma']['name']),ENT_QUOTES))
				:'';
	$town = isset($userrec[$i][$j]['town']['name'])?
				trim(htmlspecialchars(stripslashes($userrec[$i][$j]['town']['name']),ENT_QUOTES))
				:'';

	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_formation\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_formation\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {$name ='';}; 
   echo"         <div class='container'>	
				   <label for='formname' >Nom de l'établissement : </label><br/>
				  <input type='text' name='$i.$j.establishment.name' id='formname' value='".$name."' maxlength=\"20\" />				<br/>
				  <span id='changeprofile_formname_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
					
				   <label for='formdipname' >Intitulé du diplôme : </label><br/>
				  <input type='text' name='$i.$j.diploma.name' id='formdipname' value='".$dipname."' maxlength=\"20\" /><br/>
				  <span id='changeprofile_formdipname_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
					
				   <label for='formtown' >Lieu : </label><br/>
				  <input type='text' name='$i.$j.town.name' id='formtown' value='".$town."' maxlength=\"20\" /><br/>
				  <span id='changeprofile_formtown_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
			  <label for='formbeggining' >Début : </label><br/>
					<select name='$i.$j.AEtudieA.beggining_month' onChange=\"changeDate(this.options[selectedIndex].value);\">
						<option value=\"na\" ";
						
						$mois = isset($userrec['AEtudieA']['beggining_month'][$i])?$userrec['AEtudieA']['beggining_month'][$i]:'';
						
						if($mois == "na") {echo 'selected=\"selected\"';};
						echo ">Mois</option>
						<option value=\"1\" ";
						if($mois == "1") {echo 'selected=\"selected\"';};
						echo ">Janvier</option>
						<option value=\"2\" ";
						if($mois == "2") {echo 'selected=\"selected\"';};
						echo ">Février</option>
						<option value=\"3\" ";
						if($mois == "3") {echo 'selected=\"selected\"';};
						echo ">Mars</option>
						<option value=\"4\" ";
						if($mois == "4") {echo 'selected=\"selected\"';};
						echo ">Avril</option>
						<option value=\"5\" ";
						if($mois == "5") {echo 'selected=\"selected\"';};
						echo ">Mai</option>
						<option value=\"6\" ";
						if($mois == "6") {echo 'selected=\"selected\"';};
						echo ">Juin</option>
						<option value=\"7\" ";
						if($mois == "7") {echo 'selected=\"selected\"';};
						echo ">Juillet</option>
						<option value=\"8\" ";
						if($mois == "8") {echo 'selected=\"selected\"';};
						echo ">Août</option>
						<option value=\"9\" ";
						if($mois == "9") {echo 'selected=\"selected\"';};
						echo ">Septembre</option>
						<option value=\"10\" ";
						if($mois == "10") {echo 'selected=\"selected\"';};
						echo ">Octobre</option>
						<option value=\"11\" ";
						if($mois == "11") {echo 'selected=\"selected\"';};
						echo ">Novembre</option>
						<option value=\"12\" ";
						if($mois == "12") {echo 'selected=\"selected\"';};
						echo ">Décembre</option>
						
					</select>
					<select name='$i.$j.AEtudieA.beggining_year' id=\"year\">
					<option value=\"na\">Année</option>";
					
						for($k = 2018;$k > 1950;$k--) {
							$string ='';
							if($userrec[$i][$j]['AEtudieA']['beggining_year'] == "$k") {$string = 'selected=\"selected\"';}
							echo "<option value=\"$k\" $string>$k</option>\n";		
						};
					
					echo "</select>
			 <span id='changeprofile_formbeg_errorloc' class='error'></span>
			</div>
			
			 <div class='container'>
			  <label for='formend' >Fin : </label><br/>
					<select name='$i.$j.AEtudieA.end_month' onChange=\"changeDate(this.options[selectedIndex].value);\">
						<option value=\"na\" ";
						$mois = isset($userrec[$i][$j]['AEtudieA']['end_month'])?$userrec[$i][$j]['AEtudieA']['end_month']:'';
						if($mois == "na") {echo 'selected=\"selected\"';};
						echo ">Mois</option>
						<option value=\"1\" ";
						if($mois == "1") {echo 'selected=\"selected\"';};
						echo ">Janvier</option>
						<option value=\"2\" ";
						if($mois == "2") {echo 'selected=\"selected\"';};
						echo ">Février</option>
						<option value=\"3\" ";
						if($mois == "3") {echo 'selected=\"selected\"';};
						echo ">Mars</option>
						<option value=\"4\" ";
						if($mois == "4") {echo 'selected=\"selected\"';};
						echo ">Avril</option>
						<option value=\"5\" ";
						if($mois == "5") {echo 'selected=\"selected\"';};
						echo ">Mai</option>
						<option value=\"6\" ";
						if($mois == "6") {echo 'selected=\"selected\"';};
						echo ">Juin</option>
						<option value=\"7\" ";
						if($mois == "7") {echo 'selected=\"selected\"';};
						echo ">Juillet</option>
						<option value=\"8\" ";
						if($mois == "8") {echo 'selected=\"selected\"';};
						echo ">Août</option>
						<option value=\"9\" ";
						if($mois == "9") {echo 'selected=\"selected\"';};
						echo ">Septembre</option>
						<option value=\"10\" ";
						if($mois == "10") {echo 'selected=\"selected\"';};
						echo ">Octobre</option>
						<option value=\"11\" ";
						if($mois == "11") {echo 'selected=\"selected\"';};
						echo ">Novembre</option>
						<option value=\"12\" ";
						if($mois == "12") {echo 'selected=\"selected\"';};
						echo ">Décembre</option>
						
					</select>
					<select name='$i.$j.AEtudieA.end_year' id=\"year\">
					<option value=\"na\">Année</option>";

	for($k = 2018;$k > 1950;$k--) {
		$string ='';
		if($userrec[$i][$j]['AEtudieA']['end_year'] == "$k") {$string = 'selected="selected"';}
		echo "<option value=".$k.'.'." $string>$k</option>\n";		
	};
	
	echo "				</select>
			 <span id='changeprofile_formend_errorloc' class='error'></span>
			</div>
			<input type='submit' name='Ajout' value='Ajouter'/>
			</fieldset>
	</div>";
	}


	function PopupProexp($userrec, $i, $j) {
	$name = isset($userrec[$i][$j]['society']['name']) ? trim(htmlspecialchars(stripslashes($userrec[$i][$j]['society']['name']),ENT_QUOTES)) :'';
	$namejob = isset($userrec[$i][$j]['proexperience']['job'])?trim(htmlspecialchars(stripslashes($userrec[$i][$j]['proexperience']['job']),ENT_QUOTES)) : '';
	$add = isset($userrec[$i][$j]['ATravailleA']['mission'])?trim(htmlspecialchars(stripslashes($userrec[$i][$j]['ATravailleA']['mission']),ENT_QUOTES)) : '';
	$town = isset($userrec[$i][$j]['town']['name']) ? trim(htmlspecialchars(stripslashes($userrec[$i][$j]['town']['name']),ENT_QUOTES)) : '';
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_exppro\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_exppro\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 
  
   			echo  "<div class='container'>	
				   <label for='proexpname' >Nom de l'entreprise : </label><br/>
				  <input type='text' name='$i.$j.society.raisonSociale' id='proexpname' value='".$name."' maxlength=\"20\" />				<br/>
				  <span id='changeprofile_proexpname_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
					
				   <label for='proexpnamejob' >Intitulé du poste : </label><br/>
				  <input type='text' name='$i.$j.proexperience.job' id='proexpnamejob' value='".$namejob."' maxlength=\"20\" /><br/>
				  <span id='changeprofile_proexpnamejob_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
					
				   <label for='proexptown' >Lieu : </label><br/>
				  <input type='text' name='$i.$j.proexperience.town' id='proexptown' value='".$town."' maxlength=\"20\" /><br/>
				  <span id='changeprofile_proexptown_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
			  <label for='proexpbeggining' >Début : </label><br/>
					<select name='$i.$j.ATravailleA.beggining_month' onChange=\"changeDate(this.options[selectedIndex].value);\">
						<option value=\"na\" ";
						
						$mois = $userrec['proexperience']['beggining_month'][$i];
						
						if($mois == "na") {echo 'selected=\"selected\"';};
						echo ">Mois</option>
						<option value=\"1\" ";
						if($mois == "1") {echo 'selected=\"selected\"';};
						echo ">Janvier</option>
						<option value=\"2\" ";
						if($mois == "2") {echo 'selected=\"selected\"';};
						echo ">Février</option>
						<option value=\"3\" ";
						if($mois == "3") {echo 'selected=\"selected\"';};
						echo ">Mars</option>
						<option value=\"4\" ";
						if($mois == "4") {echo 'selected=\"selected\"';};
						echo ">Avril</option>
						<option value=\"5\" ";
						if($mois == "5") {echo 'selected=\"selected\"';};
						echo ">Mai</option>
						<option value=\"6\" ";
						if($mois == "6") {echo 'selected=\"selected\"';};
						echo ">Juin</option>
						<option value=\"7\" ";
						if($mois == "7") {echo 'selected=\"selected\"';};
						echo ">Juillet</option>
						<option value=\"8\" ";
						if($mois == "8") {echo 'selected=\"selected\"';};
						echo ">Août</option>
						<option value=\"9\" ";
						if($mois == "9") {echo 'selected=\"selected\"';};
						echo ">Septembre</option>
						<option value=\"10\" ";
						if($mois == "10") {echo 'selected=\"selected\"';};
						echo ">Octobre</option>
						<option value=\"11\" ";
						if($mois == "11") {echo 'selected=\"selected\"';};
						echo ">Novembre</option>
						<option value=\"12\" ";
						if($mois == "12") {echo 'selected=\"selected\"';};
						echo ">Décembre</option>
						
					</select>
					<select name='$i.$j.ATravailleA.beggining_year' id=\"year\">
					<option value=\"na\">Année</option>";
					
						for($k = 2018;$k > 1950;$k--) {
							$string ='';
							if($userrec[$i][$j]['proexperience']['beggining_year'] == "$k") {$string = 'selected=\"selected\"';}
							echo "<option value=\"$k\" $string>$k</option>\n";		
						};
					
					echo "</select>
			 <span id='changeprofile_proexpbeg_errorloc' class='error'></span>
			</div>
			
			 <div class='container'>
			  <label for='proexpend' >Fin : </label><br/>
					<select name='$i.$j.ATravailleA.end_month' onChange=\"changeDate(this.options[selectedIndex].value);\">
						<option value=\"na\" ";
						$mois = $userrec['proexperience']['end_month'][$i];
						if($mois == "na") {echo 'selected=\"selected\"';};
						echo ">Mois</option>
						<option value=\"1\" ";
						if($mois == "1") {echo 'selected=\"selected\"';};
						echo ">Janvier</option>
						<option value=\"2\" ";
						if($mois == "2") {echo 'selected=\"selected\"';};
						echo ">Février</option>
						<option value=\"3\" ";
						if($mois == "3") {echo 'selected=\"selected\"';};
						echo ">Mars</option>
						<option value=\"4\" ";
						if($mois == "4") {echo 'selected=\"selected\"';};
						echo ">Avril</option>
						<option value=\"5\" ";
						if($mois == "5") {echo 'selected=\"selected\"';};
						echo ">Mai</option>
						<option value=\"6\" ";
						if($mois == "6") {echo 'selected=\"selected\"';};
						echo ">Juin</option>
						<option value=\"7\" ";
						if($mois == "7") {echo 'selected=\"selected\"';};
						echo ">Juillet</option>
						<option value=\"8\" ";
						if($mois == "8") {echo 'selected=\"selected\"';};
						echo ">Août</option>
						<option value=\"9\" ";
						if($mois == "9") {echo 'selected=\"selected\"';};
						echo ">Septembre</option>
						<option value=\"10\" ";
						if($mois == "10") {echo 'selected=\"selected\"';};
						echo ">Octobre</option>
						<option value=\"11\" ";
						if($mois == "11") {echo 'selected=\"selected\"';};
						echo ">Novembre</option>
						<option value=\"12\" ";
						if($mois == "12") {echo 'selected=\"selected\"';};
						echo ">Décembre</option>
						
					</select>
					<select name='$i.$j.ATravailleA.end_year' id=\"year\">
					<option value=\"na\">Année</option>";

	for($k = 2018;$k > 1950;$k--) {
							$string ='';
							if($userrec[$i][$j]['proexperience']['end_year'] == "$k") {$string = 'selected=\"selected\"';}
							echo "<option value=\"$k\" $string>$k</option>\n";		
						};
	
	echo "				</select>
			 <span id='changeprofile_proexpend_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
					
				  <input type='checkbox' name='$i.$j.proexperience.isASmallJob' value='1' id='smalljob' ";
				   if($userrec['proexperience']['isASmallJob'][$i]=='1') echo '\"checked =\"checked\"'; 
				   echo ">Petit boulot ?<br/>
				  <span id='changeprofile_smalljob_errorloc' class='error'></span>
			</div>
		  
			<div class='container'>
			<label for='proexpmission'> Expliquez votre mission :</label> <br/>
					<textarea name=\"".$i.".ATravailleA.mission\" id=\"proexpmission\" maxlength=\"2000\">".$add."</textarea>
			</div>
			<input type='submit' name='Ajout' value='Ajouter'/>
			</fieldset>
	</div>";
	
	}
	
	function PopupInfo($userrec, $i, $j) {
	
	$name = trim(htmlspecialchars(stripslashes($userrec['listinfolanguages'][$i]),ENT_QUOTES));
	$lvl = trim(htmlspecialchars(stripslashes($userrec['listinfolanguageslvl'][$i]),ENT_QUOTES));
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_info\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_info\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 	
		
	echo '			<div class="container">
                		
 					   <label for="infolanguages" >Language/Domaine : </label><br/>
  					  <input type="text" name="'.$i.'.infolanguages" id="infolanguages" value="'.$name.'" maxlength="20" />				<br/>
  					  <span id="changeprofile_infolanguages_errorloc" class="error"></span>
				</div>
                
                 <div class="container">
  				  <label for="infolanguageslvl" >Niveau : </label><br/>
						<select name="'.$i.'.infolanguageslvl">
						<option value="0"'; if($lvl == "0") {echo 'selected="selected"';}; echo '>Notions</option>
						<option value="1" '; if($lvl == "1") {echo 'selected="selected"';}; echo '>Intermédiaire</option>
						<option value="2"'; if($lvl == "2") {echo 'selected="selected"';}; echo '>Avancé</option>
						<option value="3"'; if($lvl == "3") {echo 'selected="selected"';}; echo '>Maîtrise</option>
					
						</select>
   				 <span id="changeprofile_infolanguageslvl_errorloc" class="error"></span>
				</div>
                <a href="#noWhere"><input type="submit" name="Ajout" value="Ajouter"/></a>
                </fieldset>
                
			
		</div>

    <span id="changeprofile_info_errorloc" class="error"></span>';
	}
	
	function PopupTongue($userrec, $i, $j) {
	
	$name = isset($userrec[$i][$j]['tongue']['name'])?trim(htmlspecialchars(stripslashes($userrec[$i][$j]['tongue']['name']),ENT_QUOTES)):'';
	$lvl =  isset($userrec[$i][$j]['Parle']['estimatedLevel'])?trim(htmlspecialchars(stripslashes($userrec[$i][$j]['Parle']['estimatedLevel']),ENT_QUOTES)):'';
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_tongue\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_tongue\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 	
		
	echo '			<div class="container">
                		
 					   <label for="tonguename" >Langue : </label><br/>
  					  <input type="text" name="'.$i.'.tongue.name" id="tonguename" value="'.$name.'" maxlength="20" />				<br/>
  					  <span id="changeprofile_tonguename_errorloc" class="error"></span>
				</div>
                
                 <div class="container">
  				  <label for="tonguelvl" >Niveau : </label><br/>
						<select name="'.$i.'.Parle.estimatedLevel">
						<option value="0"'; if($lvl == "0") {echo 'selected="selected"';}; echo '>Notions</option>
						<option value="1" '; if($lvl == "1") {echo 'selected="selected"';}; echo '>Intermédiaire</option>
						<option value="2"'; if($lvl == "2") {echo 'selected="selected"';}; echo '>Courant</option>
						<option value="3"'; if($lvl == "3") {echo 'selected="selected"';}; echo '>Bilingue</option>
						<option value="4"'; if($lvl == "4") {echo 'selected="selected"';}; echo '>Maternelle</option>
					
						</select>
   				 <span id="changeprofile_tonguelvl_errorloc" class="error"></span>
				</div>
                <a href="#noWhere"><input type="submit" name="Ajout" value="Ajouter"/></a>
                </fieldset>
                
			
		</div>

    <span id="changeprofile_tongues_errorloc" class="error"></span>';
	}
	
	function PopupSport($userrec,$i,$j) {
	
	$name = trim(htmlspecialchars(stripslashes($userrec['listsportname'][$i]),ENT_QUOTES));
	$duration = trim(htmlspecialchars(stripslashes($userrec['listsportduration'][$i]),ENT_QUOTES));
	$qual = trim(htmlspecialchars(stripslashes($userrec['listsportqual'][$i]),ENT_QUOTES));
	$addsth = trim(htmlspecialchars(stripslashes($userrec['listsportaddsth'][$i]),ENT_QUOTES));
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_sport\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_sport\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 
	
	echo '             <div class="container">                	
 					   <label for="sportname" >Nom du sport : </label><br/>
  					  <input type="text" name="'.$i.'.sportname" id="sportname" value="'.$name.'" maxlength="20" />				<br/>
  					  <span id="changeprofile_sportname_errorloc" class="error"></span>
				</div>
                        
                <div class="container">
  				  <label for="sportduration" >Durée : </label><br/>
						<input type="text" name="'.$i.'.sportduration" id="sportduration" value="'.$duration.'" maxlength="20" />	
   				 <span id="changeprofile_sportduration_errorloc" class="error"></span>
				</div>

              	<div class="container">
                <label for="sportqual"> Quelle qualité en-as tu tiré ?</label> <br/>
                		<textarea name="'.$i.'.sportqual" id="sportqual" maxlength="2000">'.$qual.'</textarea>
                </div>
                
                <div class="container">
                <label for="sportaddsth"> Envie d\'en dire plus ?</label> <br/>
                		<textarea name="'.$i.'.sportaddsth" id="sportaddsth" maxlength="2000">'.$addsth.'</textarea>
                </div>
                <a href="#noWhere"><input type="submit" name="Ajout" value="Ajouter"/></a>
                </fieldset>
                
			</div>

    <span id="changeprofile_sportaddsth_errorloc" class="error"></span>';
	
	}
		
	function PopupArt($userrec,$i,$j) {
		
	$name = trim(htmlspecialchars(stripslashes($userrec['listartname'][$i]),ENT_QUOTES));
	$duration = trim(htmlspecialchars(stripslashes($userrec['listartduration'][$i]),ENT_QUOTES));
	$qual = trim(htmlspecialchars(stripslashes($userrec['listartqual'][$i]),ENT_QUOTES));
	$addsth = trim(htmlspecialchars(stripslashes($userrec['listartaddsth'][$i]),ENT_QUOTES));
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_art\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_art\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 
		
		
		echo '			<div class="container">
                		
 					   <label for="artname" >Nom de l\'activité : </label><br/>
  					  <input type="text" name="'.$i.'.artname" id="artname" value="'.$name.'" maxlength="20" />				<br/>
  					  <span id="changeprofile_artname_errorloc" class="error"></span>
				</div>
                        
                <div class="container">
  				  <label for="artduration" >Durée : </label><br/>
						<input type="text" name="'.$i.'.artduration" id="artduration" value="'.$duration.'" maxlength="20" />	
   				 <span id="changeprofile_artduration_errorloc" class="error"></span>
				</div>

              	<div class="container">
                <label for="artqual"> Quelle qualité en-as tu tiré ?</label> <br/>
                		<textarea name="'.$i.'.artqual" id="artqual" maxlength="2000">'.$qual.'</textarea>
                </div>
                
                <div class="container">
                <label for="artaddsth"> Envie d\'en dire plus ?</label> <br/>
                		<textarea name="'.$i.'.artaddsth" id="artaddsth" maxlength="2000">'.$addsth.'</textarea>
                </div>
                <a href="#noWhere"><input type="submit" name="Ajout" value="Ajouter"/></a>
                </fieldset>
                </form>
                
			</div>
			
			<span id="changeprofile_arts_errorloc" class="error"></span>';

    
    }
	
	function PopupAssoc($userrec,$i,$j) {
		
	$name = trim(htmlspecialchars(stripslashes($userrec['listassocname'][$i]),ENT_QUOTES));
	$role = trim(htmlspecialchars(stripslashes($userrec['listassocrole'][$i]),ENT_QUOTES));
	$qual = trim(htmlspecialchars(stripslashes($userrec['listassocqual'][$i]),ENT_QUOTES));
	$descr = trim(htmlspecialchars(stripslashes($userrec['listassocdescr'][$i]),ENT_QUOTES));
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_assoc\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_assoc\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 
		
		
		echo '			<div class="container">
                		
 					   <label for="assocname" >Nom de l\'association : </label><br/>
  					  <input type="text" name="'.$i.'.assocname" id="assocname" value="'.$name.'" maxlength="20" />				<br/>
  					  <span id="changeprofile_assocname_errorloc" class="error"></span>
				</div>
                        
                <div class="container">
  				  <label for="assocduration" >Rôle/Poste dans l\'association : </label><br/>
						<input type="text" name="'.$i.'.assocrole" id="assocrole" value="'.$role.'" maxlength="20" />	
   				 <span id="changeprofile_assocrole_errorloc" class="error"></span>
				</div>

				<div class="container">
                <label for="assocdescr"> Décrivez votre activité au sein de l\'association</label> <br/>
                		<textarea name="'.$i.'.assocdescr" id="assocdescr" maxlength="2000">'.$descr.'</textarea>
                </div>

              	<div class="container">
                <label for="assocqual"> Quelle qualité en-avez-vous tiré ?</label> <br/>
                		<textarea name="'.$i.'.assocqual" id="assocqual" maxlength="2000">'.$qual.'</textarea>
                </div>
                
                
                <a href="#noWhere"><input type="submit" name="Ajout" value="Ajouter"/></a>
                </fieldset>
                </form>
                
			</div>
			
			<span id="changeprofile_assocs_errorloc" class="error"></span>';

    
    }
	
	function PopupTravel($userrec, $i, $j) {
		
	$name = trim(htmlspecialchars(stripslashes($userrec['listtravelplace'][$i]),ENT_QUOTES));
	$rem = trim(htmlspecialchars(stripslashes($userrec['listtravelrem'][$i]),ENT_QUOTES));
	
	if($name == '') {$name = "Ajout";};

	echo "<a href=\"#?w=500\" rel=\"".$i."_travel\" class=\"poplight\"> $name </a>
	<div class=\"popup_block\" id=\"".$i."_travel\">
			<fieldset>
			<legend> $name </legend>";  
	if($name == "Ajout") {unset($name);}; 
  
   			echo  "<div class='container'>	
				   <label for='travelplace' >Lieu : </label><br/>
				  <input type='text' name='$i.$j.travelplace' id='travelplace' value='".$name."' maxlength=\"20\" />				<br/>
				  <span id='changeprofile_travelplace_errorloc' class='error'></span>
			</div>
			
			<div class='container'>
			  <label for='travelbeggining' >Début : </label><br/>
					<select name='$i.$j.travelbegmonth' onChange=\"changeDate(this.options[selectedIndex].value);\">
						<option value=\"na\" ";
						
						$mois = $userrec['listtravelbegmonth'][$i];
						
						if($mois == "na") {echo 'selected=\"selected\"';};
						echo ">Mois</option>
						<option value=\"1\" ";
						if($mois == "1") {echo 'selected=\"selected\"';};
						echo ">Janvier</option>
						<option value=\"2\" ";
						if($mois == "2") {echo 'selected=\"selected\"';};
						echo ">Février</option>
						<option value=\"3\" ";
						if($mois == "3") {echo 'selected=\"selected\"';};
						echo ">Mars</option>
						<option value=\"4\" ";
						if($mois == "4") {echo 'selected=\"selected\"';};
						echo ">Avril</option>
						<option value=\"5\" ";
						if($mois == "5") {echo 'selected=\"selected\"';};
						echo ">Mai</option>
						<option value=\"6\" ";
						if($mois == "6") {echo 'selected=\"selected\"';};
						echo ">Juin</option>
						<option value=\"7\" ";
						if($mois == "7") {echo 'selected=\"selected\"';};
						echo ">Juillet</option>
						<option value=\"8\" ";
						if($mois == "8") {echo 'selected=\"selected\"';};
						echo ">Août</option>
						<option value=\"9\" ";
						if($mois == "9") {echo 'selected=\"selected\"';};
						echo ">Septembre</option>
						<option value=\"10\" ";
						if($mois == "10") {echo 'selected=\"selected\"';};
						echo ">Octobre</option>
						<option value=\"11\" ";
						if($mois == "11") {echo 'selected=\"selected\"';};
						echo ">Novembre</option>
						<option value=\"12\" ";
						if($mois == "12") {echo 'selected=\"selected\"';};
						echo ">Décembre</option>
						
					</select>
					<select name='$i.$j.travelbegyear' id=\"year\">
					<option value=\"na\">Année</option>";
					
							for($k = 2018;$k > 1950;$k--) {
							$string ='';
							if($userrec[$i][$j]['ATravailleA']['beggining_year'] == "$k") {$string = 'selected=\"selected\"';}
							echo "<option value=\"$k\" $string>$k</option>\n";		
						};
					
					echo "</select>
			 <span id='changeprofile_travelbeg_errorloc' class='error'></span>
			</div>
			
			 <div class='container'>
			  <label for='travelend' >Fin : </label><br/>
					<select name='$i.$j.travelendmonth' onChange=\"changeDate(this.options[selectedIndex].value);\">
						<option value=\"na\" ";
						$mois = $userrec['listtravelendmonth'][$i];
						if($mois == "na") {echo 'selected=\"selected\"';};
						echo ">Mois</option>
						<option value=\"1\" ";
						if($mois == "1") {echo 'selected=\"selected\"';};
						echo ">Janvier</option>
						<option value=\"2\" ";
						if($mois == "2") {echo 'selected=\"selected\"';};
						echo ">Février</option>
						<option value=\"3\" ";
						if($mois == "3") {echo 'selected=\"selected\"';};
						echo ">Mars</option>
						<option value=\"4\" ";
						if($mois == "4") {echo 'selected=\"selected\"';};
						echo ">Avril</option>
						<option value=\"5\" ";
						if($mois == "5") {echo 'selected=\"selected\"';};
						echo ">Mai</option>
						<option value=\"6\" ";
						if($mois == "6") {echo 'selected=\"selected\"';};
						echo ">Juin</option>
						<option value=\"7\" ";
						if($mois == "7") {echo 'selected=\"selected\"';};
						echo ">Juillet</option>
						<option value=\"8\" ";
						if($mois == "8") {echo 'selected=\"selected\"';};
						echo ">Août</option>
						<option value=\"9\" ";
						if($mois == "9") {echo 'selected=\"selected\"';};
						echo ">Septembre</option>
						<option value=\"10\" ";
						if($mois == "10") {echo 'selected=\"selected\"';};
						echo ">Octobre</option>
						<option value=\"11\" ";
						if($mois == "11") {echo 'selected=\"selected\"';};
						echo ">Novembre</option>
						<option value=\"12\" ";
						if($mois == "12") {echo 'selected=\"selected\"';};
						echo ">Décembre</option>
						
					</select>
					<select name='$i.$j.travelendyear' id=\"year\">
					<option value=\"na\">Année</option>";

	for($k = 2018;$k > 1950;$k--) {
							$string ='';
							if($userrec[$i][$j]['ATravailleA']['beggining_year'] == "$k") {$string = 'selected=\"selected\"';}
							echo "<option value=\"$k\" $string>$k</option>\n";		
						};
	
	echo "				</select>
			 <span id='changeprofile_travelend_errorloc' class='error'></span>
			</div>
		  
			<div class='container'>
			<label for='travelrem'> Qu'en avez vous retenu ?</label> <br/>
					<textarea name=\"$i.$j.travelrem\" id=\"travelrem\" maxlength=\"2000\">".$comp."</textarea>
			</div>
			<input type='submit' name='Ajout' value='Ajouter'/>
			</fieldset>
	</div>";
	
	}
}

// Fin de la classe ProfileEdition


$profileedition = new ProfileEdition($fgmembersite);


if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

if(isset($_POST['submitted']))
{
			if($profileedition->ChangeProfile())
	  		{
		  		//if(!isset($_POST['Ajout']) && !isset($_POST['Delete'])) //a corriger "Delete"
	       		// $fgmembersite->RedirectToURL("changeprofiledone.html");
	  		}

}

$userrec = array(array(array(array())));
$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();

$fgmembersite->GetProfileFromEmail($email,$userrec,$profileedition->MAX_AJOUT);


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html lang="fr">
<head>
    <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
<title>Edition du paramètre du compte</title>
    <link rel="STYLESHEET" type="text/css" href="../style/fg_membersite.css" />
    <script type='text/javascript' src='../scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="../style/pwdwidget.css" />
    <link rel="STYLESHEET" type="text/css" href="../popup/changeprofiletest.css" />
    <script src="../scripts/pwdwidget.js" type="text/javascript"></script>
   <script type="text/javascript" src="../popup/jquery-1.8.3.js"></script>
   
<style type="text/css">
A:link {text-decoration: none; color: #333}
A:visited {text-decoration: none; color: #333}
A:active {text-decoration: none; color: #333}
A:hover {text-decoration: underline; color: red;; color: #000}
</style>
   
    
</head>
<body>

<!-- Form Code Start -->
<div id='fg_membersite'>
<form id='changeprofile' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>

<fieldset >
<legend>Edition du profil</legend>

<input type='hidden' name='submitted' value='1'/>
<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

<div>
	<span class='error'>
		<?php echo $fgmembersite->GetErrorMessage(); ?>
   	</span>
</div>

<?php 

if($fgmembersite->UserType() == "etudiant") {
	
echo "<div class = 'container'>
	<label> Générer mon CV </label>
    <a href=\"../estelle/PDF.php\">Cliquez ici !</a>
    <span id='changeprofile_cv_errorloc' class='error'></span>
	</div>";
}
?>
<div class='container'>
    <label>Civilité : </label>
    <br/>
    <select name='0.0.user.gender'>
    	<option value='H' <?php if($userrec[0][0]['user']['gender'] == 'H') {echo 'selected="selected"';};?>>M</option>
    	<option value='F' <?php if($userrec[0][0]['user']['gender'] == 'F') {echo 'selected="selected"';};?>>Mme</option>
    </select>
    <br/>
    <span id='changeprofile_gender_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Votre nom : </label>
    <br/>
    <input type='text' name='0.0.user.surname' value='<?php echo htmlspecialchars(stripslashes($userrec[0][0]['user']['surname']),ENT_QUOTES) ?>' maxlength="50" />
    <br/>
    <span id='changeprofile_surname_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Votre prénom : </label>
    <br/>
    <input type='text' name='0.0.user.name' value='<?php echo htmlspecialchars(stripslashes($userrec[0][0]['user']['name']),ENT_QUOTES) ?>' maxlength="50" />
    <br/>
    <span id='changeprofile_name_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Votre date de naissance : </label><br/>
	<select name="0.0.student.birthdate_day" id="birthdate_day">
		<option value="na">Jour</option>
		<?php 
			for($i = 1;$i <= 31;$i++) {
				$string ='';
				if($userrec[0][0]['user']['birthdate_day'] == "$i") {$string = 'selected="selected"';}
				echo "<option value=\"$i\" $string>$i</option>\n";		
			}
		?>
	</select>

	<?php $fgmembersite->afficheMois(0, 0, 'student', 'birthdate_month',$userrec)?>

	<select name="0.0.student.birthdate_year" id="birthdate_year">
		<option value="na">Année</option>
		<?php 
			for($i = 2018;$i > 1950;$i--) {
				$string ='';
				if($userrec[0][0]['student']['birthdate_year'] == "$i") {$string = 'selected="selected"';}
				echo "<option value=\"$i\" $string>$i</option>\n";		
			}
		?>
	</select>
    <span id='changeprofile_birthdate_errorloc' class='error'></span>
</div>


<div class='container'>
    <label>Lieu : </label>
    <br/>
    <input type='text' name='0.0.user.town' value='<?php echo htmlspecialchars(stripslashes($userrec[0][0]['user']['town']),ENT_QUOTES) ?>' maxlength="20" />
    <br/>
    <span id='changeprofile_town_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='phonenumber' >Votre numéro de téléphone : </label>
    <br/>
    <input type='text' name='0.0.user.phonenumber' id='user.phonenumber' value='<?php echo htmlspecialchars(stripslashes($userrec[0][0]['user']['phonenumber']),ENT_QUOTES) ?>' maxlength="10" />
    <br/>
    <span id='changeprofile_phonenumber_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='nationality' >Votre nationalité : </label>
    <br/>
    <input type='text' name='0.0.student.nationality' id='student.nationality' value='<?php if(isset($userrec[0][0]['student']['nationality'])) {echo htmlspecialchars(stripslashes($userrec[0][0]['student']['nationality']),ENT_QUOTES); };?>' maxlength="50" />
    <br/>
    <span id='changeprofile_nationality_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='presentation' >Présentez-vous : </label><br/>
    <input type='text' name='0.0.student.presentation' id='student.presentation' value='<?php if(isset($userrec[0][0]['student']['presentation'])) {echo htmlspecialchars(stripslashes($userrec[0][0]['student']['presentation']),ENT_QUOTES); }; ?>' maxlength="1000" /><br/>
    <span id='changeprofile_presentation_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Que recherches-tu actuellement ? : </label>
    <br/>
    <?php $seeking = (isset($userrec[0][0]['student']['seeking']))?$userrec[0][0]['student']['seeking']:'';?>
    <select name='0.0.student.seeking' size="6" multiple="multiple">
    	<option value='Stage' <?php if($seeking == 'Stage') {echo 'selected="selected"';};?>>Stage</option>
    	<option value='CDI' <?php if($seeking == 'CDI') {echo 'selected="selected"';};?>>CDI</option>
        <option value='CDD' <?php if($seeking == 'CDD') {echo 'selected="selected"';};?>>CDD</option>
        <option value='VIE' <?php if($seeking == 'VIE') {echo 'selected="selected"';};?>>VIE</option>
        <option value='Alternance' <?php if($seeking == 'Alternance') {echo 'selected="selected"';};?>>Alternance</option>
        <option value='Freelance' <?php if($seeking == 'Freelance') {echo 'selected="selected"';};?>>Freelance</option>
        <option value='Autre' <?php if($seeking == 'Autre') {echo 'selected="selected"';};?>>Autre</option>
    </select>
    <br/>
    <span id='changeprofile_seeking_errorloc' class='error'></span>
</div>

<div class='container'>
	    <label>Disponibilité : </label><br/>

	<select name="0.0.student.disponibility_month" onChange="changeDate(this.options[selectedIndex].value);">
	 <?php $disponibility = (isset($userrec[0][0]['student']['disponibility_month']))?$userrec[0][0]['student']['disponibility_month']:'';?>
		<option value="na" <?php if($disponibility == "na") {echo 'selected="selected"';};?>>Mois</option>
		<option value="1" <?php if($disponibility == "1") {echo 'selected="selected"';};?>>Janvier</option>
		<option value="2" <?php if($disponibility == "2") {echo 'selected="selected"';};?>>Février</option>
		<option value="3" <?php if($disponibility == "3") {echo 'selected="selected"';};?>>Mars</option>
		<option value="4" <?php if($disponibility == "4") {echo 'selected="selected"';};?>>Avril</option>
		<option value="5" <?php if($disponibility == "5") {echo 'selected="selected"';};?>>Mai</option>
		<option value="6" <?php if($disponibility == "6") {echo 'selected="selected"';};?>>Juin</option>
		<option value="7" <?php if($disponibility == "7") {echo 'selected="selected"';};?>>Juillet</option>
		<option value="8" <?php if($disponibility == "8") {echo 'selected="selected"';};?>>Août</option>
		<option value="9" <?php if($disponibility == "9") {echo 'selected="selected"';};?>>Septembre</option>
		<option value="10" <?php if($disponibility == "10") {echo 'selected="selected"';};?>>Octobre</option>
		<option value="11" <?php if($disponibility == "11") {echo 'selected="selected"';};?>>Novembre</option>
		<option value="12" <?php if($disponibility == "12") {echo 'selected="selected"';};?>>Décembre</option>
	</select>

	<select name="0.0.student.disponibility_year" id="year">
		<option value="na">Année</option>
		<?php 
		$disponibility = (isset($userrec[0][0]['student']['disponibility_year']))?$userrec[0][0]['student']['disponibility_year']:'';
			for($i = 2018;$i > 1950;$i--) {
				$string ='';
				if($disponibility == "$i") {$string = 'selected="selected"';}
				echo "<option value=\"$i\" $string>$i</option>\n";		
			}
		?>
	</select>
    <span id='changeprofile_disponibility_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Pour une durée de : </label>
    <br/>
    <select name='0.0.student.seekingDuration'>
    	 <?php $seekingDuration = (isset($userrec[0][0]['student']['seekingDuration']))?$userrec[0][0]['student']['seekingDuration']:'';?>
    	<option value='0' <?php if($seekingDuration == '0') {echo 'selected="selected"';};?>>1 à 3 mois</option>
    	<option value='1' <?php if($seekingDuration == '1') {echo 'selected="selected"';};?>>3 à 6 mois</option>
        <option value='2' <?php if($seekingDuration == '2') {echo 'selected="selected"';};?>>6 à 9 mois</option>
        <option value='3' <?php if($seekingDuration == '3') {echo 'selected="selected"';};?>>9 à 12 mois</option>
        <option value='4' <?php if($seekingDuration == '4') {echo 'selected="selected"';};?>>Un an ou plus</option>
    </select>
    <br/>
    <span id='changeprofile_seekingDuration_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Secteur d'activité : </label>
    <br/>
    <select name='0.0.student.seekingDomain' size="9" multiple="multiple">
    <?php $seekingDomain = (isset($userrec[0][0]['student']['seekingDomain']))?$userrec[0][0]['student']['seekingDomain']:'';?>
      <option value="0" <?php if($seekingDomain == '0') {echo 'selected="selected"';};?>>Audit/Conseil</option>
      <option value="1" <?php if($seekingDomain == '1') {echo 'selected="selected"';};?>>Banque/Assurance</option>
      <option value="2" <?php if($seekingDomain == '2') {echo 'selected="selected"';};?>>BTP/Immobilier</option>
      <option value="3" <?php if($seekingDomain == '3') {echo 'selected="selected"';};?>>Distribution/Consommation</option>
      <option value="4" <?php if($seekingDomain == '4') {echo 'selected="selected"';};?>>Energie/Environnement</option>
      <option value="5" <?php if($seekingDomain == '5') {echo 'selected="selected"';};?>>Industrie/Chimie</option>
      <option value="6" <?php if($seekingDomain == '6') {echo 'selected="selected"';};?>>IT/Telecom/Multimedia</option>
      <option value="7" <?php if($seekingDomain == '7') {echo 'selected="selected"';};?>>Pharmacie/Santé</option>
      <option value="8" <?php if($seekingDomain == '8') {echo 'selected="selected"';};?>>Transport/Service</option>
    </select>
    <br/>
    <span id='changeprofile_seekingDomain_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='formations' >Vos formations : </label><br/>
	
    <?php 
    $j = 0;


    
    $l=0; 
    while(isset($userrec[$l][$j]['establishment']['name'])){
    	$l++;
    }
    
	for($i=0; $i < $l && $i < 5; $i++)
		{
			$establishmentName = isset($userrec[$i][$j]['establishment']['name']) ? $userrec[$i][$j]['establishment']['name'] : "";
			$profileedition->PopupFormation($userrec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteFormation' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteFormation'>
						Etes vous sûr(e) de vouloir supprimer la formation ".$establishmentName." ?<br>
						<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupFormation($userrec,$i,$j); 		//Ajouter une formation
	 ?>
    <span id='changeprofile_formations_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='proexp' >Vos expériences professionnelles : </label><br/>
   
     <?php 
     $j = 1;
     

     
     $l=0;
     while(isset($userrec[$l][$j]['society']['name'])){
     	$l++;
     }
	for($i=0; $i < $l; $i++)
		{
			$proExperienceName = isset($userrec[$i][$j]['society']['name']) ? $userrec[$i][$j]['society']['name'] : "";
			
			$profileedition->PopupProexp($userrec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteProexp' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteProexp'>
						Etes vous sûr(e) de vouloir supprimer l'expérience professionnelle ".$proExperienceName." ?<br>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupProexp($userrec,$i,$j); 		//Ajouter une formation
		
	 ?>
		
</div>

<div class='container'>
    <label for='info' >Mes compétences informatiques : </label><br/>
    
    <?php
    $j = 2;
    

     
    $l=0;
    while(isset($userrec[$l][$j]['infoLanguage']['name'])){
    	$l++;
    }
    
    for($i=0; $i < $l; $i++)
		{
			$infoLanguageName = isset($userrec[$i][$j]['infoLanguage']['name']) ? $userrec[$i][$j]['infoLanguage']['name'] : "";
			
			$profileedition->PopupInfo($userrec,$i);
			echo "	<a href='#?w=500' rel='".$i."_deleteInfo' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteInfo'>
						Etes vous sûr(e) de vouloir supprimer le language/domaine : ".$infoLanguageName." ?<br>
					<input type='submit' name='delete_SaitProgrammerEn_".$i."' value='Oui'>
					</div>";
		}
        $profileedition->PopupInfo($userrec,$i); 		//Ajouter une formation
		
	 ?>
    
	
</div>

<div class='container'>
    <label for='tongues' >Mes langues parlées : </label><br/>
    
    <?php
    $j=3;
     
    $l=0;
    while(isset($userrec[$l][$j]['tongue']['name'])){
    	$l++;
    }
    for($i=0; $i < $l; $i++)
		{
			$tongueName = isset($userrec[$i][$j]['tongue']['name']) ? $userrec[$i][$j]['tongue']['name'] : "";
			
			$profileedition->PopupTongue($userrec,$i);
			echo "	<a href='#?w=500' rel='".$i."_deleteTongue' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteTongue'>
						Etes vous sûr(e) de vouloir supprimer la langue ".$tongueName." ?<br>
					<input type='submit' name='delete_Parle_".$i."' value='Oui'>
					</div>";
		}
        $profileedition->PopupTongue($userrec,$i,$j); 		//Ajouter une formation
		
	 ?>
    
	
</div>

<div class='container'>
    <label for='sports' >Sports : </label><br/>
	
     <?php
     $j=4;
     
     $l=0;
     while(isset($userrec[$l][$j]['sport']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$sportName = isset($userrec[$i][$j]['sport']['name']) ? $userrec[$i][$j]['sport']['name'] : "";
			
			$profileedition->PopupSport($userrec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteSport' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteSport'>
						Etes vous sûr(e) de vouloir supprimer le sport ".$sportName." ?<br>
					<input type='submit' name='delete_APratique_sport_".$i."' value='Oui'>
					</div>";
		}
        $profileedition->PopupSport($userrec,$i,$j); 		//Ajouter une formation
		
	 ?>
    
</div>

<div class='container'>
    <label for='arts' >Parcours artistique : </label><br/>
    
	<?php
	$j=5;
	
     $l=0;
     while(isset($userrec[$l][$j]['art']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$artName = isset($userrec[$i][$j]['art']['name']) ? $userrec[$i][$j]['art']['name'] : "";
			$profileedition->PopupArt($userrec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteArt' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteArt'>
						Etes vous sûr(e) de vouloir supprimer le parcours ".$artName." ?<br>
					<input type='submit' name='delete_APratique_art_".$i."' value='Oui'>
					</div>";
		}
        $profileedition->PopupArt($userrec,$i,$j); 		//Ajouter une formation
		
	 ?>
                
                
</div>

<div class='container'>
    <label for='associations' >Engagement associatif : </label><br/>
    
    <?php
    $j=6;
     $l=0;
     while(isset($userrec[$l][$j]['association']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$associationName = isset($userrec[$i][$j]['association']['name']) ? $userrec[$i][$j]['association']['name'] : "";
			$profileedition->PopupAssoc($userrec,$i,$j);
			echo "	<a href='' rel='".$i."_deleteAssoc' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deleteAssoc'>
						Etes vous sûr(e) de vouloir supprimer le parcours ".$associationName." ?<br>
					<input type='submit' name='delete_AFaitPartieDe_association_".$i."' value='Oui'>
					</div>";
		}
        $profileedition->PopupAssoc($userrec,$i,$j); 		//Ajouter une formation
		
	 ?>
    
</div>

<div class='container'>
    <label for='travels' >Voyages : </label><br/>
     <?php 
     $j=7;
	 $l=0;
     while(isset($userrec[$l][$j]['country']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$countryName = isset($userrec[$i][$j]['country']['name']) ? $userrec[$i][$j]['country']['name'] : "";
			$profileedition->PopupTravel($userrec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deletetravel' class='poplight' align='right'>X</a><br/>
					<div class=\"popup_block\" id='".$i."_deletetravel'>
						Etes vous sûr(e) de vouloir supprimer le petit boulot ".$countryName." ?<br>
					<input type='submit' name='delete_AEteA_pays_".$i."' value='Oui'>
					</div>";
		}
        $profileedition->PopupTravel($userrec,$i,$j); 		//Ajouter une formation
		
	 ?>

</div>

<div class='container'>
    <label for='studentYear' >Situation présente du cursus : </label><br/>
    <select name='studentYear' id='studentYear'>
    	<option value='1A' <?php if($userrec['0']['0']['student']['studentYear'] == '1A') {echo 'selected="selected"';};?>>Première année</option>
    	<option value='2A' <?php if($userrec['0']['0']['student']['studentYear'] == '2A') {echo 'selected="selected"';};?>>Deuxième année</option>
        <option value='3A' <?php if($userrec['0']['0']['student']['studentYear'] == '3A') {echo 'selected="selected"';};?>>Troisième année</option>
        <option value='cesure' <?php if($userrec['0']['0']['student']['studentYear'] == 'cesure') {echo 'selected="selected"';};?>>Césure</option>
        <option value='autre' <?php if($userrec['0']['0']['student']['studentYear'] == 'autre') {echo 'selected="selected"';};?>>Autre</option>
    </select>
    <br/>
    <span id='changeprofile_studentYear_errorloc' class='error'></span>
</div>

<div class='container'>
	<label for='picture'>Envoi de votre photographie : (l'image sera redimensionnée sur 140*150px)</label>
    <?php 
	$myImageUpload = new maxImageUpload($userrec['id_user']); 

    //$myUpload->setUploadLocation(getcwd().DIRECTORY_SEPARATOR);
    $myImageUpload->uploadImage();
	?>
	</div>

<input type='submit' name=<?php echo $profileedition->GetOk(); ?> value='Valider' />

</fieldset>
</form>
</div>

<!-- client-side Form Validations:
Uses the excellent form validation script from JavaScript-coder.com-->

<script type='text/javascript'>
// <![CDATA[
    
    var frmvalidator  = new Validator("changeprofile");
    frmvalidator.EnableOnPageErrorDisplay();
    frmvalidator.EnableMsgsTogether();


	//frmvalidator.addValidation("phonenumber","numeric","Le format du numéro de téléphone n'est pas valide (exemple : 0612345678)");
    //frmvalidator.addValidation("name","req","Veuillez indiquer votre nom complet");

// ]]>
</script>

<script type="text/javascript">
$(document).ready(function(){
						   		   
	//When you click on a link with class of poplight and the href starts with a # 
	$('a.poplight[href^=#]').click(function() {
		var popID = $(this).attr('rel'); //Get Popup Name
		var popURL = $(this).attr('href'); //Get Popup href to define size
				
		//Pull Query & Variables from href URL
		var query= popURL.split('?');
		var dim= query[1].split('&');
		var popWidth = dim[0].split('=')[1]; //Gets the first query string value

		//Fade in the Popup and add close button
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="images/close_pop.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
		//Define margin for center alignment (vertical + horizontal) - we add 80 to the height/width to accomodate for the padding + border width defined in the css
		var popMargTop = ($('#' + popID).height() + 80) / 2;
		var popMargLeft = ($('#' + popID).width() + 80) / 2;
		
		//Apply Margin to Popup
		$('#' + popID).css({ 
			'margin-top' : -popMargTop,
			'margin-left' : -popMargLeft
		});
		
		//Fade in Background
		$('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
		$('#fade').css({'filter' : 'alpha(opacity=80)'}).fadeIn(); //Fade in the fade layer 
		
		return false;
	});
	
	
	//Close Popups and Fade Layer
	$('a.close, #fade').live('click', function() { //When clicking on the close or fade layer...
	  	$('#fade , .popup_block').fadeOut(function() {
			$('#fade, a.close').remove();  
	}); //fade them both out
		
		return false;
	});

	
});

</script>
</body>
</html>