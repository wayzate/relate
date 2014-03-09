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
$fgmembersite->GetUserProfileFromEmail($email,$type,$user_rec);


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

//Tri
for ($i=0; $i<count($user_rec['listformname']); $i++)
{
	for ($j=$i+1; $j<count($user_rec['listformname']); $j++)
	{
		if (($user_rec['listformendyear'][$i]!="na")&&($user_rec['listformendyear'][$j]!="na"))
		{
		if ($user_rec['listformendyear'][$i]<$user_rec['listformendyear'][$j])
		{ 
		Echange($user_rec['listformbegyear'],$i,$j);
		Echange($user_rec['listformendyear'],$i,$j);
		Echange($user_rec['listformbegmonth'],$i,$j);
		Echange($user_rec['listformendmonth'],$i,$j);
		Echange($user_rec['listformname'],$i,$j);
		Echange($user_rec['listformtown'],$i,$j);
		Echange($user_rec['listformdipname'],$i,$j);
		Echange($user_rec['listforminprogress'],$i,$j);
		}
		if ($user_rec['listformendyear'][$i]==$user_rec['listformendyear'][$j])
		{ 
			if ($user_rec['listformbegyear'][$i]<$user_rec['listformbegyear'][$j]) 
			{ 
			Echange($user_rec['listformbegyear'],$i,$j);
			Echange($user_rec['listformendyear'],$i,$j);
			Echange($user_rec['listformbegmonth'],$i,$j);
		    Echange($user_rec['listformendmonth'],$i,$j);
			Echange($user_rec['listformname'],$i,$j);
			Echange($user_rec['listformtown'],$i,$j);
			Echange($user_rec['listformdipname'],$i,$j);
			Echange($user_rec['listforminprogress'],$i,$j);
		    }
		}
		}
	}
}

//En Tete
$entete = new ENTETE();
if (file_exists("../membres/pics/".$user_rec['id_user']."/thumbnail/thumb_pic.jpg")){
$entete->Photo($pdf,$user_rec['id_user']); }
if ((!empty($user_rec['name']))||(!empty($user_rec['surname']))) {
$entete->Head($pdf,$user_rec['name'],strtoupper($user_rec['surname'])); }
if ((!empty($user_rec['gender']))||(!empty($user_rec['day']))||(!empty($user_rec['month']))||(!empty($user_rec['year']))) {
$entete->Naissance($pdf,$user_rec['gender'],$user_rec['day'],$user_rec['month'],$user_rec['year']); }
if (!empty($user_rec['nationality'])) {
$entete->Nationalite($pdf,$user_rec['nationality']); }
if (!empty($user_rec['adresstown'])) {
$entete->Lieu($pdf,$user_rec['adresstown']);}
if (!empty($user_rec['telnumber'])) {
$entete->Tel($pdf,$user_rec['telnumber']); }
if (!empty($user_rec['email'])) {
$entete->Email($pdf,$user_rec['email']); }
if (!empty($user_rec['seeking'])) {
$entete->Titre($pdf,$user_rec['gender'],$user_rec['listformname'][0],$user_rec['seeking'],$user_rec['seeking_duration'],$user_rec['seeking_domain'],$user_rec['dispomonth'],$user_rec['dispoyear']); }


//Rubriques
	//Formation
$formation = new FORMATION();
if (!empty($user_rec['listformname'])) {
	
$formation->TitreFormation($pdf);

for ($i=0; $i<count($user_rec['listformname']); $i++)
{

$formation->AjouterFormation($pdf,$user_rec['listformbegyear'][$i],$user_rec['listformendyear'][$i],ucfirst($user_rec['listformname'][$i]),ucfirst($user_rec['listformtown'][$i]),ucfirst($user_rec['listformdipname'][$i]),$user_rec['listforminprogress'][$i]);
}
$formation->SautDeLigne($pdf); }

	//Expériences professionnelles

		//Tri et Execution
for ($i=0; $i<count($user_rec['listproexpname']); $i++)
{
	for ($j=$i+1; $j<count($user_rec['listproexpname']); $j++)
	{
		if ($user_rec['listproexpendyear'][$i]<$user_rec['listproexpendyear'][$j])
		{ 
		Echange($user_rec['listproexpbegyear'],$i,$j);
		Echange($user_rec['listproexpendyear'],$i,$j);
		Echange($user_rec['listproexpbegmonth'],$i,$j);
		Echange($user_rec['listproexpendmonth'],$i,$j);
		Echange($user_rec['listproexpname'],$i,$j);
		Echange($user_rec['listproexptown'],$i,$j);
		Echange($user_rec['listproexpnamejob'],$i,$j);
		Echange($user_rec['listproexpmission'],$i,$j);
		}
		if ($user_rec['listproexpendyear'][$i]==$user_rec['listproexpendyear'][$j])
		{ 
			if ($user_rec['listproexpendmonth'][$i]<$user_rec['listproexpendmonth'][$j]) 
			{ 
			Echange($user_rec['listproexpbegyear'],$i,$j);
			Echange($user_rec['listproexpendyear'],$i,$j);
			Echange($user_rec['listproexpbegmonth'],$i,$j);
			Echange($user_rec['listproexpendmonth'],$i,$j);
			Echange($user_rec['listproexpname'],$i,$j);
			Echange($user_rec['listproexptown'],$i,$j);
			Echange($user_rec['listproexpnamejob'],$i,$j);
			Echange($user_rec['listproexpmission'],$i,$j);
		    }
			if ($user_rec['listproexpendmonth'][$i]==$user_rec['listproexpendmonth'][$j])
			{
				if ($user_rec['listproexpbegyear'][$i]>$user_rec['listproexpbegyear'][$j]) 
				{ 
				Echange($user_rec['listproexpbegyear'],$i,$j);
				Echange($user_rec['listproexpendyear'],$i,$j);
				Echange($user_rec['listproexpbegmonth'],$i,$j);
				Echange($user_rec['listproexpendmonth'],$i,$j);
				Echange($user_rec['listproexpname'],$i,$j);
				Echange($user_rec['listproexptown'],$i,$j);
				Echange($user_rec['listproexpnamejob'],$i,$j);
				Echange($user_rec['listproexpmission'],$i,$j);
				}
				if ($user_rec['listproexpbegyear'][$i]==$user_rec['listproexpbegyear'][$j])
				{ 
					if ($user_rec['listproexpbegmonth'][$i]<$user_rec['listproexpbegmonth'][$j]) 
				Echange($user_rec['listproexpbegyear'],$i,$j);
				Echange($user_rec['listproexpendyear'],$i,$j);
				Echange($user_rec['listproexpbegmonth'],$i,$j);
				Echange($user_rec['listproexpendmonth'],$i,$j);
				Echange($user_rec['listproexpname'],$i,$j);
				Echange($user_rec['listproexptown'],$i,$j);
				Echange($user_rec['listproexpnamejob'],$i,$j);
				Echange($user_rec['listproexpmission'],$i,$j);
				}
		    }
		}
	}
}

$experiencespro = new EXPERIENCESPROFESSIONNELLES();
if (!empty($user_rec['listproexpname'])) {

$experiencespro->TitreExperiencesProfessionnelles($pdf);

for ($i=0; $i<count($user_rec['listproexpname']); $i++)
{
$experiencespro->AjouterExperiencesProfessionnelles($pdf,$user_rec['listproexpbegmonth'][$i],$user_rec['listproexpbegyear'][$i],$user_rec['listproexpendmonth'][$i],$user_rec['listproexpendyear'][$i],ucfirst($user_rec['listproexpname'][$i]),ucfirst($user_rec['listproexptown'][$i]),ucfirst($user_rec['listproexpnamejob'][$i]),ucfirst($user_rec['listproexpmission'][$i]));
}

$experiencespro->SautDeLigne($pdf); }


	//Langues

		//Tri et Execution
for ($i=0; $i<count($user_rec['listtonguename']); $i++)
{
	for ($j=$i+1; $j<count($user_rec['listtonguename']); $j++)
	{
		if ($user_rec['listtonguelvl'][$i]<$user_rec['listtonguelvl'][$j])
		{ 
		Echange($user_rec['listtonguename'],$i,$j);
		Echange($user_rec['listtonguelvl'],$i,$j);
		}
	}
}
	
$langues = new LANGUES();
if (!empty($user_rec['listtonguename'])) {

$langues->TitreLangues(&$pdf);

if ((!empty($user_rec['listtonguename']))||(!empty($user_rec['listtonguelvl']))) {
for ($i=0; $i<count($user_rec['listtonguename']); $i++)
{
$langues->AjouterLangues($pdf,ucfirst($user_rec['listtonguename'][$i]),$user_rec['listtonguelvl'][$i]);
}
}
if (!empty($user_rec['listinfolanguages'])) {
$langues->Informatique($pdf,$user_rec['listinfolanguages']); }

$langues->SautDeLigne($pdf); }


	//Centres d'intérêt
$centresdint = new CENTRESDINTERET();
if ((!empty($user_rec['listsportname']))||(!empty($user_rec['listsportname']))||(!empty($user_rec['listartname']))||(!empty($user_rec['listtravelplace']))||(!empty($user_rec['listassocname']))) {
$centresdint->TitreCentresdInteret($pdf); }
if (!empty($user_rec['listsportname'])) {
$centresdint->AjouterCentresdInteret($pdf,'Sports',$user_rec['listsportname']); }
if (!empty($user_rec['listartname'])) {
$centresdint->AjouterCentresdInteret($pdf,'Arts',$user_rec['listartname']); }
if (!empty($user_rec['listtravelplace'])) {
$centresdint->AjouterCentresdInteret($pdf,'Voyages',$user_rec['listtravelplace']); }
if (!empty($user_rec['listassocname'])) {
$centresdint->AssociatifCentresdInteret($pdf,$user_rec['listassocname'],$user_rec['listassocrole']); }


//Fin
$pdf->Output();
?>