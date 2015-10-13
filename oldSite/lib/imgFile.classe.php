<?php
class imgFile extends UtilFile{
	const 	ORIG_PATH 	= 	'images/originaux/',
			GAL_PATH	=	'images/gal/',
			MAX_WIDTH	=	'1000',
			MAX_HEIGHT	=	'900';
	
	public 	$iResWidth = null,
			$iResHeight = null,
			$fImgFile = null,
			$iImgTarWidth = null,
			$iImgTarHeight = null,
			$gdImg = null,
			$bImgNeedRedim = false,
			$aSize = array(),
			$oImgFile = null,
			$oFile = null,
			$sUploadName = null;

	public function __construct($oFile = null,$sUploadName = ''){
		parent::__construct();
		$this->oFile = $oFile;
		$this->sUploadName = $sUploadName;
		/*if (isset($_SESSION["resWidth"]) && isset($_SESSION["resHeight"])) {
			
			$this->iResWidth = $_SESSION["resWidth"];
			$this->iResHeight = $_SESSION["resHeight"];
		}
		$this->fImgFile = self::IMG_PATH.$sFileName.'.png';
		$this->gdImg = $this->LoadPNG($this->fImgFile);
		$this->setImgSize();
		$this->setSize();
		$this->showImg();*/
	}
	public function uploadOrig(){
		$this->oFile->renameAndCopy ($this->sUploadName, self::ORIG_PATH);
		$this->gdImg = $this->LoadPNG(self::ORIG_PATH.$this->sUploadName);

	}
	
	public function traitementGlobal(){
		for($i=100;$i<=1000;$i+=100){
			if($this->resizeImg($i,self::GAL_PATH.$i.'/')){
				$sRes = true;	
			}else{
				$sRes = false;
			}
		}
		return $sRes;		
	}
	public function deleteAll(){
		for($i=100;$i<=1000;$i+=100){
			if($this->deleteFile(self::GAL_PATH.$i.'/')){
				$sRes = true;	
			}else{
				$sRes = false;
			}
		}
		return $sRes;		
	}
	
	public function deleteFile($sDir){
		if(file_exists($sDir.$this->oFile->getFileName())){
			if(unlink($sDir.$this->oFile->getFileName())){
				$sRes = true;
			}else{
				$sRes = false;
			}
		}else{
			$sRes = true;	
		}
		return $sRes;
	}
	public function resizeImg($iSize = 0,$sDir = ''){
		//Calculer le ratio
		$aImgConf = getimagesize(self::ORIG_PATH.$this->oFile->getFileName());
		$iWidthByRatio = 0;
		$iHeightByRatio = 0;
		if($aImgConf[0]!=$aImgConf[1]){
			//Img non carrée
			if($aImgConf[0]>$aImgConf[1]){
				//Img Horiz
				if($aImgConf[0]!=$iSize){
					$iRatio = $aImgConf[0] / $aImgConf[1];
					$iWidthByRatio = $iSize;
					$iHeightByRatio = $iSize/$iRatio;
					//Position de l'image dans son cadre
					$iPosX = 0;
					$iPosY = ($iSize-$iHeightByRatio)/2;
				}else{
					$iWidthByRatio = $iSize;
					$iHeightByRatio = $aImgConf[1];
					$iPosX = 0;
					$iPosY = ($iSize-$iHeightByRatio)/2;
				}
			}else{
				//Img Verti
				if($aImgConf[1]!=$iSize){
					$iRatio = $aImgConf[1] / $aImgConf[0];
					$iWidthByRatio = $iSize/$iRatio;
					$iHeightByRatio = $iSize;
					$iPosX = ($iSize-$iWidthByRatio)/2;
					$iPosY = 0;
				}else{
					$iWidthByRatio = $aImgConf[0];
					$iHeightByRatio = $iSize;
					$iPosX = ($iSize-$iWidthByRatio)/2;
					$iPosY = 0;
				}
			}
		}else{
			// Img Carrée
			$iWidthByRatio = $iSize;
			$iHeightByRatio = $iSize;
		}
		/*echo $aImgConf[0]."<br>";
		echo $aImgConf[1]."<br>";
		echo $iWidthByRatio."<br>";
		echo $iHeightByRatio."<br>";*/	
		// Création du support
		$image_p = imagecreatetruecolor($iSize, $iSize);
		// Préservé l'alpha
		imagealphablending($image_p, false);
		imagesavealpha($image_p, true);
		// Créer un fond transparent
		$bga = imagecolorallocatealpha($image_p, 255, 0, 0,127);
		//Affecter le fond transparent au support
		imagefilledrectangle($image_p, 0, 0, $iSize, $iSize, $bga);
		//Charger l'image d'origine
		$image = $this->LoadPNG(self::ORIG_PATH.$this->sUploadName);
		//Créer la nouvelle image
		imagecopyresampled($image_p, $image, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $aImgConf[0], $aImgConf[1]);
		
		// Génération de l'image finale
		if(imagepng($image_p,$sDir.$this->oFile->getFileName(),0)){
			return true;	
		}else{
			return false;
		}
		imagedestroy($image);
		imagedestroy($image_p);
		//die();
	}
	
	public function setSize(){
		$this->aSize['HImg'] = 800;
		$this->aSize['WImg'] = 800;
		$this->aSize['HCol'] = 800;
		$this->aSize['WCol'] = 100;
		$this->aSize['HLeg'] = 100;
		$this->aSize['HTable'] = 900;
		$this->aSize['WTable'] = 1000;
		$this->aSize['HLight'] = 900;
		$this->aSize['WLight'] = 1000;
		$this->aSize['WIframeBg'] = 996;	
	}
	
	public function getSize(){
		return $this->aSize;
	}
	
	public function setImgSize(){
		$aImgConf = getimagesize($this->fImgFile);
		$iMaxImgHeight = (floor($this->iResHeight/100)*100)-100;
		$iMaxImgWidth = (floor($this->iResWidth/100)*100)-100;
		if($this->iResHeight<$this->iResWidth){
			if($aImgConf[0]>$iMaxImgHeight || $aImgConf[1]>$iMaxImgHeight){
				$iImgMaxSize = max($aImgConf[0],$aImgConf[1]);
				$this->iImgTarWidth = floor($iImgMaxSize/100)*100;
				$this->iImgTarHeight = floor($iImgMaxSize/100)*100;
			}else{
				$this->iImgTarWidth = $iMaxImgHeight;
				$this->iImgTarHeight = $iMaxImgHeight;
			}
		}else{	
			if($aImgConf[0]>$iMaxImgWidth || $aImgConf[1]>$iMaxImgWidth){
				$iImgMaxSize = max($aImgConf[0],$aImgConf[1]);
				$this->iImgTarWidth = floor($iImgMaxSize/100)*100;
				$this->iImgTarHeight = floor($iImgMaxSize/100)*100;
			}else{
				$this->iImgTarWidth = $iMaxImgWidth;
				$this->iImgTarHeight = $iMaxImgWidth;
			}
		}
		/*if(!$this->bImgNeedRedim){
			$iImgMaxSize = max($aImgConf[0],$aImgConf[1]);
			$this->iImgTarWidth = floor($iImgMaxSize/100)*100;
			$this->iImgTarHeight = floor($iImgMaxSize/100)*100;
		}else{
			if($this->iResHeight<$this->iResWidth){
				$this->iImgTarWidth = $iMaxImgHeight;
				$this->iImgTarHeight = $iMaxImgHeight;
			}else{
				$this->iImgTarWidth = $iMaxImgWidth;
				$this->iImgTarHeight = $iMaxImgWidth;
			}
		}*/
		/*echo " ImgTarWidth ".$this->iImgTarWidth;
		echo "<br>";
		echo " ImgTarHeight ".$this->iImgTarHeight;
		echo "<br>";*/
		//echo"<pre>";print_r($aImgConf);echo"</pre>";
		/*echo $aImgConf[0];
		if($aImgConf[0]<=$this->iResWidth){
			$this->bRedimWidth = 0;
		}else{
			$this->bRedimWidth = 1;
		}
		echo $aImgConf[1];
		if($aImgConf[1]<=$this->iResHeight){
			$this->bRedimHeight = 0;
		}else{
			$this->bRedimHeight = 1;
		}*/
	}
	
	public function showImg(){
		header('Content-type: image/png');
		// Aquisition de la taille de l'image
		$aImgConf = getimagesize($this->fImgFile);
		// Création du support
		$image_p = imagecreatetruecolor($this->iImgTarWidth, $this->iImgTarHeight);
		// Préservé l'alpha
		imagealphablending($image_p, false);
		imagesavealpha($image_p, true);
		// Créer un fond transparent
		$bga = imagecolorallocatealpha($image_p, 255, 0, 0,127);
		//Affecter le fond transparent au support
		imagefilledrectangle($image_p, 0, 0, $this->iImgTarWidth, $this->iImgTarHeight, $bga);
		//Charger l'image d'origine
		$image = $this->LoadPNG($this->fImgFile);
		
		// Redimensionnement au besoin
		if($aImgConf[0]!=$aImgConf[1]){
			//Img non carrée
			if($aImgConf[0]>$aImgConf[1]){
				//Img Horiz
				if($aImgConf[0]>$this->iImgTarWidth){
					//Img a redim
					//Calculer le ratio
					//Effectuer la redim
				}else{
					//Img taille ok
				}
			}else{
				//Img Verti
				if($aImgConf[1]>$this->iImgTarHeight){
					//Img a redim
					//Calculer le ratio
					//Effectuer la redim
				}else{
					//Img taille ok
				}
			}
		}else{
			// Img Carrée
			if($aImgConf[0]>$this->iImgTarWidth || $aImgConf[1]>$this->iImgTarHeight){
				//Img a redim
				//Calculer le ratio
				//Effectuer la redim
			}else{
				//Img taille ok
			}
		}
		
		
		
		if($aImgConf[0]!=$aImgConf[1] && $aImgConf[0]!=$this->iImgTarWidth || $aImgConf[1]!=$this->iImgTarHeight){
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, 800, 534, 1000, 667);
		}else{
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $this->iImgTarWidth, $this->iImgTarHeight, $aImgConf[0], $aImgConf[1]);
		}
		// Génération de l'image finale
		return imagepng($image_p);
		imagedestroy($image);
		imagedestroy($image_p);
		
	}
	
	public function LoadPNG($imgname)
	{
		/* Tente d'ouvrir l'image */
		$im = @imagecreatefrompng($imgname);
		imagealphablending($im, true);
		imagesavealpha($im, true);
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