<?php
class outil_Photo {
	const   PATH_TO_PHOTOS = "front/web/import/annonces/";
	const	EXPORT_PATH = "front/web/images/annonces/";
	
	private $sXmlNameOfFile = null;
	private $sOrigName = null;
	private $sCoeurName = null;
	private $sMediumName = null;
	private $sThumbName = null;

   
	public function __construct ($sXmlNameOfFile) {
        $this->sXmlNameOfFile = $sXmlNameOfFile;
    }
	
	public function getName() {
		return $this->sXmlNameOfFile;
	}
	
	public function createOrigFile() {
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file(self::EXPORT_PATH."orig/".$this->sXmlNameOfFile)){
				return true;
			}else{
				// Calcul des nouvelles dimensions
				list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
				$n_largeur = $largeur;
				$ratio = $n_largeur/$largeur;
				$n_hauteur = $hauteur * $ratio;
				//création de la destination
				$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
				
				//on ouvre la source
				$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
				
				// Redimensionnement
				if(imagecopy($destination, $source, 0, 0, 0, 0, $largeur, $hauteur)) {
					if(imagejpeg($destination,self::EXPORT_PATH."orig/".$this->sXmlNameOfFile)){
					imagedestroy($destination);
					imagedestroy($source);
					return true;
				}else{
					imagedestroy($destination);
					imagedestroy($source);
					return false;
				}
				}else{
					return false;
				}
			}
			
			
		}else{
			return false;
		}
	}
	
/*	public function createCoeurFile ($sFileName) {
			
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file(self::EXPORT_PATH."coeur/".$sFileName.".jpg"))
			unlink(self::EXPORT_PATH."coeur/".$sFileName.".jpg");
			// Calcul des nouvelles dimensions
			list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
			$n_largeur = 712;
			$ratio = $n_largeur/$largeur;
			$n_hauteur = $hauteur * $ratio;
			
			//création de la destination
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			
			//on ouvre la source
			$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
			
			// Redimensionnement
			if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
				if(imagejpeg($destination,self::EXPORT_PATH."coeur/".$sFileName.".jpg")){
					imagedestroy($destination);
					imagedestroy($source);
					return true;
				}else{
					imagedestroy($destination);
					imagedestroy($source);
					return false;
				}
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
		
	}
	
	public function createMediumFile($sFileName) {
			
			if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file(self::EXPORT_PATH."medium/".$sFileName.".jpg"))
			unlink(self::EXPORT_PATH."medium/".$sFileName.".jpg");
			// Calcul des nouvelles dimensions
			list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
			$n_largeur = 480;
			$ratio = $n_largeur/$largeur;
			$n_hauteur = $hauteur * $ratio;
			
			//création de la destination
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			
			//on ouvre la source
			$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
			
			// Redimensionnement
			if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
				if(imagejpeg($destination,self::EXPORT_PATH."medium/".$sFileName.".jpg")){
					imagedestroy($destination);
					imagedestroy($source);
					return true;
				}else{
					imagedestroy($destination);
					imagedestroy($source);
					return false;
				}
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
	}*/
	
	public function  createThumbFile($sFileName) {
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file(self::EXPORT_PATH."thumb/".$sFileName.".jpg"))
			unlink(self::EXPORT_PATH."thumb/".$sFileName.".jpg");
				$iSizeW = 180;
				$iSizeH = 120;
				list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
				$iWidthByRatio = 0;
				$iHeightByRatio = 0;
				$r1 = $iSizeW/$largeur;
				$r2 = $iSizeH/$hauteur;
				$r=min(array($r1,$r2));
				$iWidthByRatio = $largeur * $r;
				$iHeightByRatio = $hauteur * $r;
				
				if($r1<$r2){
					$iPosX = 0;
					$iPosY = 60-90*($hauteur/$largeur);
				}else{
					$iPosX = 90-60*($largeur/$hauteur);
					$iPosY = 0;
				}
					
				// Création du support
				$destination = imagecreatetruecolor($iSizeW, $iSizeH);
				// Créer un fond 
				$bga = imagecolorallocate($destination, 0, 0, 0);
				//Affecter le fond au support
				imagefilledrectangle($destination, 0, 0, $iSizeW, $iSizeH, $bga);
				//Charger l'image d'origine
				$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
				//Créer la nouvelle image
				//imagecopyresampled($destination, $source, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $largeur, $hauteur);

				// Redimensionnement
				if(imagecopyresampled($destination, $source, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $largeur, $hauteur)) {
					if(imagejpeg($destination,self::EXPORT_PATH."thumb/".$sFileName.".jpg")){
						imagedestroy($destination);
						imagedestroy($source);
						return true;
					}else{
						imagedestroy($destination);
						imagedestroy($source);
						return false;
					}
				}else{
					return false;
				}
			
		}else{
			return false;
		}
	}
	
	public function  createMediumFile($sFileName) {
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file(self::EXPORT_PATH."medium/".$sFileName.".jpg"))
			unlink(self::EXPORT_PATH."medium/".$sFileName.".jpg");
				$iSizeW = 480;
				$iSizeH = 320;
				// Calcul des nouvelles dimensions
				list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
				$iWidthByRatio = 0;
				$iHeightByRatio = 0;
				$r1 = $iSizeW/$largeur;
				$r2 = $iSizeH/$hauteur;
				$r=min(array($r1,$r2));
				$iWidthByRatio = $largeur * $r;
				$iHeightByRatio = $hauteur * $r;
				
				if($r1<$r2){
					$iPosX = 0;
					$iPosY = 160-240*($hauteur/$largeur);
				}else{
					$iPosX = 240-160*($largeur/$hauteur);
					$iPosY = 0;
				}
					
				// Création du support
				$destination = imagecreatetruecolor($iSizeW, $iSizeH);
				// Créer un fond 
				$bga = imagecolorallocate($destination, 0, 0, 0);
				//Affecter le fond au support
				imagefilledrectangle($destination, 0, 0, $iSizeW, $iSizeH, $bga);
				//Charger l'image d'origine
				$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
				//Créer la nouvelle image
				//imagecopyresampled($destination, $source, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $largeur, $hauteur);

				// Redimensionnement
				if(imagecopyresampled($destination, $source, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $largeur, $hauteur)) {
				if(imagejpeg($destination,self::EXPORT_PATH."medium/".$sFileName.".jpg")){
					imagedestroy($destination);
					imagedestroy($source);
					return true;
				}else{
					imagedestroy($destination);
					imagedestroy($source);
					return false;
				}
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
	}
	
}
?>