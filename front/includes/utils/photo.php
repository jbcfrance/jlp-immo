<?php
class Photo {
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
					return true;
				}else{
					return false;
				}
				imagedestroy($destination);
				imagedestroy($source);
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
	}
	
	public function createCoeurFile () {
			
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			// Calcul des nouvelles dimensions
			list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
			$n_largeur = 800;
			$ratio = $n_largeur/$largeur;
			$n_hauteur = $hauteur * $ratio;
			
			//création de la destination
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			
			//on ouvre la source
			$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
			
			// Redimensionnement
			if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
				if(imagejpeg($destination,self::EXPORT_PATH."coeur/".$this->sXmlNameOfFile)){
					return true;
				}else{
					return false;
				}
				imagedestroy($destination);
				imagedestroy($source);
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
		
	}
	
	public function createMediumFile() {
				if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			// Calcul des nouvelles dimensions
			list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
			$n_largeur = 500;
			$ratio = $n_largeur/$largeur;
			$n_hauteur = $hauteur * $ratio;
			
			//création de la destination
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			
			//on ouvre la source
			$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
			
			// Redimensionnement
			if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
				if(imagejpeg($destination,self::EXPORT_PATH."medium/".$this->sXmlNameOfFile)){
					return true;
				}else{
					return false;
				}
				imagedestroy($destination);
				imagedestroy($source);
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
	}
	
	public function  createThumbFile() {
		if(is_file(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile)){
			
			// Calcul des nouvelles dimensions
			list($largeur, $hauteur) = getimagesize(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
			$n_largeur = 150;
			$ratio = $n_largeur/$largeur;
			$n_hauteur = $hauteur * $ratio;
			
			//création de la destination
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			
			//on ouvre la source
			$source = imagecreatefromjpeg(self::PATH_TO_PHOTOS.$this->sXmlNameOfFile);
			
			// Redimensionnement
			if(imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur)) {
				if(imagejpeg($destination,self::EXPORT_PATH."thumb/".$this->sXmlNameOfFile)){
					return true;
				}else{
					return false;
				}
				imagedestroy($destination);
				imagedestroy($source);
			}else{
				return false;
			}
			
			
		}else{
			return false;
		}
	}
	
}
?>