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

class CENTRESDINTERET
{
	
function TitreCentresdInteret($pdf)
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
    $pdf->Cell(0,-3,"Centres d'intérêt",0,1,'L',false);
    // Saut de ligne
    $pdf->Ln(10);
}

function CorpsCentresdInteret($pdf,$categorie,$liste)
{
    // Arial 11 Gras
    $pdf->SetFont('Arial','BI',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Nom de la catégorie
	$pdf->Cell(30,0,"$categorie");
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Liste
	$chaine="$liste[0]";
	/*$wun=$pdf->GetStringWidth($liste[0]);*/
	/*$pdf->Cell($wun,0,"$liste[0]");*/
	for ($i=1; $i<count($liste); $i++)
	{
	/*$w=$pdf->GetStringWidth($liste[$i])+2;
	$pdf->Cell($w,0,", $liste[$i]");*/
	$chaine=$chaine.", $liste[$i]";
	}
	// Remontée de ligne
	$pdf->Ln(-2,5);
	// Décalage à droite
	$pdf->Cell(30);
	// Affichage de la chaîne
	$pdf->MultiCell(0,5,$chaine,0,1);
	// Saut de ligne
    $pdf->Ln(2.5);
}

function AssociatifCentresdInteret($pdf,$liste,$role)
{
	// Arial 11 Gras
    $pdf->SetFont('Arial','BI',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Nom de la catégorie
	$pdf->Cell(30,0,"Associatif",0,0);
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Liste
	$chaine="";
	/*$wun=$pdf->GetStringWidth($liste[0]);
	$wunr=$pdf->GetStringWidth($role[0])+3;
	$wunt=$wun+$wunr;*/
	if (empty($role[0])){
	$chaine="$liste[0]";
	     //$pdf->Cell($wun,0,"$liste[0]");
	}
	else {
	     //$pdf->Cell($wunt,0,"$liste[0] ($role[0])");
	$chaine="$liste[0] ($role[0])";
	}
	
	for ($i=1; $i<count($liste); $i++)
	{
	/*$w=$pdf->GetStringWidth($liste[$i])+2;
	$wr=$pdf->GetStringWidth($role[$i])+4;
	$wt=$w+$wr;*/
	if (empty($role[$i])){
	$chaine=$chaine.", $liste[$i]";	
	     //$pdf->Cell($w,0,", $liste[$i]");
	}
	else {
	$chaine=$chaine.", $liste[$i] ($role[$i])";
	     //$pdf->Cell($wt,0,", $liste[$i] ($role[$i])");
	}
	}
	// Remontée de ligne
	$pdf->Ln(-2,5);
	// Décalage à droite
	$pdf->Cell(30);
	// Affichage de la chaîne
	$pdf->MultiCell(0,5,$chaine,0,1);
	// Saut de ligne
    $pdf->Ln(2.5);
}

function AjouterCentresdInteret($pdf,$categorie,$liste)
{
    $this->CorpsCentresdInteret($pdf,$categorie,$liste);
}

}
?>
