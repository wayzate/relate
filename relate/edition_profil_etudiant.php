<?PHP
require_once("./include/membersite_config.php");
require_once('./changeprofile/profileEdition.php');
require_once("./upload/maxImageUpload.class.php");


$profileedition = new ProfileEdition($fgmembersite);

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}

if(isset($_POST['submitted']))
{
			if($profileedition->ChangeProfile($fgmembersite))
	  		{
		  		$actionDelete = false;
		  		for($i=0;$i<=$profileedition->MAX_AJOUT;$i++) {
		  			for($j=0;$j<$profileedition->NUM_SECTION;$j++) {
		  				if(isset($_POST['delete_'.$i.'_'.$j])) {
		  					$actionDelete = true; 
		  				}
		  			}
		  		}
		  		
		  		if(!isset($_POST['Ajout']) && !$actionDelete)
	       		 $fgmembersite->RedirectToURL("profil_etudiant.php");
				 
	  		}

}

$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();

$fgmembersite->GetProfileFromEmail($email,$user_rec,$profileedition->MAX_AJOUT);
?>


<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Edition de profil</title>

	<link rel="STYLESHEET" type="text/CSS" href="css/style.css" media="screen" />
	<link rel="STYLESHEET" type="text/CSS" href="css/styleprofil_etudiant.css" media="screen" />
    <link rel="STYLESHEET" type="text/css" href="style/profil_fg_membersite.css" />
    <script type='text/javascript' src='../scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
    <link rel="STYLESHEET" type="text/css" href="popup/changeprofiletest.css" />
    <script src="scripts/pwdwidget.js" type="text/javascript"></script>
    <script type="text/javascript" src="autocomplete/jquery-1.2.1.pack.js"></script>
  
   <script type="text/javascript" src="popup/jquery-1.8.3.js"></script>
   
<script type="text/javascript">
	function lookup_society(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestionsSociety').hide();
		} else {
			$.post("../autocomplete/rpc_entreprise.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestionsSociety').show();
					$('#autoSuggestionsListSociety').html(data);
				}
			});
		}
	} // lookup_society

	function lookup_town(inputString) {
		if(inputString.length == 0) {
			// Hide the suggestion box.
			$('#suggestionsTown').hide();
		} else {
			$.post("../autocomplete/rpc_villes.php", {queryString: ""+inputString+""}, function(data){
				if(data.length >0) {
					$('#suggestionsTown').show();
					$('#autoSuggestionsListTown').html(data);
				}
			});
		}
	} // lookup_town
	
	function fill(thisValue) {
		$('#inputString').val(thisValue);
		setTimeout("$('#suggestionsTown').hide();", 200);
		setTimeout("$('#suggestionsSociety').hide();", 200);
		
	}
</script>

   
    
    
<style type="text/css">
A:link {text-decoration: none; color: #000}
A:visited {text-decoration: none; color: #000}
A:active {text-decoration: none; color: #000}
A:hover {text-decoration:none; color: #000; color: #000}
</style>
   

<style type="text/css">
	body {
		font-family: Myriad Pro Semibold, Trebuchet MS, sans-serif;
		font-size: 14px;
		color: #000;
		padding: 0px;	
	}
	
	h3 {
		margin: 0px;
		padding: 0px;	
	}

	.suggestionsBox {
		position: relative;
		left: 30px;
		margin: 10px 0px 0px 0px;
		width: 200px;
		background-color:transparent;
		border:none;	
		color:transparent;
	}
	
	.suggestionList {
		margin: 0px;
		padding: 0px;
	}
	
	.suggestionList li {
		
		margin: 0px 0px 3px 0px;
		padding: 3px;
		cursor: pointer;
	}
	
	.suggestionList li:hover {
		background-color: #659CD8;
	}
</style>

</head>


<body>

<!-- ************************ DEBUT GROSCONTENEUR ************************ -->
		
	<div id="grosconteneur">

<!-- ************************ HEADER ************************ -->
	<div id="header">
    
		<div id="top">
			<div id="item">
				<div id="logo"></div>
			</div>
		</div>
		<div id="topombre"></div>
		
	</div>
    

<!-- ************************ BARRE DE MENU ************************ -->
	
	<div id="menu">
	
		<ul>
			<li><a href="profil_etudiant.php" class="profil"> Profil </a></li>
			<li><a href="offres.php" class="offres"> Offres </a></li>
			<li><a href="entreprises.php" class="entreprises"> Entreprises </a></li>
			<li><a href="etudiants.php" class="etudiants"> Etudiants </a></li>
			<li><a href="candidatures.php" class="candidatures"> Candidatures </a></li>
			<li><a href='messages.php' class="messages"> Messages </a></li>
		</ul>
	</div>


<!-- ************************ DEBUT CONTENEUR ************************ -->
		
	<div id="conteneur">
    
    
<!-- ************************ BIENVENUE ET DECONNEXION ************************ -->		

    	<div id="textetop">
			<div id="bienvenue"><a href="accueil_etudiant.php">Bienvenue <?php echo $fgmembersite->UserFullName(); ?></a></div>
        	<div id="deconnexion"><a href='logout.php'> Se déconnecter </a></div>
        </div>

<!-- ************************ TITRE ************************ -->	
	        
		<div id="titre">VOTRE PROFIL ETUDIANT</div>


<!-- *********************** EDITION DU PROFIL *********************** -->
        
<div id='fg_membersite' class='edition'>

<div class='rectangletitre'>
Edition de votre profil
</div>

<form id='changeprofile' action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>

<fieldset >

<input type='hidden' name='submitted' value='1'/>
<input type='text'  class='spmhidip' name='<?php echo $fgmembersite->GetSpamTrapInputName(); ?>' />

<div>
	<span class='error'>
		<?php echo $fgmembersite->GetErrorMessage(); ?>
   	</span>
</div>

<div class='titrerubrique'> Vos informations générales</div>

<div class='container'>
    <label>Civilité</label>
    	<a id="marge">
    <select name='0.0.user.gender'>
    	<option value='H' <?php if($user_rec[0][0]['user']['gender'] == 'H') {echo 'selected="selected"';};?>>M</option>
    	<option value='F' <?php if($user_rec[0][0]['user']['gender'] == 'F') {echo 'selected="selected"';};?>>Mme</option>
    </select>
    	</a>
    
    <span id='changeprofile_gender_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Nom</label>
    
    <input type='text' name='0.0.user.surname' value='<?php if(isset($user_rec[0][0]['user']['surname'])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['user']['surname']),ENT_QUOTES);} ?>' maxlength="50" />
    
    <span id='changeprofile_surname_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Prénom</label>
    
    <input type='text' name='0.0.user.name' value='<?php if(isset($user_rec[0][0]['user']['name'])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['user']['name']),ENT_QUOTES);} ?>' maxlength="50" />
    
    <span id='changeprofile_name_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Date de naissance</label>
	
    <a id="marge">
	<?php $fgmembersite->afficheJour(0, 0, 'student', 'birthdate_day',$user_rec)?>
    
	<?php $fgmembersite->afficheMois(0, 0, 'student', 'birthdate_month',$user_rec)?>

	<?php $fgmembersite->afficheAnnee(0, 0, 'student', 'birthdate_year',$user_rec)?>
    </a>
	
    <span id='changeprofile_birthdate_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='nationality' >Nationalité</label>
    
    <input type='text' name='0.0.student.nationality' id='student.nationality' value='<?php if(isset($user_rec[0][0]['student']['nationality'])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['student']['nationality']),ENT_QUOTES); };?>' maxlength="50" />
    
    <span id='changeprofile_nationality_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='studentYear' >Année du cursus</label>
    <a id="marge">
    <select name='0.0.student.studentYear' id='studentYear'>
    <?php $string = isset($user_rec['0']['0']['student']['studentYear'])?$user_rec['0']['0']['student']['studentYear']:'';?>
    	<option value='' <?php if($string == '') {echo 'selected="selected"';};?>>Année en cours</option>
    	<option value='1A' <?php if($string == '1A') {echo 'selected="selected"';};?>>Première année</option>
    	<option value='2A' <?php if($string == '2A') {echo 'selected="selected"';};?>>Deuxième année</option>
        <option value='3A' <?php if($string == '3A') {echo 'selected="selected"';};?>>Troisième année</option>
        <option value='cesure' <?php if($string == 'cesure') {echo 'selected="selected"';};?>>Césure</option>
        <option value='autre' <?php if($string == 'autre') {echo 'selected="selected"';};?>>Autre</option>
    </select>
	</a>
    
    <span id='changeprofile_studentYear_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='presentation' >Présentez-vous</label>
    <input type='text' name='0.0.student.presentation' id='student.presentation' value='<?php if(isset($user_rec[0][0]['student']['presentation'])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['student']['presentation']),ENT_QUOTES); }; ?>' maxlength="1000" />
    <span id='changeprofile_presentation_errorloc' class='error'></span>
</div>
<br/>
<div class='titrerubrique'> Vos coordonnées</div>

<!--<div class='container'>
    <label>Ville</label>
    
    <input type='text' class='villes' name='0.0.user.town' value='<?php //if(isset($user_rec[0][0]['town']['name'])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['town']['name']),ENT_QUOTES);}; ?>' maxlength="20" />
    
    <span id='changeprofile_town_errorloc' class='error'></span>
</div>-->

<div class='container'>
    <label for='phonenumber' >Téléphone</label>
    
    <input type='text' name='0.0.user.phonenumber' id='user.phonenumber' value='<?php echo htmlspecialchars(stripslashes($user_rec[0][0]['user']['phonenumber']),ENT_QUOTES) ?>' maxlength="10" />
    
    <span id='changeprofile_phonenumber_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'>Ce que vous recherchez</div>

<div class='container'>
    <label>Type de contrat</label>
    <a id="marge">
    <?php $seeking = (isset($user_rec[0][0]['student']['seeking']))?$user_rec[0][0]['student']['seeking']:'';?>
    <select name='0.0.student.seeking'>
    	<option value='' <?php if($seeking == '') {echo 'selected="selected"';};?>>Indifférent</option>
        <option value='Stage' <?php if($seeking == 'Stage') {echo 'selected="selected"';};?>>Stage</option>
    	<option value='CDI' <?php if($seeking == 'CDI') {echo 'selected="selected"';};?>>CDI</option>
        <option value='CDD' <?php if($seeking == 'CDD') {echo 'selected="selected"';};?>>CDD</option>
        <option value='VIE' <?php if($seeking == 'VIE') {echo 'selected="selected"';};?>>VIE</option>
        <option value='Alternance' <?php if($seeking == 'Alternance') {echo 'selected="selected"';};?>>Alternance</option>
        <option value='Freelance' <?php if($seeking == 'Freelance') {echo 'selected="selected"';};?>>Freelance</option>
        <option value='Autre' <?php if($seeking == 'Autre') {echo 'selected="selected"';};?>>Autre</option>
    </select>
    </a>
    <span id='changeprofile_seeking_errorloc' class='error'></span>
</div>

<div class='container'>
	    <label>Disponibilité</label>
     <a id="marge">   
	 <select name="0.0.student.disponibility_month" onChange="changeDate(this.options[selectedIndex].value);">
	 <?php $disponibility = (isset($user_rec[0][0]['student']['disponibility_month']))?$user_rec[0][0]['student']['disponibility_month']:'';?>
		<option value="" <?php if($disponibility == "") {echo 'selected="selected"';};?>>Mois</option>
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
		<option value="">Année</option>
		<?php 
		$disponibility = (isset($user_rec[0][0]['student']['disponibility_year']))?$user_rec[0][0]['student']['disponibility_year']:'';
			for($i = 2018;$i > 1950;$i--) {
				$string ='';
				if($disponibility == "$i") {$string = 'selected="selected"';}
				echo "<option value=\"$i\" $string>$i</option>\n";		
			}
		?>
	</select>
        </a>
    <span id='changeprofile_disponibility_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Durée</label>
    	 <a id="marge">
    	 <select name='0.0.student.seekingDuration'>
    	 <?php $seekingDuration = (isset($user_rec[0][0]['student']['seekingDuration']))?$user_rec[0][0]['student']['seekingDuration']:'';?>
    	 <option value='' <?php if($seekingDuration == '') {echo 'selected="selected"';};?> >Durée du contrat</option>
    	<option value='0' <?php if($seekingDuration == '0') {echo 'selected="selected"';};?>>1 à 3 mois</option>
    	<option value='1' <?php if($seekingDuration == '1') {echo 'selected="selected"';};?>>3 à 6 mois</option>
        <option value='2' <?php if($seekingDuration == '2') {echo 'selected="selected"';};?>>6 à 9 mois</option>
        <option value='3' <?php if($seekingDuration == '3') {echo 'selected="selected"';};?>>9 à 12 mois</option>
        <option value='4' <?php if($seekingDuration == '4') {echo 'selected="selected"';};?>>Un an ou plus</option>
    	</select>
    	 </a>
    <span id='changeprofile_seekingDuration_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Secteur d'activité</label>
    <a id="marge">
     <select name='0.0.student.seekingDomain'>
    <?php $seekingDomain = (isset($user_rec[0][0]['student']['seekingDomain']))?$user_rec[0][0]['student']['seekingDomain']:'';?>
       <option value="" <?php if($seekingDomain == '') {echo 'selected="selected"';};?>>Indifférent</option>
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
    </a>
    <span id='changeprofile_seekingDomain_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'> Vos formations</div>

<div class='container'>	
   <?php 
    $j = 0;

    $l=0; 
    while(isset($user_rec[$l][$j]['establishment']['name'])){
    	$l++;
    }
    
	for($i=0; $i < $l && $i < 5; $i++)
		{
			$establishmentName = isset($user_rec[$i][$j]['establishment']['name']) ? $user_rec[$i][$j]['establishment']['name'] : "";
			$profileedition->PopupFormation($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteFormation' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteFormation'>
						Etes vous sûr(e) de vouloir supprimer la formation ".$establishmentName." ?<br/><br/>
						<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupFormation($user_rec,$i,$j); 		//Ajouter une formation
	 ?>
    <span id='changeprofile_formations_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'> Vos expériences professionnelles</div>

<div class='container'>
	<?php 
     $j = 1;
     

     
     $l=0;
     while(isset($user_rec[$l][$j]['society']['raisonSociale'])){
     	$l++;
     }
	for($i=0; $i < $l; $i++)
		{
			$proExperienceName = isset($user_rec[$i][$j]['society']['raisonSociale']) ? $user_rec[$i][$j]['society']['raisonSociale'] : "";
			
			$profileedition->PopupProexp($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteProexp' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteProexp'>
						Etes vous sûr(e) de vouloir supprimer l'expérience professionnelle ".$proExperienceName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupProexp($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>
		
</div>

<br/>

<div class='titrerubrique'> Vos compétences informatiques</div>

<div class='container'>
    <label for='info' >Languages de programmation</label><br/>
    <?php
    $j = 2;
    

     
    $l=0;
    while(isset($user_rec[$l][$j]['infoLanguage']['name'])){
    	$l++;
    }
    
    for($i=0; $i < $l; $i++)
		{
			$infoLanguageName = isset($user_rec[$i][$j]['infoLanguage']['name']) ? $user_rec[$i][$j]['infoLanguage']['name'] : "";
			
			$profileedition->PopupInfo($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteInfo' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteInfo'>
						Etes vous sûr(e) de vouloir supprimer le language/domaine : ".$infoLanguageName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupInfo($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>

</div>

<br/>

<div class='container'>
    <label for='info' >Logiciels</label><br/>
    <?php
    $j = 8;
    

     
    $l=0;
    while(isset($user_rec[$l][$j]['software']['name'])){
    	$l++;
    }
    
    for($i=0; $i < $l; $i++)
		{
			$softwarename = isset($user_rec[$i][$j]['software']['name']) ? $user_rec[$i][$j]['software']['name'] : "";
			
			$profileedition->PopupSoftware($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteSoftware' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteSoftware'>
						Etes vous sûr(e) de vouloir supprimer le language/domaine : ".$softwarename." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupSoftware($user_rec,$i,$j); 		//Ajouter une formation

	 ?>
	
</div>

<br/>
<div class='titrerubrique'> Vos langues</div>

<div class='container'>
    
    <?php
    $j=3;
     
    $l=0;
    while(isset($user_rec[$l][$j]['tongue']['name'])){
    	$l++;
    }
    for($i=0; $i < $l; $i++)
		{
			$tongueName = isset($user_rec[$i][$j]['tongue']['name']) ? $user_rec[$i][$j]['tongue']['name'] : "";
			
			$profileedition->PopupTongue($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteTongue' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteTongue'>
						Etes vous sûr(e) de vouloir supprimer la langue ".$tongueName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupTongue($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>
	
</div>

<br/>
<div class='titrerubrique'> Vos sports</div>

<div class='container'>
   
     <?php
     $j=4;
     
     $l=0;
     while(isset($user_rec[$l][$j]['sport']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$sportName = isset($user_rec[$i][$j]['sport']['name']) ? $user_rec[$i][$j]['sport']['name'] : "";
			
			$profileedition->PopupSport($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteSport' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteSport'>
						Etes vous sûr(e) de vouloir supprimer le sport ".$sportName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupSport($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>
    
</div>

<br/>
<div class='titrerubrique'> Votre parcours artistique</div>

<div class='container'>
    
	 <?php
	$j=5;
	
     $l=0;
     while(isset($user_rec[$l][$j]['art']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$artName = isset($user_rec[$i][$j]['art']['name']) ? $user_rec[$i][$j]['art']['name'] : "";
			$profileedition->PopupArt($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteArt' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteArt'>
						Etes vous sûr(e) de vouloir supprimer le parcours ".$artName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupArt($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>
                         
</div>

<br/>
<div class='titrerubrique'> Votre engagement associatif</div>

<div class='container'>
    
   <?php
     $j=6;
     $l=0;
     while(isset($user_rec[$l][$j]['association']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$associationName = isset($user_rec[$i][$j]['association']['name']) ? $user_rec[$i][$j]['association']['name'] : "";
			$profileedition->PopupAssoc($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deleteAssoc' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deleteAssoc'>
						Etes vous sûr(e) de vouloir supprimer le parcours ".$associationName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupAssoc($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>  
    
</div>

<br/>
<div class='titrerubrique'> Vos voyages</div>

<div class='container'>
    
	 <?php 
     $j=7;
	 $l=0;
     while(isset($user_rec[$l][$j]['country']['name'])){
     	$l++;
     }
     
    for($i=0; $i < $l; $i++)
		{
			$countryName = isset($user_rec[$i][$j]['country']['name']) ? $user_rec[$i][$j]['country']['name'] : "";
			$profileedition->PopupTravel($user_rec,$i,$j);
			echo "	<a href='#?w=500' rel='".$i."_deletetravel' class='poplight' align='right'><img src='theme/boutonquittersimple.png' class='btn_suppr'></a><br/>
					<div class=\"popup_block\" id='".$i."_deletetravel'>
						Etes vous sûr(e) de vouloir supprimer le petit boulot ".$countryName." ?<br/><br/>
					<input type='submit' name='delete_$i"."_"."$j' value='Oui'>
					</div>";
		}
        $profileedition->PopupTravel($user_rec,$i,$j); 		//Ajouter une formation
		
	 ?>

</div>
<br/>
<!-- 
<div class='container'>
	<label for='picture'>Envoi de votre photographie : (l'image sera redimensionnée sur 140*150px)</label>
    <?php 
/*	$myImageUpload = new maxImageUpload($user_rec['id_user']); 

    //$myUpload->setUploadLocation(getcwd().DIRECTORY_SEPARATOR);
    $myImageUpload->uploadImage();
*/	?>
	</div>
 -->
<input type='submit' name=<?php echo $profileedition->GetOk(); ?> value='Valider' class='boutonvalider' />
<div id='boutonannuler'><a href="profil_etudiant.php">Annuler</a></div>
</fieldset>
</form>
</div>
     

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
		$('#' + popID).fadeIn().css({ 'width': Number( popWidth ) }).prepend('<a href="#" class="close"><img src="theme/boutonquittersimple.png" class="btn_close" title="Close Window" alt="Close" /></a>');
		
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

<!-- ************************ FIN CONTENEUR ************************ -->    
    </div>

<!-- ************************ FOOTER ************************ -->

<div id="footer" class="edition_profil">

	<div id="footombre"></div>
    
	<div id="foot">
    	<div id="textefoot">
        	<li><a class="relate"> Relate ©  2013</a></li>
			<li><a href="#"> Contactez-nous </a></li>
			<li><a href='conditions_utilisation.html'> Conditions d'utilisation </a></li>
			<li><a href='mentions_legales.html'> Mentions légales </a></li>
        </div>
	</div>

</div>


<!-- ************************ FIN GROSCONTENEUR ************************ -->    
    </div>

	
</body>
</html>
