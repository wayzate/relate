<?

require('../fpdf.php');

//Génération du PDF
$pdf = new FPDF();
$pdf->SetTopMargin(15);
$pdf->SetDrawColor(79,129,189);
$pdf->AddPage();
$pdf->SetLeftMargin(35);
$pdf->SetRightMargin(9.5);

$pdf->SetAuthor('Offre',true);


//Fin
$pdf->Output();

?>