<?php
require('fpdf.php');

class PDF extends fpdf
{
function Header()
{
    // Arial 15
    $this->SetFont('Arial','',15);
    // Titre
    $this->Text(24.25,10,"Prenom NOM");
    // Saut de ligne
    $this->Ln(10);
}


function TitreRubrique($libelle)
{
	// Arial Gras Souligné 16
    $this->SetFont('Arial','BU',16);
	// Couleur bleue
	$this->SetTextColor(79,129,189);
    // Petit carré bleu
	$this->Image('CarreBleu.png');
	// Décalage à droite
    $this->Cell(3);
    // Titre
    $this->Cell(0,-3,"$libelle",0,1,'L',false);
    // Saut de ligne
    $this->Ln(10);
}

function SousTitreRubrique()
{
	// Saut de ligne
    $this->Ln(2);
	// Arial Gras Italique 11
    $this->SetFont('Arial','BI',11);
	// Couleur bleue
	$this->SetTextColor(79,129,189);
    // Titre
    $this->Cell(-3,0,"Debut-Fin",0,1,'L',false);
    // Saut de ligne
    $this->Ln(5);
}


function CorpsRubrique($fichier)
{
    // Lecture du fichier texte
    $txt = file_get_contents($fichier);
    // Arial 11
    $this->SetFont('Arial','',11);
	// Couleur noire
	$this->SetTextColor(0,0,0);
	// Décalage à droite
    $this->Cell(10);
    // Sortie du texte justifié
    $this->MultiCell(0,5,$txt);
    // Saut de ligne
    $this->Ln(15);
}



function AjouterRubrique($titre, $fichier)
{
    $this->TitreRubrique($titre);
	$this->SousTitreRubrique();
    $this->CorpsRubrique($fichier);
}
}

$pdf = new PDF();
$pdf->SetLeftMargin(20);
$pdf->AddPage();
$pdf->AjouterRubrique('Formation','CorpsFormation.txt');
$pdf->AjouterRubrique('Experiences Professionnelles','CorpsExperiencesProfessionnelles.txt');
$pdf->AjouterRubrique('Langues','CorpsLangues.txt');
$pdf->AjouterRubrique('Centres d interet','CorpsCentresdInteret.txt');
$pdf->Output();
?>