<?php

require_once('fpdf.php');
require_once('entete.php');
require_once('formation.php');
require_once('experiencesprofessionnelles.php');
require_once('langues.php');
require_once('centresdinteret.php');



$user_rec = array();

if(!$fgmembersite->CheckLogin()) //le CheckLogin() effectue un "session_start()"
{
	$fgmembersite->RedirectToURL("../login.php");
	exit;
}

$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();
$fgmembersite->GetProfileFromEmail($email,$user_rec,9);


//Génération du PDF
$pdf = new FPDF();
$pdf->SetTopMargin(15);
$pdf->SetDrawColor(79,129,189);
$pdf->AddPage();
$pdf->SetLeftMargin(35);
$pdf->SetRightMargin(9.5);

$pdf->SetAuthor('CV',true);


// Echange
function Echange(&$tab,$a,$b)
{
	$varEchange = $tab[$a];
	$tab[$a] = $tab[$b];
	$tab[$b] = $varEchange;
}

//En Tete
$entete = new ENTETE();
if (file_exists("../membres/pics/".$user_rec[0][0]['user']['id']."/thumbnail/thumb_pic.jpg")){
	$entete->Photo($pdf,$user_rec[0][0]['user']['id']);
}
if ((isset($user_rec[0][0]['user']['name']))||(isset($user_rec[0][0]['user']['surname']))) {
	$entete->Head($pdf,$user_rec[0][0]['user']['name'],strtoupper($user_rec[0][0]['user']['surname']));
}
if ((isset($user_rec[0][0]['user']['gender']))||(isset($user_rec[0][0]['student']['birthdate_day']))||(isset($user_rec[0][0]['student']['birthdate_month']))||(isset($user_rec[0][0]['student']['birthdate_year']))) {
	$entete->Naissance($pdf,$user_rec[0][0]['user']['gender'],$user_rec[0][0]['student']['birthdate_day'],$user_rec[0][0]['student']['birthdate_month'],$user_rec[0][0]['student']['birthdate_year']);
}
if (isset($user_rec[0][0]['student']['nationality'])) {
	$entete->Nationalite($pdf,$user_rec[0][0]['student']['nationality']);
}
if (isset($user_rec[0][0]['town']['name'])) {
	$entete->Lieu($pdf,$user_rec[0][0]['town']['name']);
}
if (isset($user_rec[0][0]['user']['phonenumber'])) {
	$entete->Tel($pdf,$user_rec[0][0]['user']['phonenumber']);
}
if (isset($user_rec[0][0]['user']['email'])) {
	$entete->Email($pdf,$user_rec[0][0]['user']['email']);
}
if (isset($user_rec[0][0]['student']['seeking'])) {
	$entete->Titre($pdf,$user_rec[0][0]['user']['gender'],$user_rec[0][0]['establishment']['name'],$user_rec[0][0]['student']['seeking'],$user_rec[0][0]['student']['seekingDuration'],$user_rec[0][0]['student']['seekingDomain'],$user_rec[0][0]['student']['disponibility_month'],$user_rec[0][0]['student']['disponibility_year']);
}


//Rubriques
//Formation
$formation = new FORMATION();
if (isset($user_rec[0][0]['AEtudieA'])) {

	$formation->TitreFormation($pdf);

	for ($i=0; $i<9 && isset($user_rec[$i][0]['establishment']); $i++)
	{
		$formation->AjouterFormation(
				$pdf,
				$user_rec[$i][0]['AEtudieA']['beggining_year'],
				$user_rec[$i][0]['AEtudieA']['end_year'],
				ucfirst($user_rec[$i][0]['establishment']['name']),
				ucfirst($user_rec[$i][0]['town']['name']),
				ucfirst($user_rec[$i][0]['diploma']['name']),
				"FORMATION EN COURS ? wip");
	}
	$formation->SautDeLigne($pdf);
}

//Expériences professionnelles

$experiencespro = new EXPERIENCESPROFESSIONNELLES();
if (isset($user_rec[0][1]['ATravailleA'])) {

	$experiencespro->TitreExperiencesProfessionnelles($pdf);

	for ($i=0; $i<9 && isset($user_rec[$i][1]['ATravailleA']['id']); $i++)
	{
		$experiencespro->AjouterExperiencesProfessionnelles($pdf,$user_rec[$i][1]['ATravailleA']['beggining_month'],
				$user_rec[$i][1]['ATravailleA']['beggining_year'],
				$user_rec[$i][1]['ATravailleA']['end_month'],
				$user_rec[$i][1]['ATravailleA']['end_year'],
				ucfirst($user_rec[$i][1]['society']['raisonSociale']),
				ucfirst($user_rec[$i][1]['town']['name']),
				ucfirst($user_rec[$i][1]['proexperience']['job']),
				ucfirst($user_rec[$i][1]['ATravailleA']['mission']));
	}

	$experiencespro->SautDeLigne($pdf);
}


//Langues

$langues = new LANGUES();

$langues->TitreLangues(&$pdf);

for ($i=0; $i<9 && isset($user_rec[$i][3]['tongue']['name']); $i++)
{
	$langues->AjouterLangues($pdf,ucfirst($user_rec[$i][3]['tongue']['name']),
			$user_rec[$i][3]['Parle']['estimatedLevel']);
}

//Conversion de tableau
for($l=0; $l <9 && isset($user_rec[$l][2]['infoLanguage']); $l++) {
	$user_rec['listinfolanguages'][$l] = $user_rec[$l][2]['infoLanguage']['name'];
}

for($l=0; $l <9 && isset($user_rec[$l][8]['software']); $l++) {
	$user_rec['listsoftware'][$l] = $user_rec[$l][8]['software']['name'];
}
//Fin conversion

	$langues->PetitSautDeLigne($pdf);
	
if (isset($user_rec['listinfolanguages'])) {
	$langues->Informatique($pdf,$user_rec['listinfolanguages']);
}

if (isset($user_rec['listsoftware'])) {
	$langues->Logiciels($pdf,$user_rec['listsoftware']);
}


	$langues->SautDeLigne($pdf);


//Centres d'intérêt

//Conversion de tableau

for($l=0; $l <9 && isset($user_rec[$l][4]['sport']); $l++) {
	$user_rec['listsportname'][$l] = $user_rec[$l][4]['sport']['name'];
}
for($l=0; $l <9 && isset($user_rec[$l][5]['art']); $l++) {
	$user_rec['listartname'][$l] = $user_rec[$l][5]['art']['name'];
}
for($l=0; $l <9 && isset($user_rec[$l][7]['country']); $l++) {
	$user_rec['listtravelplace'][$l] = $user_rec[$l][7]['country']['name'];
}
for($l=0; $l <9 && isset($user_rec[$l][6]['association']); $l++) {
	$user_rec['listassocname'][$l] = $user_rec[$l][6]['association']['name'];
}
for($l=0; $l <9 && isset($user_rec[$l][6]['AFaitPartieDeAssociation']); $l++) {
	$user_rec['listassocrole'][$l] = $user_rec[$l][6]['AFaitPartieDeAssociation']['role'];
}

//Fin de conversion

$centresdint = new CENTRESDINTERET();
if (
		(isset($user_rec['listsportname']))||
		(isset($user_rec['listartname']))||
		(isset($user_rec['listtravelplace']))||
		(isset($user_rec['listassocname']))
	)
{
	$centresdint->TitreCentresdInteret($pdf);
}
if (isset($user_rec['listsportname'])) {
	$centresdint->AjouterCentresdInteret($pdf,'Sports',$user_rec['listsportname']);
}
if (isset($user_rec['listartname'])) {
	$centresdint->AjouterCentresdInteret($pdf,'Arts',$user_rec['listartname']);
}
if (isset($user_rec['listtravelplace'])) {
	$centresdint->AjouterCentresdInteret($pdf,'Voyages',$user_rec['listtravelplace']);
}
if (isset($user_rec['listassocname'])) {
	$centresdint->AssociatifCentresdInteret($pdf,$user_rec['listassocname'],$user_rec['listassocrole']);
}


//Fin
$pdf->Output();
?>