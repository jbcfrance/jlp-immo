<?php
	/**
	 * Auteur			: Jean-Baptiste CIEPKA
	 * Date				: Février - Mars 2009
	 *
	 * Name				: class Images extends UtilFile
	 * Description		: Cette classe extends UtilFile afin de permetre un traitement plus précis des images du site. 
	 * @templates dir 	: templates/livres
	 *
	*/ 
class Images extends UtilFile {

	const 	UPLOAD_DIR 	= 	"web/images/programmeneufs/";
	
	private	$oImageFile		= null,		// Fichier Image
			$sTargetPath	= null,		// Repertoire de destination
			$sUploadName	= null,		// Nom de fichier pour upload
			$sWidthImg		= null,		// Width
			$iId			= null,		// ID
			$sStyleImg		= null,		// UploadName
			$sResult		= null;		// Affichage du resultat							 
	
	/**
	 *	__construct
	 */
	public function __construct (
									$oFile = null,
									$iId = '',
									$sUploadName = '',
									$sWidth = 0
								) {
		parent::__construct();
		$this->oImageFile = $oFile;
		$this->setTargetPath($iId);
		$this->sUploadName = $sUploadName;
		$this->sWidthImg = $sWidth;
	}

	/**
	 *	__destruct
	 */
	public function __destruct () {
		$this->close();
	}
	public function uploadImage() {
			if(file_exists(self::UPLOAD_DIR.$this->sTargetPath.$this->sUploadName.$this->oImageFile->getExtension())){
				unlink(self::UPLOAD_DIR.$this->sTargetPath.$this->sUploadName.$this->oImageFile->getExtension());
			}
			$this->oImageFile->copy(self::UPLOAD_DIR.$this->sTargetPath);
			list($largeur, $hauteur) = getimagesize(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName());
			/*Thumbnail*/
			$n_largeur_thumb = 100;
			$ratio_thumb = $n_largeur_thumb/$largeur;
			$n_hauteur_thumb = $hauteur * $ratio_thumb;
			$destination = imagecreatetruecolor($n_largeur_thumb, $n_hauteur_thumb);
			$source = imagecreatefromjpeg(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName());
			imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur_thumb, $n_hauteur_thumb, $largeur, $hauteur);
			imagejpeg($destination,self::UPLOAD_DIR.$this->sTargetPath."Mini_".$this->sUploadName);
			imagedestroy($destination);
			imagedestroy($source);
			/*Preview*/
			$n_largeur_pre = 400;
			$ratio_pre = $n_largeur_pre/$largeur;
			$n_hauteur_pre = $hauteur * $ratio_pre;
			$destination = imagecreatetruecolor($n_largeur_pre, $n_hauteur_pre);
			$source = imagecreatefromjpeg(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName());
			imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur_pre, $n_hauteur_pre, $largeur, $hauteur);
			imagejpeg($destination,self::UPLOAD_DIR.$this->sTargetPath."Pre_".$this->sUploadName);
			imagedestroy($destination);
			imagedestroy($source);
			/*Taille Orig*/
			if($largeur>1000) {
				$n_largeur_big = 1000;
				$ratio_big = $n_largeur_big/$largeur;
				$n_hauteur_big = $hauteur * $ratio_big;
				$destination = imagecreatetruecolor($n_largeur_big, $n_hauteur_big);
				$source = imagecreatefromjpeg(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName());
				imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur_big, $n_hauteur_big, $largeur, $hauteur);
				imagejpeg($destination,self::UPLOAD_DIR.$this->sTargetPath."Big_".$this->sUploadName);
				imagedestroy($destination);
				imagedestroy($source);
			}else{
				copy(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName(),self::UPLOAD_DIR.$this->sTargetPath."Big_".$this->sUploadName);
			}
			/*Taille normale*/
			$n_largeur = $this->sWidthImg;
			$ratio = $n_largeur/$largeur;
			$n_hauteur = $hauteur * $ratio;
			$destination = imagecreatetruecolor($n_largeur, $n_hauteur);
			$source = imagecreatefromjpeg(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName());
			imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur, $n_hauteur, $largeur, $hauteur);
			imagejpeg($destination,self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName());
			rename(self::UPLOAD_DIR.$this->sTargetPath.$this->oImageFile->getFileName(),self::UPLOAD_DIR.$this->sTargetPath.$this->sUploadName);
			imagedestroy($destination);
			imagedestroy($source);
			$this->sResult = "Mise en ligne OK ! ";
		return $this->sResult;
	}
	public function getTargetPath() {
		return $this->sTargetPath;
	}
	public function deleteImages($sId,$sImgName){
		if(file_exists(self::UPLOAD_DIR.$sId.$sImgName)){
			if(unlink(self::UPLOAD_DIR.$sId.$sImgName))
			{
				$this->sResult = "Image correctement supprimée";
			}
		}else{
			$this->sResult = "Aucun fichier à supprimer";
		}
		return $this->sResult;
	}
	
	function deleteDir($iId)
	{
		$filepath = self::UPLOAD_DIR.$iId;
		$aFiles = $this->searchdir ( $filepath , -1 ,"FILES" );
		foreach($aFiles as $k=>$v) {
			if(file_exists($v)){
				unlink($v);
			}
		}
		rmdir($filepath);
		$this->sResult = "Images supprimées";
		return $this->sResult;
	}
		
	private function setTargetPath($iId) {
				$this->iId = $iId;
				if($this->iId !='') {
					$this->sTargetPath = $iId."/";
					if(!is_dir(self::UPLOAD_DIR.$this->iId."/")){
						mkdir(self::UPLOAD_DIR.$this->iId."/");
					}
				}
		return $this->sTargetPath;
	}
		
	private function setUploadName()
	{
		$this->sUploadName = $this->sStyleImg.'.'.$this->oImageFile->getExtension(); 
		return $this->sUploadName;	
	}
	
	// $path : path to browse
	// $maxdepth : how deep to browse (-1=unlimited)
	// $mode : "FULL"|"DIRS"|"FILES"
	// $d : must not be defined
	private function searchdir ( $path , $maxdepth = -1 , $mode = "FULL" , $d = 0 )
	{
	   if ( substr ( $path , strlen ( $path ) - 1 ) != '/' ) { $path .= '/' ; }     
	   $dirlist = array () ;
	   if ( $mode != "FILES" ) { $dirlist[] = $path ; }
	   if ( $handle = opendir ( $path ) )
	   {
		   while ( false !== ( $file = readdir ( $handle ) ) )
		   {
			   if ( $file != '.' && $file != '..' )
			   {
				   $file = $path . $file ;
				   if ( ! is_dir ( $file ) ) { if ( $mode != "DIRS" ) { $dirlist[] = $file ; } }
				   elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) )
				   {
					   $result = $this->searchdir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
					   $dirlist = array_merge ( $dirlist , $result ) ;
				   }
		   }
		   }
		   closedir ( $handle ) ;
	   }
	   if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
	   return ( $dirlist ) ;
	}

}
?>