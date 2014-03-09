<?php

require('fpdf.php');
require('entete.php');
//require_once("../include/membersite_config.php");
//
//$user_rec = array();
//
//if(!$fgmembersite->CheckLogin()) //le CheckLogin() effectue un "session_start()"
//{
//    $fgmembersite->RedirectToURL("../login.php");
//    exit;
//}
//
//$email = $fgmembersite->UserEmail();
//$type = $fgmembersite->UserType();
//$fgmembersite->GetUserFromEmail($email,$type,$user_rec);

class rubrique 
{

//function TitreRubrique($libelle)
//{
//	// Décalage à gauche
//    $this->Cell(-8);
//	// Arial Gras Souligné 16
//    $this->SetFont('Arial','BU',16);
//	// Couleur bleue
//	$this->SetTextColor(79,129,189);
//    // Petit carré bleu
//	$this->Image('CarreBleu.png');
//	// Décalage à droite
//    $this->Cell(3);
//    // Titre
//    $this->Cell(0,-3,"$libelle",0,1,'L',false);
//    // Saut de ligne
//    $this->Ln(10);
//}
//
//function SousTitreRubrique($titre)
//{
//}
//	// Saut de ligne
//    $this->Ln(2);
//	// Décalage à gauche
//    $this->Cell(-9.5);
//	// Arial Gras Italique 11
//    $this->SetFont('Arial','BI',11);
//	// Couleur bleue
//	$this->SetTextColor(79,129,189);
//    // Sous Titre
//    $this->Cell(-3,0,"Debut-Fin",0,1,'L',false);
//    // Saut de ligne
//    $this->Ln(5);

//function CorpsRubrique($fichier)
//{
////	if ($titre == 'Formation') { $this = Formation::CorpFormation() ; }
////	if ($titre == 'ExperiencesProfessionnelles') { $this = ExperiencesProfessionnelles::CorpsExperiencesProfessionnelles(); }
////	if ($titre == 'Langues') { $this = Langues::CorpsLangues() ; }
////	if ($titre == 'CentresdInteret') { $this = CentresdInteret::CorpsCentresdInteret() ; }
//
//    // Lecture du fichier texte
//    $txt = file_get_contents($fichier);
//    // Arial 11
//    $this->SetFont('Arial','',11);
//	// Couleur noire
//	$this->SetTextColor(0,0,0);
//    // Sortie du texte justifié
//    $this->MultiCell(0,5,$txt);
//    // Saut de ligne
//    $this->Ln(15);
//}

}

//$pdf = new rubrique();
//$pdf->AddPage();
//$pdf->SetLeftMargin(20);
//$pdf->AjouterRubrique('Formation','CorpsFormation.txt');
//$pdf->AjouterRubrique('Experiences Professionnelles','CorpsExperiencesProfessionnelles.txt');
//$pdf->AjouterRubrique('Langues','CorpsLangues.txt');
//$pdf->AjouterRubrique('Centres d interet','CorpsCentresdInteret.txt');
//$pdf->Output();
?>