<!-- *********************** DEBUT DE LA COPIE numero 1 DE searchstage.php *********************** -->
<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
if($fgmembersite->UserType() != "etudiant")
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}


$fgmembersite->DBLogin();


$stageTable = "offreDeStage";
$i_stage = 0;
/* gender, typeDeContrat, durée, niveauEtudeExige, secteurActivite, lieuDeTravail */

$gender = "";
if(isset($_POST['gender']) && $_POST['gender'] != "")
{
	$gender = $_POST['gender'];
	$i_stage++;
	$fieldValue = mysql_real_escape_string($gender);
	$stageTable =
	"(SELECT * FROM
	$stageTable
	WHERE gender=\"$fieldValue\") AS stage$i_stage";
}

$typeDeContrat = "";
if(isset($_POST['typeDeContrat']) && $_POST['typeDeContrat'] != "")
{
	$typeDeContrat = $_POST['typeDeContrat'];
	$i_stage++;
	$fieldValue = mysql_real_escape_string($typeDeContrat);
	$stageTable =
	"(SELECT * FROM
	$stageTable
	WHERE typeDeContrat=\"$fieldValue\") AS stage$i_stage";
}
$duree = "";
if(isset($_POST['durée'])  && $_POST['durée'] != "")
{
	$duree = $_POST['durée'];
	$i_stage++;
	$fieldValue = mysql_real_escape_string($duree);
	$stageTable =
	"(SELECT * FROM
	$stageTable
	WHERE durée=\"$fieldValue\") AS stage$i_stage";
}
$niveauEtudeExige = "";
if(isset($_POST['niveauEtudeExige']) && $_POST['niveauEtudeExige'] != "")
{
	$niveauEtudeExige = $_POST['niveauEtudeExige'];
	$i_stage++;
	$fieldValue = mysql_real_escape_string($niveauEtudeExige);
	$stageTable =
	"(SELECT * FROM
	$stageTable
	WHERE niveauEtudeExige=\"$fieldValue\") AS stage$i_stage";
}
$secteurActivite = "";
if(isset($_POST['secteurActivite']) && $_POST['secteurActivite'] != "")
{
	$secteurActivite = $_POST['secteurActivite'];
	$i_stage++;
	$fieldValue = mysql_real_escape_string($secteurActivite);
	$stageTable =
	"(SELECT * FROM
	$stageTable
	WHERE secteurActivite=\"$fieldValue\") AS stage$i_stage";
}
$lieuDeTravail = "";
if(isset($_POST['lieuDeTravail']) && $_POST['lieuDeTravail'] != "")
{
	$lieuDeTravail = $_POST['lieuDeTravail'];
	$i_stage++;
	$fieldValue = mysql_real_escape_string($lieuDeTravail);
	$stageTable =
	"(SELECT * FROM
	$stageTable
	WHERE lieuDeTravail=\"$fieldValue\") AS stage$i_stage";
}





?>
<!-- *********************** Fin DE LA COPIE numero 1 DE searchstage.php *********************** -->

<?PHP
/*require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}*/

$user_rec = array();
if(!$fgmembersite->GetUserIDFromEmail($fgmembersite->UserEmail(),$fgmembersite->UserType(),$user_rec))
{
	return false;
}

$id_user = $user_rec['id'];
$typeOfUser = $user_rec['type'];

?>


<xml version="1.0" encoding="UTF-8">
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Rechercher une offre de stage</title>

	<link rel="STYLESHEET" type="text/CSS" href="css/styleprofil_etudiant.css" media="screen" />
    <link rel="STYLESHEET" type="text/css" href="style/profil_fg_membersite.css" />
	  <link rel="STYLESHEET" type="text/CSS" href="css/style.css" media="screen" />
	  <link rel="STYLESHEET" type="text/css" href="css/stylemessages.css" media="screen" />
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
      
      <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
      <script type="text/javascript" src="popup/jquery-1.8.3.js"></script>
      <style type="text/css">
	  
#fade {
	display: none;
	background: #000; 
	position: fixed; left: 0; top: 0; 
	z-index: 10;
	width: 100%; height: 100%;
	opacity: .80;
	z-index: 9999;
}
.popup_block{
	display: none;
	background: #fff;
	padding: 20px; 	
	border: 20px solid #ddd;
	float: left;
	font-size: 1em;
	position: fixed;
	top: 50%; left: 50%;
	z-index: 99999;
	-webkit-box-shadow: 0px 0px 20px #000;
	-moz-box-shadow: 0px 0px 20px #000;
	box-shadow: 0px 0px 20px #000;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
	border-radius: 10px;
}
img.btn_close {
	float: right; 
	margin: -55px -55px 0 0;
}
.popup p {
	padding: 5px 10px;
	margin: 5px 0;
}
/*--Making IE6 Understand Fixed Positioning--*/
*html #fade {
	position: absolute;
}
*html .popup_block {
	position: absolute;
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

			<div id="titre">
				RECHERCHER UNE OFFRE DE STAGE
			</div>
        
<!-- *********************** MESSAGES RECUS *********************** -->

<div id="messages">

<div id='message'>
				<li class='enteterecus'><a href='ouverture_message.php?n=$i'>
					<div class='expediteur'>{$mess['fromName']} {$mess['fromSurname']}</div> 
					<div class='marge'>{$mess['date']}</div>
			  		$objet
					<div class='marge'><input type='checkbox'/></div></a></li>
				<li class='contenu'>
			  		{$mess['content']}<br />
			  	</li>
			  </div><br />

</div>

<!-- *********************** DEBUT DE LA COPIE numero 2 DE searchstage.php *********************** -->
<div class='edition'>
<div class='rectangletitre'>
Effectuez une recherche parmi nos offres
</div>
</div>

<form id="searchstage"  action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset>
<select name="gender">
	<option value="">Choisissez le genre attendu</option>
	<option value="H">Homme</option>
    <option value="F">Femme</option>
</select>
<br/>


<label>Type d'offre</label>

 <select name='typeDeContrat' size="6" multiple="multiple" >
        		<option value='' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "");?>>Type de contrat</option>
				
				<option value='Stage' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "Stage");?>>Stage</option>
				
				<option value='CDI' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "CDI");?>>CDI</option>
				
				<option value='CDD' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "CDD");?>>CDD</option>
				
				<option value='VIE' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "VIE");?>>VIE</option>
				
				<option value='Alternance' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "Alternance");?>>Alternance</option>
				
				<option value='Freelance' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "Freelance");?>>Freelance</option>
				
				<option value='Autre' 
				<?php $fgmembersite->echoSelected("typeDeContrat", "Autre");?>>Autre</option>
				
</select>
<br>
<label>Durée</label>
<select name='durée'>
    	 <option value='' <?php $fgmembersite->echoSelected("durée", "");?> >Durée du stage</option>
    	<option value='1 à 3 mois' <?php $fgmembersite->echoSelected("durée", "1 à 3 mois");?>>1 à 3 mois</option>
    	<option value='3 à 6 mois' <?php $fgmembersite->echoSelected("durée", "3 à 6 mois");?>>3 à 6 mois</option>
        <option value='6 à 9 mois' <?php $fgmembersite->echoSelected("durée", "6 à 9 mois");?>>6 à 9 mois</option>
        <option value='9 à 12 mois' <?php $fgmembersite->echoSelected("durée", "9 à 12 mois");?>>9 à 12 mois</option>
        <option value='Un an ou plus' <?php $fgmembersite->echoSelected("durée", "Un an ou plus");?>>Un an ou plus</option>
</select>
<br> 
<label>Niveau d'étude</label> 
<select name="niveauEtudeExige">
						<option value=""
						<?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>></option>
						<option value="L1"
						<?php echo $fgmembersite->echoSelected('niveauEtudeExige','L1');?>>L1
							/ Bac+1</option>
						<option value="L2"
						<?php echo $fgmembersite->echoSelected('niveauEtudeExige','L2');?>>L2
							/ Bac+2</option>
						<option value="L3"
						<?php echo $fgmembersite->echoSelected('niveauEtudeExige','L3');?>>L3
							/ Bac+3</option>
						<option value="M1"
						<?php echo $fgmembersite->echoSelected('niveauEtudeExige','M1');?>>M1
							/ Bac+4</option>
						<option value="M2"
						<?php echo $fgmembersite->echoSelected('niveauEtudeExige','M2');?>>M2
							/ Bac+5</option>
					</select><br>

<br>
<label>Secteur d'activité</label>
<select name="secteurActivite">
<option value=""></option>
<option value="21">Aéronautique</option>
<option value="2">Agroalimentaire</option>
<option value="22">Armée / Maintien de l'ordre</option>
<option value="23">Arts / Culture</option>
<option value="24">Associations / Humanitaire</option>
<option value="8">Audit / Conseil</option>
<option value="4">Automobile / Equipementiers</option>
<option value="5">Banque / Assurances / Crédit</option>
<option value="3">BTP - Génie civil</option>
<option value="25">Centres d'appel</option>
<option value="26">Comptabilité / Contrôle de gestion</option>
<option value="9">Distribution / Commerce</option>
<option value="27">e-commerce / Internet</option>
<option value="7">Edition / Publicité / Média</option>
<option value="28">Electro(tech)nique</option>
<option value="29">Energie / Pétrole / Nucléaire</option>
<option value="30">Enseignement / Formation</option>
<option value="31">Environnement</option>
<option value="32">Franchise</option>
<option value="13">Immobilier - BTP</option>
<option value="14">Industrie / Mécanique / Maintenance</option>
<option value="11">Informatique / Telecom</option>
<option value="33">Juridique</option>
<option value="16">Luxe / Mode / Textile</option>
<option value="34">Nettoyage / Sécurité</option>
<option value="1">Non précisé</option>
<option value="17">Pharmacie / Biotechnologie / Chimie</option>
<option value="12">Restauration / Hôtellerie</option>
<option value="35">RH / Recrutement / Intérim</option>
<option value="18">Santé / Social</option>
<option value="6">Services Publics / Collectivités</option>
<option value="15">SSII</option>
<option value="20">Tourisme / Loisirs</option>
<option value="19">Transport / Logistique</option>

</select><br>

<br>

<div style="float:left">
  <label>Lieu</label>
  <select name="lieuDeTravail" size="1" onchange="filtre_lieu()">
  <option value="">Choisissez le lieu de travail souhaité</option>
  <option value="58">France</option>
  </select>
</div>
<br/>

<input type="submit" value="Chercher" />
</fieldset>
</form>

<?php 

if($i_stage > 0) {
	$qry =
	"SELECT stage$i_stage.intitule,stage$i_stage.typeDeContrat, stage$i_stage.lieuDeTravail FROM
	$stageTable";

	$result = mysql_query($qry);

	var_dump($result,$qry);

	while ($donnees = mysql_fetch_assoc($result))
	{
		var_dump($donnees);
	}
	echo "<br>";
}

?>

<p>
<a href='login-home.php'>Accueil</a>
</p>
<!-- *********************** FIN DE LA COPIE numero 2 DE searchstage.php *********************** -->

<br />



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


<!-- *********************** FIN CONTENEUR*********************** -->

</div>        
   

<!-- ************************ FOOTER ************************ -->

<!--
<div id="footer" class="messages">

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
-->

<!-- ************************ FIN GROSCONTENEUR ************************ -->    
    </div>
	
</body>
</html>
