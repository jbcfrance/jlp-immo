<?php
class adminPage extends Page {
	
	public function traitementPage() {
		if(isset($_POST) and !empty($_POST)){
			$aPost = array();
			foreach($_POST as $kPost=>$vPost){
				$aPost[$kPost] = $vPost;	
			}
		}
		
		$this->aVars[$this->aVars['sAction']] = array();
		
		if(isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == 1){
			if(method_exists(&$this, $this->aVars['sAction'])){
				$this->{$this->aVars['sAction']}(); 
			}else{
				$this->{'dashboard'}();
			}
		}else{
			$this->{'accueil'}();
		}
		
		
		
	}
	
	public function accueil(){
		$this->setLayout('loginLayout.htm');
		$this->setTpl($this->sRequestPage.'/accueil.htm');	
		$this->setSubTitle('Login');	
	}
	
	public function dashboard(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/dashboard.htm');	
		$this->setSubTitle('Dashboard');
		/*$oAdmin = new AdminRecord();
		$oAdmin->setAdmin_id(1);
		$oAdmin->select();
		echo"<pre>";print_r($oAdmin);echo"</pre>";*/
	}
	
	public function prog_neuf(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/prog_neuf.htm');	
		$this->setSubTitle('Programmes neufs');
	}
	
	public function add_prog(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/add_prog.htm');	
		$this->setSubTitle('Ajout d\'un programme neuf');
	}
	
	public function add_prog_exec(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/add_prog_exec.htm');	
		$this->setSubTitle('Add Prog Neuf Execution');
	}
	
	
	
	public function galerie(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/galeries.htm');	
		$this->setSubTitle('Dashboard');
		$oQryCat = mysql_query("SELECT * FROM categorie");
		while($aCat = mysql_fetch_array($oQryCat)){
			$aTmp = array(
				"categorie_id"=>$aCat["categorie_id"],
				"categorie_name"=>$aCat["categorie_name"],
				"categorie_titre"=>$aCat["categorie_titre"],
				"categorie_template"=>$aCat["categorie_template"],
				"categorie_galeries"=>array()
			);
			$oQryGal = mysql_query("SELECT * FROM galerie WHERE galerie_categorie_id=".$aCat["categorie_id"]."");
			while($aGal = mysql_fetch_array($oQryGal)){
				$aTmp['categorie_galeries'][] = array(
				"galerie_id" => $aGal["galerie_id"],
				"galerie_categorie_id" => $aGal["galerie_categorie_id"],
				"galerie_image" => $aGal["galerie_image"],
				"galerie_titre" => $aGal["galerie_titre"],
				"galerie_intro" => $aGal["galerie_intro"]
				);
			}
			$this->aVars[$this->sRequestPage]['categories'][]= $aTmp;
		}
		
	}
	
	public function add_cat(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/add_cat.htm');	
		$this->setSubTitle('Dashboard');
	}
	public function add_gal(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/add_gal.htm');	
		$this->setSubTitle('Dashboard');
		$oQryCat = mysql_query("SELECT * FROM categorie");
		while($aCat = mysql_fetch_array($oQryCat)){
			$aTmp = array(
				"categorie_id"=>$aCat["categorie_id"],
				"categorie_name"=>$aCat["categorie_name"],
				"categorie_titre"=>$aCat["categorie_titre"],
				"categorie_template"=>$aCat["categorie_template"],
				"categorie_galeries"=>array()
			);
			$oQryGal = mysql_query("SELECT * FROM galerie WHERE galerie_categorie_id=".$aCat["categorie_id"]."");
			while($aGal = mysql_fetch_array($oQryGal)){
				$aTmp['categorie_galeries'][] = array(
				"galerie_id" => $aGal["galerie_id"],
				"galerie_categorie_id" => $aGal["galerie_categorie_id"],
				"galerie_image" => $aGal["galerie_image"],
				"galerie_titre" => $aGal["galerie_titre"],
				"galerie_intro" => $aGal["galerie_intro"]
				);
			}
			$this->aVars[$this->sRequestPage]['categories'][]= $aTmp;
		}
	}
	
	public function photos($sMessage = '',$sStatus = null){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/photos.htm');	
		$this->setSubTitle('Dashboard');
		$oQryCat = mysql_query("SELECT * FROM categorie");
		while($aCat = mysql_fetch_array($oQryCat)){
			$aTmp = array(
				"categorie_id"=>$aCat["categorie_id"],
				"categorie_name"=>$aCat["categorie_name"],
				"categorie_titre"=>$aCat["categorie_titre"],
				"categorie_template"=>$aCat["categorie_template"],
				"categorie_galeries"=>array()
			);
			$oQryGal = mysql_query("SELECT * FROM galerie WHERE galerie_categorie_id=".$aCat["categorie_id"]."");
			while($aGal = mysql_fetch_array($oQryGal)){
				$aTmp['categorie_galeries'][] = array(
				"galerie_id" => $aGal["galerie_id"],
				"galerie_categorie_id" => $aGal["galerie_categorie_id"],
				"galerie_image" => $aGal["galerie_image"],
				"galerie_titre" => $aGal["galerie_titre"],
				"galerie_intro" => $aGal["galerie_intro"]
				);
			}
			$this->aVars[$this->sRequestPage]['categories'][]= $aTmp;
		}
		if(isset($_POST['select_galerie'])){
			$iIdGal = $_POST['select_galerie'];
		}else{
			$iIdGal	= 1;
		}
		$this->aVars[$this->sRequestPage]['galerie_id'] = $iIdGal;
		$oQryPhotos = mysql_query("SELECT * FROM photo WHERE photo_galerie_id  = '".$iIdGal."' ORDER BY photo_order ASC");
		while($aPhoto = mysql_fetch_array($oQryPhotos)){
			$aTmp = array(
				"photo_id"=>$aPhoto['photo_id'],
				"photo_galerie_id"=>$aPhoto['photo_galerie_id'],
				"photo_fichier"=>$aPhoto['photo_fichier'],
				"photo_legende"=>$aPhoto['photo_legende'],
				"photo_order"=>$aPhoto['photo_order']
			);	
			$this->aVars[$this->sRequestPage]['photos'][]= $aTmp;
			$this->aVars[$this->sRequestPage]['photoOrder'][] = $aPhoto['photo_order'];
		}
		if(!empty($sMessage) && isset($sStatus)){
			$this->aVars[$this->sRequestPage]['sMessage'] = $sMessage;
			$this->aVars[$this->sRequestPage]['sStatus'] = $sStatus;
		}
	}
	
	public function add_photo(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/add_photo.htm');
		$oImgFile = new imgFile($GLOBALS['FILES']['photo_file'],$GLOBALS['FILES']['photo_file']->getFileName());
		$aFileName = explode('.',$GLOBALS['FILES']['photo_file']->getFileName());
		$oQryPhotos = mysql_query("SELECT * FROM photo");
		while($aQryPhoto = mysql_fetch_array($oQryPhotos)){
			$aPhotos[] = $aQryPhoto['photo_fichier'];	
		}
		if(in_array($aFileName[0],$aPhotos)){
			$bUpdate = true;	
		}else{
			$bUpdate = false;	
		}
		$oImgFile->uploadOrig();
		if($oImgFile->traitementGlobal()){
			if($bUpdate){
				$oQry = mysql_query("UPDATE  `photo` SET  `photo_legende` = '".$_POST['photo_legende']."' WHERE  `photo_fichier` = '".$aFileName[0]."' LIMIT 1 ");
				$this->photos('Fichier : '.$aFileName[0].' mis à jours','success');
			}else{
				$oQry = mysql_query("INSERT INTO `photo` (`photo_id`, `photo_galerie_id`, `photo_fichier`, `photo_order`, `photo_legende`) VALUES ('', '".$_POST['galerie']."', '".$aFileName[0]."', '".$_POST['photo_order']."', '".$_POST['photo_legende']."');");
				$this->photos('Photo mise en ligne avec succès','success');
			}
			
		}else{
			$this->photos('Une erreure est survenu lors de la mise en ligne.','error');
		}
			
	}
	
	public function edit_field(){
		$this->setTpl($this->sRequestPage.'/edit_field.htm');	
		switch($this->aVars['aParams'][0]){
			case 'photo_legende':
				if($oQry = mysql_query("UPDATE  `photo` SET  `photo_legende` = '".$_POST['photo_legende']."' WHERE  `photo_id` = '".$_POST['id']."' LIMIT 1 "))
				echo $_POST['photo_legende'];
				else
				echo "Erreur d'édition";
			break;
			
			case 'photo_order':
				if($oQry = mysql_query("UPDATE  `photo` SET  `photo_order` = '".$_POST['photo_order']."' WHERE  `photo_id` = '".$_POST['id']."' LIMIT 1 "))
				echo $_POST['photo_order'];
				else
				echo "Erreur d'édition";
			break;
		}
		die();
	}
	
	public function suppr_photo(){
		$oQry = mysql_query("SELECT * FROM photo WHERE photo_id = ".$this->aVars['aParams'][0]."");
		while($aPhotoData = mysql_fetch_array($oQry)){
				$aPhoto = array(
					'photo_id'=>$aPhotoData['photo_id'],
					'photo_galerie_id'=>$aPhotoData['photo_galerie_id'],
					'photo_fichier'=>$aPhotoData['photo_fichier'],
					'photo_order'=>$aPhotoData['photo_order'],
					'photo_legende'=>$aPhotoData['photo_legende']
				);
		}
		$oFile = new UtilFile($aPhoto['photo_fichier'].'.png', 38, 0, true, $aPhoto['photo_fichier'].'.png');
		$oImgFile = new imgFile($oFile,$aPhoto['photo_fichier'].'.png');
		if($oImgFile->deleteAll()){
			if($oQry = mysql_query("DELETE FROM photo WHERE photo_id = ".$aPhoto['photo_id']."")){
				$this->photos('Photo supprimée avec succès','success');
			}else{
				$this->photos('Une erreure est survenue lors de la suppression en base de donnée.','error');
			}
		}else{
			$this->photos('Une erreure est survenu lors de la suppression des fichiers.','error');
		}
	}
	
	public function links(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/links.htm');	
		$this->setSubTitle('Dashboard');
	}
	public function books(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/books.htm');	
		$this->setSubTitle('Dashboard');
	}
		
}
?>