<?php
require('fpdf.php');

class PDF extends FPDF
{

function TitreRubrique($libelle)
{
    // Arial 12
    $this->SetFont('Arial','B',16);
    // Titre
    $this->Cell(0,6,"$libelle",0,1,'L',true);
    // Saut de ligne
    $this->Ln(4);
}

function CorpsRubrique($fichier)
{
    // Lecture du fichier texte
    $txt = file_get_contents($fichier);
    // Arial 11
    $this->SetFont('Arial','',11);
    // Sortie du texte justifié
    $this->MultiCell(0,5,$txt);
    // Saut de ligne
    $this->Ln();
 
}

function AjouterRubrique($titre, $fichier)
{
    $this->AddPage();
    $this->TitreRubrique($titre);
    $this->CorpsRubrique($fichier);
}
}

$pdf = new PDF();
$titre = 'Formation';
$pdf->SetTitle($titre);
$pdf->AjouterRubrique('Formation','CorpsFormation.txt');
$pdf->AjouterChapitre('Experiences professionnelles','CorpsExperiencesProfessionnelles.txt');
$pdf->Output();
?>