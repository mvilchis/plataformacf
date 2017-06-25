<?php
require('/var/www/FPDF/fpdf.php');
$id = uniqid();
class PDF extends FPDF
{
// Page header
function Header()
{
  // Logo
  file_put_contents("tmp/".$id.".svg", file_get_contents("php://input"));
  exec("rsvg-convert -f png -o tmp/".$id.".png tmp/".$id.".svg");
	$this->Image("tmp/".$id.".png",10,6,30);

	// Arial bold 15
	$this->SetFont('Arial','B',15);
	// Move to the right
	$this->Cell(80);
	// Title
	$this->Cell(30,10,'Title',1,0,'C');
	// Line break
	$this->Ln(20);
}

// Page footer
function Footer()
{
	// Position at 1.5 cm from bottom
	$this->SetY(-15);
	// Arial italic 8
	$this->SetFont('Arial','I',8);
	// Page number
	$this->Cell(0,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');
}
}

// Instanciation of inherited class
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times','',12);
for($i=1;$i<=40;$i++)
	$pdf->Cell(0,10,'Printing line number '.$i,0,1);

$filename="/var/www/svg2pdf/tmp/".$id.".pdf";
$pdf->Output($filename,'F');
echo("/svg2pdf/tmp/".$id.".pdf");
?>
