<?php

require_once('../fpdf.php');
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

class FORMATION
{
	
function TitreFormation(&$pdf)
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
    $pdf->Cell(0,-3,"Formation",0,1,'L',false);
    // Saut de ligne
    $pdf->Ln(10);
}

function SousTitreFormation(&$pdf,$debut,$fin)
{
	// Saut de ligne
    $pdf->Ln(2);
	// Décalage à gauche
    $pdf->Cell(-25.5);
	// Arial Gras Italique 11
    $pdf->SetFont('Arial','BI',11);
	// Couleur bleue
	$pdf->SetTextColor(79,129,189);
    // Titre
	if ($debut!="na"){
		if ($fin!="na"){
    		$pdf->Cell(12,0,"$debut - $fin",0,0,'L',false); }
		else {
			$pdf->SetFont('Arial','BI',8.7);
        	$pdf->Cell(12,0,"Depuis",0,0,'L',false); 
			$pdf->Cell(-0.6);
			$pdf->SetFont('Arial','BI',11);
			$pdf->Cell(12,0,"$debut",0,0,'L',false); }
	}
}



function CorpsFormation(&$pdf,$ecole,$lieu,$intitule,$annee)
{
	// Posiion courante
	$pdf->SetX(35);
	// Arial 11 Gras
    $pdf->SetFont('Arial','B',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Calcul de la longueur du nom de l'école
	$w=$pdf->GetStringWidth($ecole)+2;
	// Nom de l'école
	$pdf->Cell($w,0,"$ecole, ");
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Suite du Texte
    $pdf->Cell(0,0,"à $lieu : $intitule",0,1);
	// Saut de ligne
    //$pdf->Ln(5);
	// Suite du Texte
	//$pdf->Cell(0,0,"En cours de $annee ème année",0,1);
    // Saut de ligne
    $pdf->Ln(7.5);
}

function AjouterFormation(&$pdf,$debut,$fin,$ecole,$lieu,$intitule,$annee)
{
	
	if ($debut!="na"){
	$this->SousTitreFormation($pdf,$debut,$fin); }
   	$this->CorpsFormation($pdf,$ecole,$lieu,$intitule,$annee);
}

function SautDeLigne(&$pdf)
{
    $pdf->Ln(5);
}

}

?>
