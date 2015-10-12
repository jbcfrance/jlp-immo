<?php
class infospasserellePage extends Page {
	
	public function traitementPage() {
		if(isset($_POST) and !empty($_POST)){
			$aPost = array();
			foreach($_POST as $kPost=>$vPost){
				$aPost[$kPost] = $vPost;	
			}
		}
		
		$this->aVars[$this->aVars['sAction']] = array();
		
		//if(isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == 1){
			if(method_exists(&$this, $this->aVars['sAction'])){
				$this->{$this->aVars['sAction']}(); 
			}else{
				$this->{'infos'}();
			}
		//}else{
		//	$this->redirect('dashboard','accueil');
		//}
	}

	public function infos($sMessage = '',$sStatus = null){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/infos.htm');	
		$this->setSubTitle('Gestion de la passerelle');
		$oPasserelle = PASSERELLE::SELECT()->ORDERBY('passerelle_id ASC')->exec();
		$this->aVars[$this->sRequestPage]['oPasserelle'] = $oPasserelle;
		
		if(!empty($sMessage) && isset($sStatus)){
			$this->aVars[$this->sRequestPage]['sMessage'] = $sMessage;
			$this->aVars[$this->sRequestPage]['sStatus'] = $sStatus;
		}
	}	
	
	public function bloclog(){
		$this->setLayout('blankLayout.htm');	
		$this->setSubTitle('');
		
		$oPasserelle  = PASSERELLE::SELECT()->WHERE('passerelle_id',$_POST['id'])->getOne();
		echo "<div class='title'>Passerelle du ".$oPasserelle->getPasserelle_date()."</div>
	<div class='content'><pre>".$oPasserelle->getPasserelle_log()."</pre></div>";die();
	}
	
	public function testval(){
				$iSizeW = 180;
				$iSizeH = 120;
				$sFile  = 'front/web/import/annonces/5980830.jpg';
				//if(!file_exists('front/web/import/annonces/2661101.jpg'))die('FILE!!');
				//Calcul des nouvelles dimensions
				list($largeur, $hauteur) = getimagesize($sFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
				//$largeur = 1600;
				//$hauteur = 1300;
				echo "Largeur : ".$largeur."<br>Hauteur : ".$hauteur."<br>";
				$iWidthByRatio = 0;
				$iHeightByRatio = 0;
				
				$r1 = $iSizeW/$largeur;
				$r2 = $iSizeH/$hauteur;
				echo "<br>*<br>R1 ".$r1."<br>R2 ".$r2."<br>";
				$r=min(array($r1,$r2));
				echo "Min entre R1 et R2 ".$r;
				$iWidthByRatio = $largeur * $r;
				$iHeightByRatio = $hauteur * $r;
				echo "<br>*<br> Nouvelle Largeur ".$iWidthByRatio."<br> Nouvelle Hauteur ".$iHeightByRatio."<br>";
				
				if($r1<$r2){
					$iPosX = 0;
					$iPosY = 60-90*($hauteur/$largeur);
				}else{
					$iPosX = 90-60*($largeur/$hauteur);
					$iPosY = 0;
				}
				echo '+++<br> Decalage en X ';
				echo $iPosX;
				echo '<br> Decalage en Y ';
				echo $iPosY;
				echo '<br>';
				
				/*$new_largeur = $iSizeW;
				$ratio = $new_largeur/$largeur;
				$new_hauteur = $hauteur * $ratio;
				if($new_hauteur>$iSizeH) {
					$new_hauteur = $iSizeH;
					$ratio = $new_hauteur/$hauteur;
					$new_largeur = $largeur * $ratio;
					$iWidthByRatio = $new_largeur;
					$iHeightByRatio = $new_hauteur;
				}else{
					$iWidthByRatio = $new_largeur;
					$iHeightByRatio = $new_hauteur;
				}
				if($iWidthByRatio!=$iSizeW){
					$iPosX = ($iSizeW-$iWidthByRatio)/2;
				}else{
					$iPosX=0;
				}
				if($iHeightByRatio!=$iSizeH){
					$iPosY = ($iSizeH-$iHeightByRatio)/2;
				}else{
					$iPosY=0;
				}*/
				
				/*echo '<br>';
				echo $iWidthByRatio;
				echo '<br>';
				echo $iHeightByRatio;
				echo '<br>';
				echo $iPosX;
				echo '<br>';
				echo $iPosY;
				echo '<br>';
				
				echo '<br>';
				
				echo '<br>';*/
				die();
	}
	
	public function testimg(){
		header('Content-type: image/jpeg');
				$iSizeW = 180;
				$iSizeH = 120;
				$sFile  = 'front/web/import/annonces/5980830.jpg';
				//if(!file_exists('front/web/import/annonces/2661101.jpg'))die('FILE!!');
				// Calcul des nouvelles dimensions
				list($largeur, $hauteur) = getimagesize($sFile); //list est un moyen plus pratique pour ne récupérer que ce qu'on veut
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
				$destination = imagecreatetruecolor(180, 120);
				// Créer un fond 
				$bga = imagecolorallocate($destination, 0, 0, 0);
				//Affecter le fond au support
				imagefilledrectangle($destination, 0, 0, 180, 120, $bga);
				//Charger l'image d'origine
				$source = imagecreatefromjpeg($sFile);
				//Créer la nouvelle image
				imagecopyresampled($destination, $source, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $largeur, $hauteur);

				// Redimensionnement
				imagecopyresampled($destination, $source, $iPosX, $iPosY, 0, 0, $iWidthByRatio, $iHeightByRatio, $largeur, $hauteur);
				imagejpeg($destination);
				
				
	}
}
?>