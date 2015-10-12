<?php
define(Environment::getDefault('INCLUDES_PATH').'pdf'.DIRECTORY_SEPARATOR.'font/');
include(Environment::getDefault('INCLUDES_PATH').'pdf'.DIRECTORY_SEPARATOR.'fpdf.php');

class PDF extends FPDF 
{
	private $sBienNumMandat = 0,
			$sTypeFiche = null;
	public function setTypeFiche($sTypeFiche) {
		$this->sTypeFiche=$sTypeFiche;
	}
	
	public function getTypeFiche() {
		return $this->sTypeFiche;
	}
	
	public function setBienNumMandat ($sBienNumMandat) {
			$this->sBienNumMandat=$sBienNumMandat;
	}
	public function getBienNumMandat (){
		return $this->sBienNumMandat;
	}
		
	public function Header() {
		switch($this->sTypeFiche){
			case 'Vitrine':
				$this->SetTitle('FicheVitrine_'.$this->sBienNumMandat.'');
				$this->Image(BASE_PATH.'images/pdf/vitrine/bg.png', 0, 0,595);
			break;
		}
		//$this->Image(BASE_PATH.'images/pdf/vitrine/logo.png', 10, 5,100);

	}
	
	
	public function Footer(){
		//$this->Image(BASE_PATH.'images/pdf/vitrine/footer.png', 0, 247,210,50,'PNG');
					/*FOOTER*/
			switch($this->sTypeFiche){
			case 'Vitrine':
				$header=array('  *Frais d\'agence inclus ','',utf8_decode('Prix net hors frais notariés, d\'enregistrement et de publicité foncière  '));
				$this->SetXY(0,784);
				$this->SetFont('Arial','',10);
				$this->SetFillColor(222);
				$this->ImprovedTable($header);
			break;
		}
	}
	
	private function ImprovedTable($header)
	{
		//Largeurs des colonnes
		$w=array(200,195,200);
		$a=array('L','C','R');
		//En-tête
		for($i=0;$i<count($header);$i++)
			$this->Cell($w[$i],34,$header[$i],0,0,$a[$i]);
	}	
}

?>