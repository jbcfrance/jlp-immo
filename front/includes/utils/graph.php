<?php
class Graph {
	const 	GRAPH_PATH 	= 	'web/images/dpe/',
			GRAPH_PREFIX_DPE = '',
			GRAPH_PREFIX_GES = '',
			POS_TXT_W 		=	257;
			
	/*
	Bien normal: H :300 W: 325
	
	
	*/
	public $aPalier = array(
		"GES" => array(
			"A"=>array("min"=>"0","max"=>"5"),
			"B"=>array("min"=>"6","max"=>"10"),
			"C"=>array("min"=>"11","max"=>"20"),
			"D"=>array("min"=>"21","max"=>"35"),
			"E"=>array("min"=>"36","max"=>"55"),
			"F"=>array("min"=>"56","max"=>"80"),
			"G"=>array("min"=>"81","max"=>"1000")),
		"dpe" => array(
			"A"=>array("min"=>"0","max"=>"50"),
			"B"=>array("min"=>"51","max"=>"90"),
			"C"=>array("min"=>"91","max"=>"150"),
			"D"=>array("min"=>"151","max"=>"230"),
			"E"=>array("min"=>"231","max"=>"330"),
			"F"=>array("min"=>"331","max"=>"450"),
			"G"=>array("min"=>"451","max"=>"10000")),
		"DPE" => array(
			"A"=>array("min"=>"0","max"=>"50"),
			"B"=>array("min"=>"51","max"=>"90"),
			"C"=>array("min"=>"91","max"=>"150"),
			"D"=>array("min"=>"151","max"=>"230"),
			"E"=>array("min"=>"231","max"=>"330"),
			"F"=>array("min"=>"331","max"=>"450"),
			"G"=>array("min"=>"451","max"=>"10000")),
		"DPEb" => array(
			"A"=>array("min"=>"0","max"=>"50"),
			"B"=>array("min"=>"51","max"=>"90"),
			"C"=>array("min"=>"91","max"=>"150"),
			"D"=>array("min"=>"151","max"=>"230"),
			"E"=>array("min"=>"231","max"=>"330"),
			"F"=>array("min"=>"331","max"=>"450"),
			"G"=>array("min"=>"451","max"=>"10000")),
		"mini_GES" => array(
			"A"=>array("min"=>"0","max"=>"5"),
			"B"=>array("min"=>"6","max"=>"10"),
			"C"=>array("min"=>"11","max"=>"20"),
			"D"=>array("min"=>"21","max"=>"35"),
			"E"=>array("min"=>"36","max"=>"55"),
			"F"=>array("min"=>"56","max"=>"80"),
			"G"=>array("min"=>"81","max"=>"1000")),
		"mini_DPE" => array(
			"A"=>array("min"=>"0","max"=>"50"),
			"B"=>array("min"=>"51","max"=>"90"),
			"C"=>array("min"=>"91","max"=>"150"),
			"D"=>array("min"=>"151","max"=>"230"),
			"E"=>array("min"=>"231","max"=>"330"),
			"F"=>array("min"=>"331","max"=>"450"),
			"G"=>array("min"=>"451","max"=>"10000")));
	public $aPos = array(
		"W" => 257,
		"H" => array(
			"A"=>26,
			"B"=>52,
			"C"=>80,
			"D"=>112,
			"E"=>143,
			"F"=>173,
			"G"=>203));
	
	public 	$sTypeGraph 	= 	'',
			$sData 			=	'',
			$sLetter		=	''; 
	
	public function __construct($sTypeGraph,$sData){
		$this->setTypeGraph($sTypeGraph);
		$this->setData($sData);
		$this->setLetter();
	}
	
	public function setTypeGraph($sTypeGraph){
		$this->sTypeGraph = $sTypeGraph;
	}
	
	public function getTypeGraph(){
		return $this->sTypeGraph;
	}
	
	public function setData($sData){
		$this->sData = $sData;
	}
	
	public function getData(){
		return $this->sData;
	}
	
	public function setLetter(){
		$sTempPos = 'I';
		foreach($this->aPalier[$this->getTypeGraph()] as $k=>$v){
			if($this->getData()>=$v['min'] && $this->getData()<=$v['max']){
				$sTempPos = $k;
				continue;
			}
		}
		$this->sLetter = $sTempPos;
	}
	
	public function getLetter(){
		return $this->sLetter;
	}
	
	public function getImgFile(){
		echo $this->aPos['W'];
		echo $this->aPos['H'][$this->getLetter()];
		
	}
	
	public function traceImg(){
		header('Content-type: image/png');
		if($this->getTypeGraph() == 'mini_DPE' || $this->getTypeGraph() == 'mini_GES'){
			$im = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
			imagealphablending($im, false);
			imagesavealpha($im, false);
			return imagepng($im);
			imagedestroy($im);
		}elseif($this->getTypeGraph() == 'DPE_GES'){
			$aData = explode('_',$this->getData());
			$this->setTypeGraph('DPE');
			$this->setData($aData[0]);
			$this->setLetter();
			$imgDpe = $this->LoadPNG(self::GRAPH_PATH.'graph_dpeges_'.strtolower($this->getLetter()).'.png');
			imagealphablending($imgDpe, false);
			imagesavealpha($imgDpe, true);
			$this->setTypeGraph('GES');
			$this->setData($aData[1]);
			$this->setLetter();
			$imgGes = $this->LoadPNG(self::GRAPH_PATH.'graph_ges_'.strtolower($this->getLetter()).'.png');
			imagealphablending($imgGes, false);
			imagesavealpha($imgGes, true);
			imagecopy($imgDpe,$imgGes,211,0,0,0,211,169);
			return imagepng($imgDpe);
			imagedestroy($imgDpe);
			imagedestroy($imgGes);
		}elseif($this->getTypeGraph() == 'DPEb'){
			$im = $this->LoadPNG(self::GRAPH_PATH.'graph_dpeb_'.strtolower($this->getLetter()).'.png');
			return imagepng($im);
			imagedestroy($im);
		}else{
		$im = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
		imagealphablending($im, false);
		imagesavealpha($im, true);
		$white = imagecolorallocate($im, 0, 0, 0);
		imagestring($im, 5, $this->aPos['W'], $this->aPos['H'][$this->getLetter()], $this->sData, $white);
		return imagepng($im);
		imagedestroy($im);
		}
	}
	
	public function __toString(){
		header('Content-type: image/png');
		if($this->getTypeGraph() == 'mini_DPE' || $this->getTypeGraph() == 'mini_GES'){
			$im = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
			imagealphablending($im, false);
			imagesavealpha($im, false);
			return imagepng($im);
			imagedestroy($im);
		}elseif($this->getTypeGraph() == 'DPE_GES'){
			$aData = explode('_',$this->getData());
			$this->setTypeGraph('DPE');
			$this->setData($aData[0]);
			
			$imgDpe = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
			imagealphablending($imgDpe, false);
			imagesavealpha($imgDpe, true);
			$this->setTypeGraph('DPE');
			$this->setData($aData[1]);
			$imgGes = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
			imagealphablending($imgGes, false);
			imagesavealpha($imgGes, true);
			//Creer le fond
			$imgBg  = imagecreatetruecolor(430, 200);
			$bgc = imagecolorallocate($imgBg, 0, 0, 0);
			
			imagefilledrectangle($imgBg, 0, 0, 430, 200, $bgc);
			//caller le DPE
			imagecopymerge($imgBg,$imgDpe,0,0,0,0,211,169);
			
			//Caller le GES
			
			return imagepng($im);
			imagedestroy($imgBg);
			imagedestroy($imgDpe);
			imagedestroy($imgGes);
		}elseif($this->getTypeGraph() == 'DPEb'){
			$im = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
			imagealphablending($im, false);
			imagesavealpha($im, true);
			$white = imagecolorallocate($im, 0, 0, 0);
			imagestring($im, 5, $this->aPos['W'], $this->aPos['H'][$this->getLetter()], $this->sData, $white);
			return imagepng($im);
			imagedestroy($im);
		}else{
		$im = $this->LoadPNG(self::GRAPH_PATH.'graph_'.strtolower($this->getTypeGraph()).'_'.strtolower($this->getLetter()).'.png');
		imagealphablending($im, false);
		imagesavealpha($im, true);
		$white = imagecolorallocate($im, 0, 0, 0);
		imagestring($im, 5, $this->aPos['W'], $this->aPos['H'][$this->getLetter()], $this->sData, $white);
		return imagepng($im);
		imagedestroy($im);
		}
	}
	public function LoadPNG($imgname)
	{
		/* Tente d'ouvrir l'image */
		$im = @imagecreatefrompng($imgname);
	
		/* Traitement en cas d'échec */
		if(!$im)
		{
			/* Création d'une image vide */
			$im  = imagecreatetruecolor(150, 30);
			$bgc = imagecolorallocate($im, 255, 255, 255);
			$tc  = imagecolorallocate($im, 0, 0, 0);
	
			imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
	
			/* On y affiche un message d'erreur */
			imagestring($im, 1, 5, 5, 'Erreur de chargement ' . $imgname, $tc);
		}
	
		return $im;
	}
}


?>