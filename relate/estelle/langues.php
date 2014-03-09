<?php

require_once('fpdf.php');
require_once("../include/membersite_config.php");

$user_rec = array();

if(!$fgmembersite->CheckLogin()) //le CheckLogin() effectue un "session_start()"
{
    $fgmembersite->RedirectToURL("../login.php");
    exit;
}

$email = $fgmembersite->UserEmail();
$type = $fgmembersite->UserType();
$fgmembersite->GetUserFromEmail($email,$type,$user_rec);

class LANGUES
{

function TitreLangues(&$pdf)
{
	// Décalage à gauche
    $pdf->Cell(-6.5);
	// Arial Gras Souligné 16
    $pdf->SetFont('Arial','BU',16);
	// Couleur bleue
	$pdf->SetTextColor(79,129,189);
    // Petit carré bleu
	$pdf->Image('CarreBleu.png');
	// Décalage à droite
    $pdf->Cell(6);
    // Titre
    $pdf->Cell(0,-3,"Langues et Compétences informatiques",0,1,'L',false);
    // Saut de ligne
    $pdf->Ln(10);
}

function CorpsLangues(&$pdf,$langue,$niveau)
{
    // Arial 11 Gras
    $pdf->SetFont('Arial','BI',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Nom de la langue
	$pdf->Cell(30,0,"$langue");
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Niveau
	if ($niveau == 4) {$pdf->Cell(0,0,"Langue maternelle");}
	if ($niveau == 3) {$pdf->Cell(0,0,"Bilingue");}
	if ($niveau == 2) {$pdf->Cell(0,0,"Courant");}
	if ($niveau == 1) {$pdf->Cell(0,0,"Intermédiaire");}
	if ($niveau == 0) {$pdf->Cell(0,0,"Notions");}
	// Saut de ligne
    $pdf->Ln(5);
}

function AjouterLangues(&$pdf,$langue,$niveau)
{
    $this->CorpsLangues(&$pdf,$langue,$niveau);
}

function PetitSautDeLigne(&$pdf)
{
    $pdf->Ln(5);
}

function Informatique($pdf,$liste)
{
    // Arial 11 Gras
    $pdf->SetFont('Arial','BI',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Informatique
	$pdf->Cell(30,0,"Langages");
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Liste
	/*$wun=$pdf->GetStringWidth($liste[0]);
	$pdf->Cell($wun,0,"$liste[0]");*/
	$chaine="$liste[0]";
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

function Logiciels($pdf,$liste)
{
    // Arial 11 Gras
    $pdf->SetFont('Arial','BI',11);
	// Couleur noire
	$pdf->SetTextColor(0,0,0);
	// Informatique
	$pdf->Cell(30,0,"Logiciels");
    // Arial 11 
	$pdf->SetFont('Arial','',11);
	// Liste
	/*$wun=$pdf->GetStringWidth($liste[0]);
	$pdf->Cell($wun,0,"$liste[0]");*/
	$chaine="$liste[0]";
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

function SautDeLigne(&$pdf)
{
    $pdf->Ln(7.5);
}

}
?>