<?PHP
require_once("./include/membersite_config.php");
require_once('./changeprofile/profileEdition.php');



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
		  		//if(!isset($_POST['Ajout']) && !isset($_POST['Delete'])) //a corriger "Delete"
	       		// $fgmembersite->RedirectToURL("changeprofiledone.html");
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
<title>Profil</title>

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


<!-- *********************** AFFICHAGE DU PROFIL *********************** -->
        
<div id='fg_membersite'>
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
		 <?php if($user_rec[0][0]['user']['gender'] == 'H') {echo 'Monsieur';};?>
    	 <?php if($user_rec[0][0]['user']['gender'] == 'F') {echo 'Madame';};?>
        </a>
    
    <span id='changeprofile_gender_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Nom</label>
    
    <input readonly='readonly' type='text' name='0.0.user.surname' value='<?php echo htmlspecialchars(stripslashes($user_rec[0][0]['user']['surname']),ENT_QUOTES) ?>' maxlength="50" />
    
    <span id='changeprofile_surname_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Prénom</label>
    
    <input readonly='readonly' type='text' name='0.0.user.name' value='<?php echo htmlspecialchars(stripslashes($user_rec[0][0]['user']['name']),ENT_QUOTES) ?>' maxlength="50" />
    
    <span id='changeprofile_name_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Date de naissance</label>
	
    <a id="marge">
	<?php if (isset($user_rec[0][0]['student']['birthdate'])) {echo date("d/m/y", mktime(0, 0, 0, $user_rec[0][0]['student']['birthdate_month'], $user_rec[0][0]['student']['birthdate_day'], $user_rec[0][0]['student']['birthdate_year']));}?>
    </a>
	
    <span id='changeprofile_birthdate_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='nationality' >Nationalité</label>
    
    <input readonly='readonly' type='text' name='0.0.student.nationality' id='student.nationality' value='<?php if(isset($user_rec[0][0]['student']['nationality'])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['student']['nationality']),ENT_QUOTES); };?>' maxlength="50" />
    
    <span id='changeprofile_nationality_errorloc' class='error'></span>
</div>

<div class='container'>
    <label for='studentYear' >Année du cursus</label>
    <a id="marge">
    <?php $string = isset($user_rec['0']['0']['student']['studentYear'])?$user_rec['0']['0']['student']['studentYear']:'';?>
    	<?php if($string == '') {echo '';};?>
    	<?php if($string == '1A') {echo 'Première année';};?>
    	<?php if($string == '2A') {echo 'Deuxième année';};?>
        <?php if($string == '3A') {echo 'Troisième année';};?>
        <?php if($string == 'cesure') {echo 'Césure';};?>
        <?php if($string == 'autre') {echo 'Autre';};?>
	</a>
    
    <span id='changeprofile_studentYear_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'> Vos coordonnées</div>
<!--  
<div class='container'>
    <label>Ville</label>
    
    <input readonly='readonly' type='text' class='villes' name='0.0.user.town' value='<?php //if(isset($user_rec[0][0]['town'][2])) {echo htmlspecialchars(stripslashes($user_rec[0][0]['town'][2]),ENT_QUOTES);}; ?>' maxlength="20" />
    
    <span id='changeprofile_town_errorloc' class='error'></span>
</div>
-->
<div class='container'>
    <label for='phonenumber' >Téléphone</label>
    
    <input readonly='readonly' type='text' name='0.0.user.phonenumber' id='user.phonenumber' value='<?php echo htmlspecialchars(stripslashes($user_rec[0][0]['user']['phonenumber']),ENT_QUOTES) ?>' maxlength="10" />
    
    <span id='changeprofile_phonenumber_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'>Ce que vous recherchez</div>

<div class='container'>
    <label>Type de contrat</label>
    <a id="marge">
    <?php $seeking = (isset($user_rec[0][0]['student']['seeking']))?$user_rec[0][0]['student']['seeking']:'';?>
    	<?php if($seeking == 'Stage') {echo 'Stage';};?>
    	<?php if($seeking == 'CDI') {echo 'CDI';};?>
        <?php if($seeking == 'CDD') {echo 'CDD';};?>
        <?php if($seeking == 'VIE') {echo 'VIE';};?>
        <?php if($seeking == 'Alternance') {echo 'Alternance';};?>
        <?php if($seeking == 'Freelance') {echo 'Freelance';};?>
        <?php if($seeking == 'Autre') {echo 'Autre';};?>
    </a>
    <span id='changeprofile_seeking_errorloc' class='error'></span>
</div>

<div class='container'>
	    <label>Disponibilité</label>
     <a id="marge">   
	 <?php $disponibility = (isset($user_rec[0][0]['student']['disponibility_month']))?$user_rec[0][0]['student']['disponibility_month']:'';?>
		<?php if($disponibility == "") {echo '';};?>
		<?php if($disponibility == "1") {echo 'Janvier';};?>
		<?php if($disponibility == "2") {echo 'Février';};?>
		<?php if($disponibility == "3") {echo 'Mars';};?>
		<?php if($disponibility == "4") {echo 'Avril';};?>
		<?php if($disponibility == "5") {echo 'Mai';};?>
		<?php if($disponibility == "6") {echo 'Juin';};?>
		<?php if($disponibility == "7") {echo 'Juillet';};?>
		<?php if($disponibility == "8") {echo 'Août';};?>
		<?php if($disponibility == "9") {echo 'Septembre';};?>
		<?php if($disponibility == "10") {echo 'Octobre';};?>
		<?php if($disponibility == "11") {echo 'Novembre';};?>
		<?php if($disponibility == "12") {echo 'Décembre';};?>

		<?php 
		$disponibility = (isset($user_rec[0][0]['student']['disponibility_year']))?$user_rec[0][0]['student']['disponibility_year']:'';
			for($i = 2018;$i > 1950;$i--) {
				$string ='';
				if($disponibility == "$i") {$string = "$i";}
				echo $string;		
			}
		?>
        </a>
    <span id='changeprofile_disponibility_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Durée</label>
    	 <a id="marge">
    	 <?php $seekingDuration = (isset($user_rec[0][0]['student']['seekingDuration']))?$user_rec[0][0]['student']['seekingDuration']:'';?>
    	 <?php if($seekingDuration == '') {echo '';};?>
    	 <?php if($seekingDuration == '0') {echo '1 à 3 mois';};?>
    	 <?php if($seekingDuration == '1') {echo '3 à 6 mois';};?>
         <?php if($seekingDuration == '2') {echo '6 à 9 mois';};?>
         <?php if($seekingDuration == '3') {echo '9 à 12 mois';};?>
         <?php if($seekingDuration == '4') {echo 'Un an ou plus';};?>
    	 </a>
    <span id='changeprofile_seekingDuration_errorloc' class='error'></span>
</div>

<div class='container'>
    <label>Secteur d'activité</label>
    <a id="marge">
    <?php $seekingDomain = (isset($user_rec[0][0]['student']['seekingDomain']))?$user_rec[0][0]['student']['seekingDomain']:'';?>
      <?php if($seekingDomain == '0') {echo 'Audit/Conseil';};?>
      <?php if($seekingDomain == '1') {echo 'Banque/Assurance';};?>
      <?php if($seekingDomain == '2') {echo 'BTP/Immobilier';};?>
      <?php if($seekingDomain == '3') {echo 'Distribution/Consommation';};?>
      <?php if($seekingDomain == '4') {echo 'Energie/Environnement';};?>
      <?php if($seekingDomain == '5') {echo 'Industrie/Chimie';};?>
      <?php if($seekingDomain == '6') {echo 'IT/Telecom/Multimedia';};?>
      <?php if($seekingDomain == '7') {echo 'Pharmacie/Santé';};?>
      <?php if($seekingDomain == '8') {echo 'Transport/Service';};?>
    </a>
    <span id='changeprofile_seekingDomain_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'> Vos formations</div>

<div class='container'>	
       <?php 
    $chaine = "";
    $j = 0;
    $l=0; 
    while(isset($user_rec[$l][$j]['establishment']['name'])){
    	$l++;
    }
    
	for($i=0; $i < $l && $i < 5; $i++)
		{
			$chaine = $chaine."<div class='container'><label>";
			$begyear = $user_rec[$i][$j]['AEtudieA']['beggining_year'];
			$endyear = $user_rec[$i][$j]['AEtudieA']['end_year'];
			$chaine = $chaine.$begyear . "-" . $endyear . "</label>"; 
			$chaine = $chaine."<a id='marge'>";
			$establishmentName = isset($user_rec[$i][$j]['establishment']['name']) ? $user_rec[$i][$j]['establishment']['name'] : "";
			$chaine = $chaine.$establishmentName . "</a></div>";
		}
		
		echo $chaine;
		unset($chaine);
	 ?>
    <span id='changeprofile_formations_errorloc' class='error'></span>
</div>

<br/>
<div class='titrerubrique'> Vos expériences professionnelles</div>

<div class='container'>
	<?php 
    $chaine = "";
    $j = 1;
    $l=0; 
    while(isset($user_rec[$l][$j]['society']['raisonSociale'])){
    	$l++;
    }
    
	for($i=0; $i < $l; $i++)
		{
			$chaine = $chaine."<div class='container'><label>";
			$begyear = isset($user_rec[$i][$j]['ATravailleA']['beggining_year']) ? $user_rec[$i][$j]['ATravailleA']['beggining_year'] : "";
			$endyear = isset($user_rec[$i][$j]['ATravailleA']['end_year']) ? ($user_rec[$i][$j]['ATravailleA']['end_year']) : "";
			$begmonth = isset($user_rec[$i][$j]['ATravailleA']['beggining_month']) ? $user_rec[$i][$j]['ATravailleA']['beggining_month'] : "";
			$endmonth = isset($user_rec[$i][$j]['ATravailleA']['end_month']) ? $user_rec[$i][$j]['ATravailleA']['end_month'] : "";
			$chaine = $chaine.$begmonth.'/'.$begyear . "-" . $endmonth.'/'.$endyear . "</label>"; 
			$chaine = $chaine."<a id='marge'>";
			$proExperienceName = isset($user_rec[$i][$j]['society']['raisonSociale']) ? $user_rec[$i][$j]['society']['raisonSociale'] : "";
			$chaine = $chaine.$proExperienceName . "</a></div>";
		}
		
		echo $chaine;
		unset($chaine);
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
			
			echo $infoLanguageName."<br/>";
		}
		
	 ?>
	
</div>
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
			
			echo $softwarename."<br/>";
		}

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
			$lvl = isset($user_rec[$i][$j]['Parle']['estimatedLevel']) ? $user_rec[$i][$j]['Parle']['estimatedLevel'] : "";
			$tongueLevel = "";
			
			if($lvl == "0") { $tongueLevel = "Notions"; };
			if($lvl == "1") { $tongueLevel = "Intermédiaire"; };
			if($lvl == "2") { $tongueLevel = "Courant"; };
			if($lvl == "3") { $tongueLevel = "Bilingue"; };
			if($lvl == "4") { $tongueLevel = "Langue maternelle"; };
			
			echo "<div class='container'>". $tongueName . ' : ' . $tongueLevel. "</div>";
		}
		
	 ?>
  
  <!-- 
    $chaine = "";$retourch = "";
        $j=3;
     
    $l=0;
    while(isset($user_rec[$l][$j]['tongue']['name'])){
    	$l++;
    }
    for($i=0; $i < $l; $i++)
		{
			$chaine = $chaine.$retourch;
			$chaine = $chaine."<label>";
			$tongueName = isset($user_rec[$i][$j]['tongue']['name']) ? $user_rec[$i][$j]['tongue']['name'] : "";
			
			$chaine = $chaine.$tongueName . "</label>"; 
			$chaine = $chaine."<a id='marge'>";
			$lvl = isset($user_rec[$i][$j]['Parle']['estimatedLevel']) ? $user_rec[$i][$j]['Parle']['estimatedLevel'] : "";
			$tongueLevel = "";
			
			if($lvl == "0") { $tongueLevel = "Notions"; };
			if($lvl == "1") { $tongueLevel = "Intermédiaire"; };
			if($lvl == "2") { $tongueLevel = "Courant"; };
			if($lvl == "3") { $tongueLevel = "Bilingue"; };
			if($lvl == "4") { $tongueLevel = "Langue maternelle"; };
			
			$chaine = $chaine.$tongueLevel . "</a>";
			$retourch = "<br/>\n";
		}
		
		echo $chaine;
		unset($chaine);
		unset($retourch);
   -->  
	
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
			
			echo "<div class='container'>".$sportName. "</div>";
		}
		
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
			
			echo "<div class='container'>".$artName. "</div>";
		}
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
			echo "<div class='container'>".$associationName. "</div>";
		}
		
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
			echo "<div class='container'>".$countryName. "</div>";
		}
		
	 ?>

</div>

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

</fieldset>
</form>
</div>
        
<!-- *********************** BLOCS DE DROITE *********************** -->
        
    <div id="rangee">
        <div id="bloc">
        	<div class='hautbloc1'></div>
        	<div class='bloc1'>
				<a href="edition_profil_etudiant.php"> EDITEZ VOTRE PROFIL
										</br><div id="petit">
                                        Modifiez vos informations personnelles constituant votre profil étudiant
										</div>
                </a>
        	</div>
        	<div class='basbloc1'></div>
        </div>
            
        <div id="bloc">
        	<div class='hautbloc1'></div>
        	<div class='bloc1'>
				<a href="estelle/PDF.php"> GENEREZ LE CV
										</br><div id="petit">
                                        Faîtes apparaître un CV automatique à partir des informations de votre profil
										</div>
                </a>
        	</div>
        	<div class='basbloc1'></div>
        </div>

        <div id="bloc">
        	<div class='hautbloc1'></div>
        	<div class='bloc1'>
				<a href="#"> IMPORTEZ VOTRE CV
										</br><div id="petit">
                                        Importez votre CV personnel sur Relate
										</div>
                </a>
        	</div>
        	<div class='basbloc1'></div>
        </div>

        <div id="bloc">
        	<div class='hautbloc1'></div>
        	<div class='bloccompte'> VOTRE COMPTE
										</br></br><a href="change-pwd.php">
                                        Changez votre mot de passe
										</a>
                						
        	</div>
        	<div class='basbloc1'></div>
        </div>        
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

<!-- ************************ FIN CONTENEUR ************************ -->    
    </div>

<!-- ************************ FOOTER ************************ -->

<div id="footer" class="profil">

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
