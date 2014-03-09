<?php
require('../../estellebis/tests/fpdf.php');
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Text(10,20,'Mon CV en PDF');
$pdf->Output();
?>
