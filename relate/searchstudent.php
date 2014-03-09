<?PHP
require_once("./include/membersite_config.php");

if(!$fgmembersite->CheckLogin())
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}
if($fgmembersite->UserType() != "entreprise")
{
    $fgmembersite->RedirectToURL("login.php");
    exit;
}


$fgmembersite->DBLogin();

$studentTable = "student";
$i_stud = 0;
$i_temp = 0;

$seeking = "";
if(isset($_POST['seeking']))
{
	$seeking = $_POST['seeking'];
	$i_stud++;
	$fieldValue = mysql_real_escape_string($_POST['seeking']);
	$studentTable =
	"(SELECT * FROM
	$studentTable
	WHERE seeking=\"$fieldValue\") AS stud$i_stud";

}


$seekingDuration="";
if(isset($_POST['seekingDuration']) && $_POST['seekingDuration'] != "")
{
	$seekingDuration = $_POST['seekingDuration'];

	$i_stud++;
	$fieldValue = mysql_real_escape_string($_POST['seekingDuration']);
	$studentTable =
	"(SELECT * FROM
	$studentTable
	WHERE seekingDuration='$fieldValue')
	AS stud$i_stud";


}

$disponibility="";
if(isset($_POST['disponibility']) && $_POST['disponibility'] != "")
{
	$disponibility = $_POST['disponibility'];
	$i_stud++;
	$fieldValue = mysql_real_escape_string($_POST['seekingDuration']);
	$studentTable =
	"(SELECT * FROM
	$studentTable
	WHERE seekingDuration='$fieldValue')
	AS stud$i_stud";
}

$studentYear="";
if(isset($_POST['studentYear']) && $_POST['studentYear'] != "")
{
	$studentYear = $_POST['studentYear'];
	$i_stud++;
	$fieldValue = mysql_real_escape_string($_POST['studentYear']);
	$studentTable =
	"(SELECT * FROM
	$studentTable
	WHERE studentYear='$fieldValue')
	AS stud$i_stud";

}

$seekingDomain="";
if(isset($_POST['seekingDomain']) && $_POST['seekingDomain'] != "")
{
	$seekingDomain = $_POST['seekingDomain'];
	$i_stud++;
	$fieldValue = mysql_real_escape_string($_POST['seekingDomain']);
	$studentTable =
	"(SELECT * FROM
	$studentTable
	WHERE seekingDomain='$fieldValue')
	AS stud$i_stud";

}


for($i=0;$i <= 5; $i++) {
	$tongue[$i] = isset($_POST["tongue_$i"])?$_POST["tongue_$i"]:"";
}
$i = 0;
while(isset($_POST["tongue_$i"]) && $_POST["tongue_$i"] != "")
{
	
	
	$studm1 = ($i_stud ==0)?"student":"stud$i_stud";

	$i_stud++;$i_temp++;

	$fieldValue = mysql_real_escape_string($_POST["tongue_$i"]);
	$studentTable =
	"(SELECT $studm1.* FROM
	$studentTable,
	(SELECT id_student FROM `Parle` WHERE id_tongue IN (SELECT id FROM `tongue` WHERE `name`='$fieldValue')) AS temp$i_temp
	WHERE $studm1.`id` = temp$i_temp.`id_student`)
	AS stud$i_stud";


	$i++;
}
unset($i);


for($i=0;$i <= 5; $i++) {
	$infoLanguage[$i] = isset($_POST["infoLanguage_$i"])?$_POST["infoLanguage_$i"]:"";
}
$i = 0;

while(isset($_POST["infoLanguage_$i"]) && $_POST["infoLanguage_$i"] != "")
{
	$infoLanguage[$i] = $_POST["infoLanguage_$i"];
	$studm1 = ($i_stud ==0)?"student":"stud$i_stud";

	$i_stud++;$i_temp++;

	$fieldValue = mysql_real_escape_string($_POST["infoLanguage_$i"]);
	$studentTable =
	"(SELECT $studm1.* FROM
	$studentTable,
	(SELECT id_student FROM `SaitProgrammerEn` WHERE id_infoLanguage IN (SELECT id FROM `infoLanguage` WHERE `name`='$fieldValue')) AS temp$i_temp
	WHERE $studm1.`id` = temp$i_temp.`id_student`)
	AS stud$i_stud";


	$i++;
}
unset($i);






?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Effectuer une recherche étudiant</title>
      <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">
</head>
<body>
<div id='fg_membersite_content'>
<h2>Recherche filtrée</h2>
<p>
Connecté sous: <?= $fgmembersite->UserFullName() ?>
</p>
<p> 


<form id="searchstudent"  action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
<fieldset>
				
				<select name='seeking' size="6" multiple="multiple">
					<option value='Stage' <?php if($seeking == 'Stage') {echo "selected='selected'";}?>
					>Stage</option>
					<option value='CDI' <?php if($seeking == 'CDI') {echo "selected='selected'";}?>
					>CDI</option>
					<option value='CDD' <?php if($seeking == 'CDD') {echo "selected='selected'";}?>
					>CDD</option>
					<option value='VIE' <?php if($seeking == 'VIE') {echo "selected='selected'";}?>
					>VIE</option>
					<option value='Alternance' <?php if($seeking == 'Alternance') {echo "selected='selected'";}?>
					>Alternance</option>
					<option value='Freelance' <?php if($seeking == 'Freelance') {echo "selected='selected'";}?>
					>Freelance</option>
					<option value='Autre' <?php if($seeking == 'Autre') {echo "selected='selected'";}?>
					>Autre</option>
				</select> 
				
				<select name='seekingDuration'>
					
					<option value=''  <?php if($seekingDuration == '') {echo "selected='selected'";}?>
					>Durée
						du stage</option>
					<option value='0' <?php if($seekingDuration == '0') {echo "selected='selected'";}?>
					>1
						à 3 mois</option>
					<option value='1' <?php if($seekingDuration == '1') {echo "selected='selected'";}?>
					>3
						à 6 mois</option>
					<option value='2' <?php if($seekingDuration == '2') {echo "selected='selected'";}?>
					>6
						à 9 mois</option>
					<option value='3' <?php if($seekingDuration == '3') {echo "selected='selected'";}?>
					>9
						à 12 mois</option>
					<option value='4' <?php if($seekingDuration == '4') {echo "selected='selected'";}?>
					>Un
						an ou plus</option>
				</select> 
				
				<select name='studentYear'>
					<option value='' <?php if($studentYear == '') {echo "selected='selected'";}?>> Année d'étude </option>
					<option value='1A' <?php if($studentYear == '1A') {echo "selected='selected'";}?>>Première année</option>
					<option value='2A' <?php if($studentYear == '2A') {echo "selected='selected'";}?>>Deuxième année</option>
					<option value='3A' <?php if($studentYear == '3A') {echo "selected='selected'";}?>>Troisième année</option>
					<option value='cesure' <?php if($studentYear == 'cesure') {echo "selected='selected'";}?>>Césure</option>
					<option value='autre' <?php if($studentYear == 'autre') {echo "selected='selected'";}?>>Autre</option>
				
				</select>
				
				<select name='seekingDomain'>
					<option value="" >Domaine Recherché</option>
					<option value="0" <?php if($seekingDomain == '0') {echo "selected='selected'";}?>>Audit/Conseil</option>
      				<option value="1" <?php if($seekingDomain == '1') {echo "selected='selected'";}?>>Banque/Assurance</option>
      				<option value="2" <?php if($seekingDomain == '2') {echo "selected='selected'";}?>>BTP/Immobilier</option>
      				<option value="3" <?php if($seekingDomain == '3') {echo "selected='selected'";}?>>Distribution/Consommation</option>
      				<option value="4" <?php if($seekingDomain == '4') {echo "selected='selected'";}?>>Energie/Environnement</option>
      				<option value="5" <?php if($seekingDomain == '5') {echo "selected='selected'";}?>>Industrie/Chimie</option>
      				<option value="6" <?php if($seekingDomain == '6') {echo "selected='selected'";}?>>IT/Telecom/Multimedia</option>
      				<option value="7" <?php if($seekingDomain == '7') {echo "selected='selected'";}?>>Pharmacie/Santé</option>
      				<option value="8" <?php if($seekingDomain == '8') {echo "selected='selected'";}?> >Transport/Service</option>				
				</select>
				
				<label for="Langues">Langues</label>
				<?php 
				for($i=0;$i <= 5; $i++) {
					echo "<input type='text' name='tongue_$i' value='{$tongue[$i]}' />";
				}
				?>
				<label for="Informatique">Informatique</label>
				<?php 
				for($i=0;$i <= 5; $i++) {
					echo "<input type='text' name='infoLanguage_$i' value='{$infoLanguage[$i]}' />";
				}
				?>
				
				
			  	
				
				
				<input type="submit" value="Ok"/>
</fieldset>
</form>
<?php

if($i_stud > 0) {
	$qry =
	"SELECT stud$i_stud.seekingDuration,stud$i_stud.studentYear, name,surname FROM user,
	$studentTable
	WHERE user.id=stud$i_stud.id_user";
	
	$result = mysql_query($qry);
	
	var_dump($result,$qry);
	
	while ($donnees = mysql_fetch_assoc($result))
	{
		var_dump($donnees);
		}
		echo "<br>";
}
//$result->closeCursor();


?>

</p>
<p>
<a href='login-home.php'>Accueil</a>
</p>
</div>
</body>
</html>
