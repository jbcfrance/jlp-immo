<?php
class prog_neufPage extends Page {
	
	const UPLOAD_DIR = "front/web/images/programmeneufs/";
	
	public function traitementPage() {
		if(isset($_POST) and !empty($_POST)){
			$aPost = array();
			foreach($_POST as $kPost=>$vPost){
				$aPost[$kPost] = $vPost;	
			}
		}
		
		$this->aVars[$this->aVars['sAction']] = array();
		
		if(isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == 1){
			if(method_exists($this, $this->aVars['sAction'])){
				$this->{$this->aVars['sAction']}(); 
			}else{
				$this->{'prog_neuf'}();
			}
		}else{
			$this->redirect('dashboard','accueil');
		}
	}

	public function prog_neuf($sMessage = '',$sStatus = null){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/prog_neuf.htm');	
		$this->setSubTitle('Programmes neufs');
		$oProg = PROGRAMME::SELECT()->exec();
		$this->aVars[$this->sRequestPage]['programme']= $oProg;
		if(!empty($sMessage) && isset($sStatus)){
			$this->aVars[$this->sRequestPage]['sMessage'] = $sMessage;
			$this->aVars[$this->sRequestPage]['sStatus'] = $sStatus;
		}
	}
	
	public function add_prog(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/add_prog.htm');	
		$this->setSubTitle('Ajout d\'un programme neuf');
		$aFields = array('programme_id','programme_titre','programme_titre_color','programme_description_fr','programme_description_en','programme_partenaire','programme_identifiant');
		$sRes = false;
		if(isset($_POST['programme_titre'])&&isset($_POST['programme_description_fr']) &&isset($_POST['programme_description_en'])){
		$aValues = array('',$_POST['programme_titre'],'',$_POST['programme_description_fr'],$_POST['programme_description_en'],$_POST['programme_partenaire'],$_POST['programme_identifiant']);
		
		if(PROGRAMME::INSERT($aFields)->values($aValues)->exec())$sRes = true; else $this->prog_neuf('Erreur lors de la création du programme neuf : insertion en bdd','error');
		$iProgId = SQLComposer::getLastInsertId();
		if(!isset($iProgId)) die('ERREUR ID');
		$aAnnonces = explode(';',$_POST['programme_annonces']);
		if(!empty($aAnnonces)){
			
			foreach($aAnnonces as $kAnnonce=>$vAnnonce){
				$oAnnonce = ANNONCE::SELECT('annonce_id')->WHERE('reference',$vAnnonce)->getOne();
				$oQry = PROGRAMME_ANNONCE::INSERT(array('programme_id','annonce_id'))->values(array($iProgId,$oAnnonce->getAnnonce_id()))->exec();
				
			}
		}		
		foreach($GLOBALS['FILES']['images_annexes'] as $kFile=>$oFile){
			if(self::prepAndUploadImg($iProgId,$oFile,'Orig_ProgrammeNeufImage_'.$kFile.'.'.$oFile->getExtension()))
			$sRes = true;
			else
			$this->prog_neuf('Erreur lors de la création du programme neuf : upload image annexe','error');
		}
		$oFile = $GLOBALS['FILES']['img_principale'];
		if($iProgId !='') {
			$sTargetPath = $iProgId."/";
			if(!is_dir(self::UPLOAD_DIR.$iProgId."/")){
				mkdir(self::UPLOAD_DIR.$iProgId."/");
			}
		}
		$sUploadName = 'Bloc_ProgrammeNeufImage'.'.jpg';
		$oFile->copy(self::UPLOAD_DIR.$sTargetPath);
		list($largeur, $hauteur) = getimagesize(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());		
		$destination = imagecreatetruecolor(300, 300);
		$source = imagecreatefromjpeg(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
		imagecopyresampled($destination, $source, 0, 0, 0, 0, 300, 300, $largeur, $hauteur);
		if(imagejpeg($destination,self::UPLOAD_DIR.$sTargetPath.$sUploadName)) $sRes = true; else $this->prog_neuf('Erreur lors de la création du programme neuf : génération du bloc','error');
		imagedestroy($destination);
		imagedestroy($source);
		unlink(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
		}
		if(isset($GLOBALS['FILES']['img_bg'])){
			$oFile = $GLOBALS['FILES']['img_bg'];
			if($iProgId !='') {
				$sTargetPath = $iProgId."/";
				if(!is_dir(self::UPLOAD_DIR.$iProgId."/")){
					mkdir(self::UPLOAD_DIR.$iProgId."/");
				}
			}
			$sUploadName = 'Bg_ProgrammeNeufImage'.'.'.$oFile->getExtension();
			self::prepAndUploadImg($iProgId,$oFile,$sUploadName,1280);
		}
		if($sRes){
			$this->prog_neuf('Programme neuf créer avec succès','success');
		}else{
			$this->prog_neuf('Erreur lors de la création du programme neuf','error');
		}
		
	}
	
	public function edit_prog($iIdProg = '',$sMessage = '',$sStatus = null){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/edit_prog.htm');	
		$this->setSubTitle('Programmes neufs - Édition');
		if(isset($this->aVars['aParams'][0]) || !empty($iIdProg)){
			if(!isset($this->aVars['aParams'][0]))
			$iProgId = $iIdProg;
			else
			$iProgId = $this->aVars['aParams'][0];
			$sTargetPath = $iProgId."/";
			$oProgramme = PROGRAMME::SELECT()->WHERE('programme_id',$iProgId)->getOne();
			$this->aVars[$this->sRequestPage]['oProgramme'] = $oProgramme;
			//echo"<pre>";print_r($oProgramme);echo"</pre>";
			$oAnnonces = PROGRAMME_ANNONCE::SELECT('reference as ref')->JOINAnnonce()->WHERE('programme_id',$iProgId)->exec();
			foreach($oAnnonces as $kAnnonce=>$vAnnonce){
				$aAnnonces[] = $vAnnonce->getRef();
			}
			$sAnnonces = implode(';',$aAnnonces);
			$this->aVars[$this->sRequestPage]['sAnnonces'] = $sAnnonces;
			//echo"<pre>";print_r($aAnnonces);echo"</pre>";
			$aImg = self::searchdir(self::UPLOAD_DIR.$iProgId."/");
			array_shift($aImg);
			foreach($aImg as $kImg=>$vImg){
				$sImgName = str_replace(self::UPLOAD_DIR.$iProgId."/",'',$vImg);
				$aImg = explode('_',$sImgName);
				if($aImg[0]=='Orig'){
					$aImages[] = substr($sImgName,0, strrpos($sImgName, '.') );
				}
				
			}
			$this->aVars[$this->sRequestPage]['aImages'] = $aImages;
			$this->aVars[$this->sRequestPage]['iProgId'] = $iProgId;
			//echo"<pre>";print_r($aImages);echo"</pre>";
		}else{
			$this->prog_neuf('L\'ID du programme n\'a pas été correctement transmise','warning');
		}
		if(!empty($sMessage) && isset($sStatus)){
			$this->aVars[$this->sRequestPage]['sMessage'] = $sMessage;
			$this->aVars[$this->sRequestPage]['sStatus'] = $sStatus;
		}
	}
	
	public function edit_prog_exec(){
		if(isset($_POST['programme_id']))
		{
			$iProgId = $_POST['programme_id'];
			$oProgramme = new ProgrammeRecord();
			$oProgramme->setProgramme_id($iProgId);
			$sRes = false;
			$oProgramme->setProgramme_titre($_POST['programme_titre']);
			$oProgramme->setProgramme_description_fr($_POST['programme_description_fr']);
			$oProgramme->setProgramme_description_en($_POST['programme_description_en']);
			$oProgramme->setProgramme_partenaire($_POST['programme_partenaire']);
			$oProgramme->setProgramme_identifiant($_POST['programme_identifiant']);
			if($oProgramme->update()) $sRes=true; else $sRes = false;		
			
			$aAnnonces = explode(';',$_POST['programme_annonces']);
			$oQrydel = PROGRAMME_ANNONCE::DELETE()->WHERE('programme_id',$iProgId)->exec();
			if(!empty($aAnnonces)){
				foreach($aAnnonces as $kAnnonce=>$vAnnonce){
					
					$oAnnonce = ANNONCE::SELECT('annonce_id')->WHERE('reference',$vAnnonce)->getOne();
					$oQry = PROGRAMME_ANNONCE::INSERT(array('programme_id','annonce_id'))->values(array($iProgId,$oAnnonce->getAnnonce_id()))->exec();
				}
			}
			
			if(isset($GLOBALS['FILES'])){
				$aImg = self::searchdir(self::UPLOAD_DIR.$iProgId."/");
				array_shift($aImg);
				foreach($aImg as $kImg=>$vImg){
					$sImgName = str_replace(self::UPLOAD_DIR.$iProgId."/",'',$vImg);
					$aImg = explode('_',$sImgName);
					if($aImg[0]=='Orig'){
						$aImages[] = substr($sImgName,0, strrpos($sImgName, '.') );
					}
				}
				$aKey = array_keys($aImages);
				$iLastKey = max($aKey);
				$iNbImg = count($aImages);
				$sLastImg =$aImages[$iLastKey];
				
				$aTempo = explode('_',$sLastImg);
				$iCurrIdImg = $aTempo[max(array_keys($aTempo))]; 
				if(!isset($iCurrIdImg) || empty($iCurrIdImg)) $iCurrIdImg = 0;	 	
				if(isset($GLOBALS['FILES']['images_annexes'])){
				foreach($GLOBALS['FILES']['images_annexes'] as $kFile=>$oFile){
					if(self::prepAndUploadImg($iProgId,$oFile,'Orig_ProgrammeNeufImage_'.$iCurrIdImg.'.'.$oFile->getExtension(),1000)){
					$sRes = true;
					$iCurrIdImg++;
					}else{
					$sRes = false;
					}
				}
				}
				if(isset($GLOBALS['FILES']['img_principale'])){
				$oFile = $GLOBALS['FILES']['img_principale'];
				
					if($iProgId !='') {
						$sTargetPath = $iProgId."/";
						if(!is_dir(self::UPLOAD_DIR.$iProgId."/")){
							mkdir(self::UPLOAD_DIR.$iProgId."/");
						}
					}
					$sUploadName = 'Bloc_ProgrammeNeufImage'.'.jpg';
					if(file_exists(self::UPLOAD_DIR.$sTargetPath.$sUploadName)){
						unlink(self::UPLOAD_DIR.$sTargetPath.$sUploadName);
					}
					$oFile->copy(self::UPLOAD_DIR.$sTargetPath);
					list($largeur, $hauteur) = getimagesize(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());		
					$destination = imagecreatetruecolor(300, 300);
					$source = imagecreatefromjpeg(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
					imagecopyresampled($destination, $source, 0, 0, 0, 0, 300, 300, $largeur, $hauteur);
					if(imagejpeg($destination,self::UPLOAD_DIR.$sTargetPath.$sUploadName)){
						$sRes = true;
						imagedestroy($destination);
						imagedestroy($source);
						unlink(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
					}else{
						$sRes = false;
					}
				}
				if(isset($GLOBALS['FILES']['img_bg'])){
				$oFile = $GLOBALS['FILES']['img_bg'];
				
					if($iProgId !='') {
						$sTargetPath = $iProgId."/";
						if(!is_dir(self::UPLOAD_DIR.$iProgId."/")){
							mkdir(self::UPLOAD_DIR.$iProgId."/");
						}
					}
					
					$sUploadName = 'Bg_ProgrammeNeufImage'.'.'.$oFile->getExtension();
					if(file_exists(self::UPLOAD_DIR.$sTargetPath.$sUploadName)){
						unlink(self::UPLOAD_DIR.$sTargetPath.$sUploadName);
					}
					self::prepAndUploadImg($iProgId,$oFile,$sUploadName,1280);
					/*$oFile->copy(self::UPLOAD_DIR.$sTargetPath);
					rename(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName(),self::UPLOAD_DIR.$sTargetPath.$sUploadName);*/
				}
			}
			if($sRes){
				$sStatut = 'success';
				$sMessage = 'Programme neuf édité avec succès';
				
			}else{
				$sStatut = 'error';
				$sMessage = 'Erreur lors de l\'édition du programme neuf id : '.$iProgId;
			}
		}else{
			$sStatut = 'error';
			$sMessage = ' Erreur lors de la transmission des données pour l\'édition du programme';
		}
		$this->edit_prog($iProgId,$sMessage,$sStatut);
	}
	
	public function delete_img(){
		if(isset($this->aVars['aParams'][0]) && isset($this->aVars['aParams'][1]) ){
			$iProgId = $this->aVars['aParams'][0];
			$sTargetPath = $iProgId."/";
			$sImageName = $this->aVars['aParams'][1].".jpg";
			if(file_exists(self::UPLOAD_DIR.$sTargetPath.$sImageName)){
				if(unlink(self::UPLOAD_DIR.$sTargetPath.$sImageName)) {
					$sStatut = 'success';
					$sMessage = 'Suppression du fichier : '.$sImageName;
				}else{
					$sStatut = 'error';
					$sMessage = 'Erreur lors de la suppression du fichier : '.$sImageName;
				}
			}else{
				$sStatut = 'error';
				$sMessage = $sImageName.' introuvable suppression impossible';
			}
		}else{
			$sStatut = 'error';
			$sMessage = ' Erreur lors de la transmission des données pour la suppression du fichier : '.$sImageName;
		}
		$this->edit_prog($iProgId,$sMessage,$sStatut);
	}
	
	public function edit_img(){
		if(isset($_POST['img_name']) && isset($_POST['programme_id'])){
			/*echo"<pre>";print_r($_POST);echo"</pre>";
			echo"<pre>";print_r($_FILES);echo"</pre>";
			echo"<pre>";print_r($GLOBALS);echo"</pre>";die();*/
			$oFile = $GLOBALS['FILES']['img_upload'];
			$iProgId = $_POST['programme_id'];
			if($iProgId !='') {
				$sTargetPath = $iProgId."/";
				if(!is_dir(self::UPLOAD_DIR.$iProgId."/")){
					mkdir(self::UPLOAD_DIR.$iProgId."/");
				}
			}
			$sFileName = $_POST['img_name'].'.jpg';
			if(file_exists(self::UPLOAD_DIR.$sTargetPath.$sFileName)){
				unlink(self::UPLOAD_DIR.$sTargetPath.$sFileName);
			}
			$oFile->copy(self::UPLOAD_DIR.$sTargetPath);
			if(self::prepAndUploadImg($iProgId,$oFile,$sFileName,1000)){
				$sStatut = 'success';
				$sMessage = 'Remplacement du fichier : '.$sFileName;
			}else{
				$sStatut = 'error';
				$sMessage = ' Erreur lors de la mise en ligne du nouveau fichier : '.$sFileName;
			}
				
		}else{
			$sStatut = 'error';
			$sMessage = ' Erreur lors de la transmission des données pour l\'édition du fichier : '.$sFileName;
		}
		$this->edit_prog($iProgId,$sMessage,$sStatut);
	}
	
	public function getprogbyref(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/getprogbyref.htm');	
		$this->setSubTitle('Programmes neufs');
		if(isset($_POST['ref']) && $_POST['ref']!=''){
			$iRef = $_POST['ref'];
			//$iRef = 1353;
			$oAnnRefs = ANNONCE::SELECT('reference')->WHERE('reference',array('like'),$iRef.'-%')->exec();
			$aAnnref = array();
			foreach($oAnnRefs as $kRef=>$vRef){
				$aAnnref[] = $vRef->getreference();
			}
			$sAnnRefs = implode(';',$aAnnref);
			echo $sAnnRefs;
			die();		
		}else{
			die('0');	
		}
	}
	
	public function delete_prog(){
		if(isset($this->aVars['aParams'][0])){
			$iProgId = $this->aVars['aParams'][0];
			$sTargetPath = $iProgId."/";
			self::rrmdir(self::UPLOAD_DIR.$sTargetPath);
			PROGRAMME_ANNONCE::DELETE()->WHERE('programme_id',$iProgId)->exec();
			PROGRAMME::DELETE()->WHERE('programme_id',$iProgId)->exec();
			$this->prog_neuf('Programme neuf supprimé avec succès','success');
		}else{
			$this->prog_neuf('Erreur lors de la suppression du programme neuf','error');
		}
	}
	
	private function prepAndUploadImg($iProgId,$oFile,$sUploadName,$iSize = 1000){
		
		if($iProgId !='') {
			$sTargetPath = $iProgId."/";
			if(!is_dir(self::UPLOAD_DIR.$iProgId."/")){
				mkdir(self::UPLOAD_DIR.$iProgId."/");
			}
		}
		if(file_exists(self::UPLOAD_DIR.$sTargetPath.$sUploadName)){
			unlink(self::UPLOAD_DIR.$sTargetPath.$sUploadName);
		}
		$oFile->copy(self::UPLOAD_DIR.$sTargetPath);
		list($largeur, $hauteur) = getimagesize(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
		if($largeur>$iSize) {
			$n_largeur_big = $iSize;
			$ratio_big = $n_largeur_big/$largeur;
			$n_hauteur_big = $hauteur * $ratio_big;
			$destination = imagecreatetruecolor($n_largeur_big, $n_hauteur_big);
			$source = imagecreatefromjpeg(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
			imagecopyresampled($destination, $source, 0, 0, 0, 0, $n_largeur_big, $n_hauteur_big, $largeur, $hauteur);
			imagejpeg($destination,self::UPLOAD_DIR.$sTargetPath.$sUploadName);
			imagedestroy($destination);
			imagedestroy($source);
			unlink(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName());
		}else{
			rename(self::UPLOAD_DIR.$sTargetPath.$oFile->getFileName(),self::UPLOAD_DIR.$sTargetPath.$sUploadName);
		}
		if(file_exists(self::UPLOAD_DIR.$sTargetPath.$sUploadName)){
			return true;
		}else{
			return false;
		}
	}
	
	private function rrmdir($dir) { 
	   if (is_dir($dir)) { 
		 $objects = scandir($dir); 
		 foreach ($objects as $object) { 
		   if ($object != "." && $object != "..") { 
			 if (filetype($dir."/".$object) == "dir") rrmdir($dir."/".$object); else unlink($dir."/".$object); 
		   } 
		 } 
		 reset($objects); 
		 rmdir($dir); 
	   } 
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