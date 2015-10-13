<?php
class outil_Photo {
	const   PATH_TO_PHOTOS = "web/import/annonces/";
	const	EXPORT_PATH = "web/images/annonces/";
	
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
	
	public function createCoeurFile ($sFileName) {
			
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
	}
	
	public function  createThumbFile($sFileName) {
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file(self::EXPORT_PATH."thumb/".$sFileName.".jpg"))
			unlink(self::EXPORT_PATH."thumb/".$sFileName.".jpg");
				// Calcul des nouvelles dimensions
				list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
/*				$n_largeur = 175;
				$ratio = $n_largeur/$largeur;
				$n_hauteur = $hauteur * $ratio;*/
				$flash_largeur = 210;
				$flash_hauteur = 120;
				$new_largeur = $flash_largeur;
				$ratio = $new_largeur/$largeur;
				$new_hauteur = $hauteur * $ratio;
				if($new_hauteur>$flash_hauteur) {
					$new_hauteur = $flash_hauteur;
					$ratio = $new_hauteur/$hauteur;
					$new_largeur = $largeur * $ratio;
					$n_largeur = $new_largeur;
					$n_hauteur = $new_hauteur;
				}else{
					$n_largeur = $new_largeur;
					$n_hauteur = $new_hauteur;
				}
				
				//création de la destination
				$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
				
				//on ouvre la source
				$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
				
				// Redimensionnement
				if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
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
	
	public function  createFlashFile($sFileName) {
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			if(is_file("web/images/flash/".$sFileName.".jpg"))
			unlink("web/images/flash/".$sFileName.".jpg");
			// Calcul des nouvelles dimensions
			list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
			$flash_largeur = 280;
			$flash_hauteur = 187;
			$new_largeur = $flash_largeur;
			$ratio = $new_largeur/$largeur;
			$new_hauteur = $hauteur * $ratio;
			if($new_hauteur>$flash_hauteur) {
				$new_hauteur = $flash_hauteur;
				$ratio = $new_hauteur/$hauteur;
				$new_largeur = $largeur * $ratio;
				$n_largeur = $new_largeur;
				$n_hauteur = $new_hauteur;
			}else{
				$n_largeur = $new_largeur;
				$n_hauteur = $new_hauteur;
			}
			
			//création de la destination
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			
			//on ouvre la source
			$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
			
			// Redimensionnement
			if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
				if(imagejpeg($destination,"web/images/flash/".$sFileName.".jpg")){
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