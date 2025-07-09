<?php
require_once app_path('Lib/fpdf186/fpdf.php');
define('FPDF_FONTPATH', app_path('Lib/fpdf186/font'));

function conversion($str){
    $str = mb_convert_encoding($str, 'windows-1252', 'UTF-8');
    return $str;
}

$pdf = new FPDF();
$pdf->AddPage();

$logoPath = public_path('img/rimberio.png');
$pdf->Image($logoPath, 10, 10, 30);
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 20, conversion('Bon de Commande'), 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);
$pdf->Cell(0, 10, conversion('Informations du Client'), 0, 1, 'L');
$pdf->Cell(0, 10, conversion('Nom: ') . conversion($vente['nom_clients']), 0, 1, 'L');
$pdf->Cell(0, 10, conversion('Adresse Email: ') . conversion($vente['email_clients']), 0, 1, 'L');

$pdf->Line(10, $pdf->GetY(), 100, $pdf->GetY());

$pdf->Ln(10);
$pdf->Cell(0, 10, conversion('Détails de la Commande'), 0, 1, 'L');
$pdf->Cell(0, 10, conversion('Produit: ') . conversion($vente['nom_gamme']), 0, 1, 'L');
$pdf->Cell(0, 10, conversion('Quantité: ') . conversion($vente['quantite']), 0, 1, 'L');
$pdf->Cell(0, 10, conversion('Prix Total: ') . conversion($vente['montant']) , 0, 1, 'L');
$pdf->Cell(0, 10, conversion('Date de vente: ') . conversion($vente['vente']) , 0, 1, 'L');

$pdf->Ln(20);
$pdf->Cell(0, 10, conversion('Signature:'), 0, 1, 'L');
$pdf->Line(10, $pdf->GetY(), 100, $pdf->GetY());

$pdf->Output();
exit();
?>
