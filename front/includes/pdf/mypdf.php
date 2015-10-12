<?php
include(INCLUDE_PATH.DIRECTORY_SEPARATOR.'pdf'.DIRECTORY_SEPARATOR.'pdf_alpha.php');

class MYPDF extends PDF_ImageAlpha
{
   public function Footer()
	{
		$this->SetFont('Arial','',8);
		$this->SetY(-20);
		$this->MultiCell(0,5,utf8_decode("66 Rue de la poste - Le Fayet - 74170 Saint-Gervais-Les-Bains "),0,'R',false);
		$this->SetY(-15);
		$this->SetFont('Arial','B',8);
		$this->Cell(0,5,utf8_decode("Tél. 04-50-78-27-44 - www.jlp-immo.com"),0,0,'R',false);
		$this->SetY(-10);
		$this->SetFont('Arial','',8);
		$this->MultiCell(0,5,utf8_decode("* Frais d'agence inclus"),0,'R',false);
		$this->Image("web/images/pdf/Logo-paru-vendu.jpg",10,270,0,0,'JPEG');
	}
        
}
?>
