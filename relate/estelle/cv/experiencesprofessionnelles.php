<?php

require('../fpdf.php');
require_once("../../include/membersite_config.php");

$user_rec = array();

if(!$fgmembersite->CheckLogin()) //le CheckLogin() effectue un "session_start()"
{
    $fgmembersite->RedirectToURL("../../login.php");
    exit;
}

$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();
$fgmembersite->GetUserFromEmail($email,$type,$user_rec);

class EXPERIENCESPROFESSIONNELLES
{

function TitreExperiencesProfessionnelles($pdf)
{
	// Décalage à gauche
    $pdf->Cell(-6.5);
	// Arial Gras Souligné 16
    $pdf->SetFont('Arial','BU',16);
	// Couleur bleue
	$pdf->SetTextColor(79,129,189);
    // Petit carré bleu
	$pdf->Image('../images/CarreBleu.png');
	// Décalage à droite
    $pdf->Cell(6);
    // Titre
    $pdf->Cell(0,-3,"Expériences professionnelles",0,1,'L',false);
    // Saut de ligne
    $pdf->Ln(10);
}

function CorrigerDate($date)
{
	if ($date<10) {return('0'.$date);}
	else {return($date);}
}

function CorrigerAnnee($annee)
{
	if ($annee>2000) {return($annee - 2000);}
	else {return($annee - 1900);}
}

function SousTitreExperiencesProfessionnelles($pdf,$moisdebut,$anneedebut,$moisfin,$anneefin)
{
	// Afficher correctement les mois
	$valmoisdebut = $this->CorrigerDate($moisdebut) ;
	$valmoisfin = $this->CorrigerDate($moisfin) ;
	// Afficher correctement les années
	$valadebut = $this->CorrigerAnnee($anneedebut) ;
	$valafin = $this->CorrigerAnnee($anneefin) ;
	$valanneedebut = $this->CorrigerDate($valadebut) ;
	$valanneefin = $this->CorrigerDate($valafin) ;
	// Saut de ligne
    $pdf->Ln(2);
	// Décalage à gauche
    $pdf->Cell(-25.5);
	// Arial Gras Italique 11
    $pdf->SetFont('Arial','BI',11);
	// Couleur bleue
	$pdf->SetTextColor(79,129,189);
    // Date
	$beginyear = ($anneedebut!="na")? $valanneedebut:"";
	$endyear = ($anneefin!="na")? $valanneefin:"";
	$beginmonth = ($moisdebut!="na")? $valmoisdebut:"";
	$endmonth = ($moisfin!="na")? $valmoisfin:"";
	
	if ($beginyear!="") {
		if($endyear!=""){
			if (($beginmonth!="")&&($endmonth!="")){
        	$pdf->Cell(12,0,"$beginmonth/$beginyear-$endmonth/$endyear",0,0,'L',false); }
			else {
	    	$pdf->Cell(12,0,"$beginyear-$endyear",0,0,'L',false); }
		}
	
		else{
			if ($beginmonth!=""){
    		$pdf->SetFont('Arial','BI',8.7);
        	$pdf->Cell(12,0,"Depuis",0,0,'L',false); 
			$pdf->Cell(-0.6);
			$pdf->SetFont('Arial','BI',11);
			$pdf->Cell(12,0,"$beginmonth/$beginyear",0,0,'L',false); }
			else {
	    	$pdf->Cell(12,0,"Depuis $anneedebut",0,0,'L',false); }
		}
	}
}

function CorpsExperiencesProfessionnelles($pdf,$entreprise,$lieu,$intitule,$mission)
{
   	// Posiion courante
	$pdf->SetX(35);
   // Arial 11 Gras
    $pdf->SetFont('Arial','B',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Calcul de la longueur du nom de l'école
	$w=$pdf->GetStringWidth($entreprise)+2;
	// Nom de l'entreprise
	$pdf->Cell($w,0,"$entreprise, ");
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Suite du Texte
    $pdf->Cell(0,0,"à $lieu : $intitule",0,1);
	// Saut de ligne
    $pdf->Ln(2.5);
	if (!empty($mission)) {
	// Suite du Texte
	$pdf->MultiCell(0,5,"Mission : $mission",0,1);
	}
    // Saut de ligne
    $pdf->Ln(5.5);
}

function AjouterExperiencesProfessionnelles($pdf,$moisdebut,$anneedebut,$moisfin,$anneefin,$entreprise,$lieu,$intitule,$mission)
{
	$this->SousTitreExperiencesProfessionnelles($pdf,$moisdebut,$anneedebut,$moisfin,$anneefin);
    $this->CorpsExperiencesProfessionnelles($pdf,$entreprise,$lieu,$intitule,$mission);
}

function SautDeLigne(&$pdf)
{
    $pdf->Ln(5);
}

}
?>
