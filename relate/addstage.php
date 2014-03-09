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

if(isset($_POST['Ajouter'])) {
	$fgmembersite->DBLogin();
	$email = $fgmembersite->UserEmail();
	$type = $fgmembersite->UserType();
	$user_rec = array();
	$fgmembersite->GetUserIDFromEmail($email, $type, $user_rec);
	
	$id_society = $user_rec['id_society'];
	
	if (isset($_POST['dateDeDebut_month']) && isset($_POST['dateDeDebut_year'])
			&& $_POST['dateDeDebut_month'] != '' && $_POST['dateDeDebut_year'] != '') {
	
		if (isset($_POST['dateDeDebut_day']))
		{
			$day = $_POST['dateDeDebut_day'];
		}
		else
		{
			$day = '01';
		}
	
		$year = $_POST['dateDeDebut_year'];
		$month =$_POST['dateDeDebut_month'];
	
	
		$_POST["dateDeDebut"] = $year.'-'.$month.'-'.$day;
	
	}
	
	$intitule = $fgmembersite->SanitizeForSQL($_POST['intitule']);
	$maitreDeStage = $fgmembersite->SanitizeForSQL($_POST['maitreDeStage']);
	$emailMaitreDeStage = $fgmembersite->SanitizeForSQL($_POST['emailMaitreDeStage']);
	$typeDeContrat = $fgmembersite->SanitizeForSQL($_POST['typeDeContrat']);
	$durée = $fgmembersite->SanitizeForSQL($_POST['durée']);
	$dateDeDebut = $fgmembersite->SanitizeForSQL($_POST['dateDeDebut']);
	$direction = $fgmembersite->SanitizeForSQL($_POST['direction']);
	$lieuDeTravail = $fgmembersite->SanitizeForSQL($_POST['lieuDeTravail']);
	$remuneration = is_int($fgmembersite->SanitizeForSQL($_POST['remuneration']))?$fgmembersite->SanitizeForSQL($_POST['remuneration']):null;
	$missionsThematiques = $fgmembersite->SanitizeForSQL($_POST['missionsThematiques']);
	$activitesCles_livrablesAttendus = $fgmembersite->SanitizeForSQL($_POST['activitesCles_livrablesAttendus']);
	$competencesExigees = $fgmembersite->SanitizeForSQL($_POST['competencesExigees']);
	$niveauEtudeExige = $fgmembersite->SanitizeForSQL($_POST['niveauEtudeExige']);
	$secteurActivite = $fgmembersite->SanitizeForSQL($_POST['secteurActivite']);
	$description = $fgmembersite->SanitizeForSQL($_POST['description']);
	
	$insqry = "INSERT INTO `principale`.`offreDeStage` (
`intitule` ,
`maitreDeStage` ,
`emailMaitreDeStage` ,
`typeDeContrat` ,
`durée` ,
`dateDeDebut` ,
`direction` ,
`lieuDeTravail` ,
`remuneration` ,
`missionsThematiques` ,
`activitesCles_livrablesAttendus` ,
`competencesExigees` ,
`niveauEtudeExige` ,
`description` ,
`secteurActivite` ,
`id_society`
)
VALUES (
 '{$_POST['intitule']}', 
 '{$_POST['maitreDeStage']}', 
 '{$_POST['emailMaitreDeStage']}', 
 '{$_POST['typeDeContrat']}', 
 '{$_POST['durée']}', 
 '{$_POST['dateDeDebut']}', 
 '{$_POST['direction']}', 
 '{$_POST['lieuDeTravail']}', 
 '{$_POST['remuneration']}', 
 '$missionsThematiques', 
 '{$_POST['activitesCles_livrablesAttendus']}', 
 '{$_POST['competencesExigees']}',
 '{$_POST['niveauEtudeExige']}', 
	'{$_POST['description']}', 
	'$id_society')";
	
	$res = mysql_query($insqry,$fgmembersite->connection);
	
	var_dump($insqry,$res);
	
	
	if(!$res) {
		$fgmembersite->HandleError("Ca n'a pas marché !");
	}
}


var_dump($_POST);



?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr" lang="fr">
<head>
      <meta http-equiv='Content-Type' content='text/html; charset=UTF-8'/>
      <title>Ajouter une offre de stage</title>
      
      
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css" />
    <script type='text/javascript' src='scripts/gen_validatorv31.js'></script>
    <link rel="STYLESHEET" type="text/css" href="style/pwdwidget.css" />
   	<link rel="STYLESHEET" type="text/css" href="popup/changeprofiletest.css" />
   	<script src="scripts/pwdwidget.js" type="text/javascript"></script>
 	<script type="text/javascript" src="popup/jquery-1.8.3.js"></script>
    <link rel="STYLESHEET" type="text/css" href="style/fg_membersite.css">

	<style type="text/css">
		A:link {text-decoration: none; color: #333}
		A:visited {text-decoration: none; color: #333}
		A:active {text-decoration: none; color: #333}
		A:hover {text-decoration: underline; color: red;; color: #000}
	</style>

</head>
<body>
<!--<div id='fg_membersite'>-->
<h2>Ajout d'une proposition de stage</h2>
<p>
Connecté sous: <?= $fgmembersite->UserFullName() ?>
</p>
<p> 
<form id="addstage"  action='<?php echo $fgmembersite->GetSelfScript(); ?>' method='post' accept-charset='UTF-8'>
	<fieldset>    	
    	<legend>Offre de stage</legend>
        <br/>
        
        <label>Intitulé du poste :</label>
    	<input type="text" name="intitule" <?php echo "value = '".$fgmembersite->SafeDisplay('intitule')."'";?>/>
        <br/>
        
        <label>Maître de stage :</label>
        <br/>
        Nom et Prénom :<input type="text" name="maitreDeStage" <?php echo "value = '".$fgmembersite->SafeDisplay('maitreDeStage')."'";?>/>
        Email :<input type="text" name="emailMaitreDeStage" <?php echo "value = '".$fgmembersite->SafeDisplay('emailMaitreDeStage')."'";?>/>
        <br/>
        
        <label>Direction :</label>
    	<input type="text" name="direction" <?php echo "value = '".$fgmembersite->SafeDisplay('direction')."'";?> />
        <br/> 
        
        <select name='typeDeContrat' size="6" multiple="multiple">
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
			<br/>
			
		<label>Période souhaitée :</label>
		
        <br/>
        <label>Début :</label><br/>
						<select name="dateDeDebut_month" onChange="changeDate(this.options[selectedIndex].value);">
							<option value="" <?php $fgmembersite->echoSelected("dateDeDebut_month", "");?>>Mois</option>
							<option value="1" <?php $fgmembersite->echoSelected("dateDeDebut_month", "1");?>>Janvier</option>
							<option value="2" <?php $fgmembersite->echoSelected("dateDeDebut_month", "2");?>>Février</option>
							<option value="3" <?php $fgmembersite->echoSelected("dateDeDebut_month", "3");?>>Mars</option>
							<option value="4" <?php $fgmembersite->echoSelected("dateDeDebut_month", "4");?>>Avril</option>
							<option value="5" <?php $fgmembersite->echoSelected("dateDeDebut_month", "5");?>>Mai</option>
							<option value="6" <?php $fgmembersite->echoSelected("dateDeDebut_month", "6");?>>Juin</option>
							<option value="7" <?php $fgmembersite->echoSelected("dateDeDebut_month", "7");?>>Juillet</option>
							<option value="8" <?php $fgmembersite->echoSelected("dateDeDebut_month", "8");?>>Août</option>
							<option value="9" <?php $fgmembersite->echoSelected("dateDeDebut_month", "9");?>>Septembre</option>
							<option value="10" <?php $fgmembersite->echoSelected("dateDeDebut_month", "10");?>>Octobre</option>
							<option value="11" <?php $fgmembersite->echoSelected("dateDeDebut_month", "11");?>>Novembre</option>
							<option value="12" <?php $fgmembersite->echoSelected("dateDeDebut_month", "12");?>>Décembre</option>
						</select>
						<select name="dateDeDebut_year" id="year">
						<option value="">Année</option>
							<?php 
								for($i = 2015;$i > 1950;$i--) {
									$string ='';
									if($fgmembersite->SafeDisplay('dateDeDebut_year') == "$i") {$string = 'selected="selected"';}
									echo "<option value=\"$i\" $string>$i</option>\n";		
								}
							?>
						</select><br/>
                     
      <label>Durée</label>   
         <select name='durée'>
    	 <option value='' <?php $fgmembersite->echoSelected("durée", "");?> >Durée du stage</option>
    	<option value='0' <?php $fgmembersite->echoSelected("durée", "0");?>>1 à 3 mois</option>
    	<option value='1' <?php $fgmembersite->echoSelected("durée", "1");?>>3 à 6 mois</option>
        <option value='2' <?php $fgmembersite->echoSelected("durée", "2");?>>6 à 9 mois</option>
        <option value='3' <?php $fgmembersite->echoSelected("durée", "3");?>>9 à 12 mois</option>
        <option value='4' <?php $fgmembersite->echoSelected("durée", "4");?>>Un an ou plus</option>
    </select>
    <br/>
                      
            <!-- <label>Qui sommes-nous ?</label><br/>
             <input type="text" name="quisommenous" maxlength="2000"/><br/>
             
             <label>Nos activités dans le monde</label><br/>
             <input type="text" name="activitesdanslemonde" maxlength="2000"/><br/>-->
             
             <label>Le lieu de travail</label><br/>
             <input type="text" name="lieuDeTravail" maxlength="2000" <?php echo "value = '".$fgmembersite->SafeDisplay('lieuDeTravail')."'";?>/><br/>
             
            <label>Missions et thématiques</label><br/>
             <input type="text" name="missionsThematiques" maxlength="2000" <?php echo "value = '".$fgmembersite->SafeDisplay('missionsThematiques')."'";?>/><br/>
             
             <label>Secteur
				d'activité</label>
				<br /> 
			<select name='secteurActivite'>
				<option value="" <?php $fgmembersite->echoSelected("secteurActivite", "");?>>Choisissez un secteur d'activité</option>
				<option value="21" <?php $fgmembersite->echoSelected("secteurActivite", "21");?>>Aéronautique</option>
				<option value="2" <?php $fgmembersite->echoSelected("secteurActivite", "2");?>>Agroalimentaire</option>
				<option value="22" <?php $fgmembersite->echoSelected("secteurActivite", "");?>>Armée / Maintien de l'ordre</option>
				<option value="23" <?php $fgmembersite->echoSelected("secteurActivite", "");?>>Arts / Culture</option>
				<option value="24" <?php /* A COMPLETER */?>>Associations / Humanitaire</option>
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

			</select>
			
				<br />

				<label>Activités Clés /Livrables Attendus</label>
			<br />
				<input type="text" name="activitesCles_livrablesAttendus"
				maxlength="2000"
				<?php echo "value = '".$fgmembersite->SafeDisplay('activitesCles_livrablesAttendus')."'";?> />
			<br />

				<label>Description supplémentaire</label>
			<br />
				<input type="text" name="description" maxlength="128"
				<?php echo "value = '".$fgmembersite->SafeDisplay('description')."'";?> />

				<h3>
					<label>Profil du stagiaire</label>
				</h3>
				<br />
				<label>Connaissances et aptitudes recherchées</label>
			<br />
				<input type="text" name="competencesExigees" maxlength="2000"
				<?php echo "value = '".$fgmembersite->SafeDisplay('competencesExigees')."'";?> />
			<br />

				<!-- <label>Compétences et qualités personnelles</label><br/>
             <input type="text" name="competencespersonnelles" maxlength="2000"/><br/>-->

				<!--  <label>Langues</label><br/>
             <input type="text" name="langues" maxlength="500"/><br/> -->

			<label>Niveau d'étude</label> 
			<select name="niveauEtudeExige">
				<option value="" <?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>></option>
				<option value="L1" <?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>>L1 / Bac+1</option>
				<option value="L2" <?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>>L2 / Bac+2</option>
				<option value="L3" <?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>>L3 / Bac+3</option>
				<option value="M1" <?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>>M1 / Bac+4</option>
				<option value="M2" <?php echo $fgmembersite->echoSelected('niveauEtudeExige','');?>>M2 / Bac+5</option>
			</select>
			<label>Niveau de formation souhaitée</label>
			<br />
				<input type="text" name="niveauEtudeExige" maxlength="50"
				<?php echo "value = '".$fgmembersite->SafeDisplay('niveauEtudeExige')."'";?> />
			<br />

				<!--<label>Type de formation souhaitée</label><br/>
             <input type="text" name="typeformation" maxlength="2000"/><br/>-->

				<label> Rémunération (€)</label>
			<br />
				<input type="text" name="remuneration" maxlength="128"
				<?php echo "value = '".$fgmembersite->SafeDisplay('remuneration')."'";?> />


				<input type="submit" name="Ajouter" />
		</fieldset>
</form>

<p>
<a href='login-home.php'>Accueil</a>
</p>


<!--</div>-->
</body>
</html>
