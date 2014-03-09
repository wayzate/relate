<?php

require_once('fpdf.php');
require_once("../include/membersite_config.php");

class ENTETE
{

	function Photo(&$pdf,$id)
	{
		// Position du cadre
		$pdf-> SetXY(149.66,7.66);
		// Photo
		$pdf->Image('CadrePhoto.png');
		// Position
		$pdf-> SetXY(151,9);
		// Photo
		$pdf->Image("../membres/pics/$id/thumbnail/thumb_pic.jpg");

	}

	function Head(&$pdf,$Prenom,$Nom)
	{
		// Position
		$pdf-> SetXY(35,15);
		// Décalage à gauche
		$pdf->Cell(-8);
		// Arial 15
		$pdf->SetFont('Arial','',16);
		// Titre
		$pdf->Cell(0,0,"$Prenom $Nom");
		// Saut de ligne
		$pdf->Ln(10);
	}


	function CorrigerAnnee($annee)
	{
		if ($annee>2000) {
			return($annee - 2000);
		}
		else {return($annee - 1900);
		}
	}

	function Naissance(&$pdf,$genre,$valjour,$valmois,$annee)
	{
		// Afficher correctement les années
		$valannee = $this->CorrigerAnnee($annee) ;

		// Arial 11
		$pdf->SetFont('Arial','',11);
		if ($genre == 'H')
		{
			// Texte
			$pdf->Cell(0,0,"Né le $valjour/$valmois/$valannee",0,1);
		}
		if ($genre == 'F')
		{
			// Texte
			$pdf->Cell(0,0,"Née le $valjour/$valmois/$valannee",0,1);
		}
		// Saut de ligne
		$pdf->Ln(5);

	}

	function Nationalite(&$pdf,$natio)
	{
		// Arial 11
		$pdf->SetFont('Arial','',11);
		// Texte
		$pdf->Cell(0,0,"Nationalité $natio",0,1);
		// Saut de ligne
		$pdf->Ln(5);
	}

	function Lieu(&$pdf,$ville)
	{
		// Arial 11
		$pdf->SetFont('Arial','',11);
		// Texte
		$pdf->Cell(0,0,"$ville",0,1);
		// Saut de ligne
		$pdf->Ln(5);
	}

	function Tel(&$pdf,$num)
	{
		// Remontée
		$pdf->Ln(-2.5);
		// Décalage à gauche
		$pdf->Cell(-8);
		// Image
		$pdf->Image('logo_telephone.png');
		// Décalage à droite
		$pdf->Cell(8);
		// Arial 11
		$pdf->SetFont('Arial','',11);
		// Texte
		$pdf->Cell(0,-4,"$num",0,1);
		// Saut de ligne
		$pdf->Ln(6.5);
	}

	function Email(&$pdf,$email)
	{
		// Remontée
		$pdf->Ln(-0.5);
		// Décalage à gauche
		$pdf->Cell(-8);
		// Image
		$pdf->Image('logo_mail.png');
	 // Décalage à droite
		$pdf->Cell(8);
	 // Arial 11
		$pdf->SetFont('Arial','',11);
		// Texte
		$pdf->Cell(0,-3,"$email",0,1);
		// Saut de ligne
		$pdf->Ln(12.5);
	}

	function AssocierDuree($duree)
	{
		if ($duree == 0) {
			return ("d'1 à 3 mois");
		}
		if ($duree == 1) {
			return('de 3 à 6 mois');
		}
		if ($duree == 2) {
			return('de 6 à 9 mois');
		}
		if ($duree == 3) {
			return('de 9 à 12 mois');
		}
		if ($duree == 4) {
			return("d'1 an ou plus");
		}
	}

	function AssocierDomaine($domaine)
	{
		if ($domaine == 0) {
			return('Audit/Conseil');
		}
		if ($domaine == 1) {
			return('Banque/Assurance');
		}
		if ($domaine == 2) {
			return('BTP/Immobilier');
		}
		if ($domaine == 3) {
			return('Distribution/Consommation');
		}
		if ($domaine == 4) {
			return("Energie/Environnement");
		}
		if ($domaine == 5) {
			return('Industrie/Chimie');
		}
		if ($domaine == 6) {
			return('IT/Telecom/Multimedia');
		}
		if ($domaine == 7) {
			return('Pharmacie/Santé');
		}
		if ($domaine == 8) {
			return('Transport/Service');
		}
	}

	function AssocierMois($mois)
	{
		if ($mois == 1) {
			return('de janvier');
		}
		if ($mois == 2) {
			return('de février');
		}
		if ($mois == 3) {
			return('de mars');
		}
		if ($mois == 4) {
			return("d'avril");
		}
		if ($mois == 5) {
			return('de mai');
		}
		if ($mois == 6) {
			return('de juin');
		}
		if ($mois == 7) {
			return('de juillet');
		}
		if ($mois == 8) {
			return("d'août");
		}
		if ($mois == 9) {
			return('de septembre');
		}
		if ($mois == 10) {
			return("d'octobre");
		}
		if ($mois == 11) {
			return('de novembre');
		}
		if ($mois == 12) {
			return('de décembre');
		}
	}

	function Titre(&$pdf,$genre,$ecole,$type,$duree,$domaine,$mois,$annee)
	{
		// Associer la durée et le domaine
		$valduree = $this->AssocierDuree($duree) ;
		$valdomaine = $this->AssocierDomaine($domaine);
		$nommois = $this->AssocierMois($mois);
		// Ligne bleue
		$ordonnee = $pdf->GetY();
		$pdf->Line(7,$ordonnee,203,$ordonnee);
		// Saut de ligne
		$pdf->Ln(5);
		// Arial 16
		$pdf->SetFont('Arial','',16);
		//// Couleur bleue
		//	$pdf->SetTextColor(79,129,189);
		// Texte
		if ((empty($genre))&&(empty($ecole))){
		}
		if ((!empty($genre))&&(empty($ecole))){
		}
		if (preg_match("#^[AEIOUY]#",$ecole)){
			$ecole = "l'".$ecole;
		}

		if ((!empty($genre))&&(!empty($ecole))){
			if ($genre == 'H')
			{
				$pdf->Cell(140,0,"Étudiant à $ecole",0,1,'C',false);
			}
			if ($genre == 'F')
			{
				// Texte
				$pdf->Cell(140,0,"Étudiante à $ecole",0,1,'C',false);
			}
		}
		if ((empty($genre))&&(!empty($ecole))){
			$pdf->Cell(140,0,"Étudiant à $ecole",0,1,'C',false);
		}

		// Saut de ligne
		$pdf->Ln(6.5);
		if ((!empty($type))&&(!empty($valduree))&&(!empty($valdomaine))){
			// Suite du Texte
			if ($type=='Alternance') {
				$pdf->Cell(140,0,"Cherche une offre en $type $valduree dans $valdomaine",0,1,'C',false);
			}
			else {
				$pdf->Cell(140,0,"Cherche un $type $valduree dans $valdomaine",0,1,'C',false);
			}
		}
		if ((!empty($type))&&(!empty($valduree))&&(empty($valdomaine))){
			// Suite du Texte
			if ($type=='Alternance') {
				$pdf->Cell(140,0,"Cherche une offre en $type $valduree",0,1,'C',false);
			}
			else {
				$pdf->Cell(140,0,"Cherche un $type $valduree",0,1,'C',false);
			}
		}
		if ((!empty($type))&&(empty($valduree))&&(!empty($valdomaine))){
			// Suite du Texte
			if ($type=='Alternance') {
				$pdf->Cell(140,0,"Cherche une offre en $type dans $valdomaine",0,1,'C',false);
			}
			else {
				$pdf->Cell(140,0,"Cherche un $type dans $valdomaine",0,1,'C',false);
			}
		}
		if ((!empty($type))&&(empty($valduree))&&(empty($valdomaine))){
			// Suite du Texte
			if ($type=='Alternance') {
				$pdf->Cell(140,0,"Cherche une offre en $type",0,1,'C',false);
			}
			else {
				$pdf->Cell(140,0,"Cherche un $type",0,1,'C',false);
			}
		}
		// Saut de ligne
		$pdf->Ln(6.5);
		// Suite du Texte
		if ((!empty($nommois))&&(!empty($annee))){
			$pdf->Cell(140,0,"Disponible à partir $nommois $annee",0,1,'C',false);
		}
		// Saut de ligne
		$pdf->Ln(5);
		// Ligne bleue
		$ordonnee = $pdf->GetY();
		$pdf->Line(7,$ordonnee,203,$ordonnee);
		// Saut de ligne
		$pdf->Ln(11);
	}
}

?>