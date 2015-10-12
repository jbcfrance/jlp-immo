<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class programmeneufAction
 * Description		: Cette class génére l'interface de présentation des programmes neufs.
 * @templates dir 	: templates/programmeneuf
 *
*/
class programmeneufAction extends programmeneufAction_BASE {

	
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

	public function doDefault ($aParams=array()) {
		$oQuery = PROGRAMME::SELECT()->exec();
		$this["PrgNeuf"] = $oQuery;
		$this->setTitle("JLP Immo: Programmes neufs Saint-Gervais, Sallanches et Passy");
		$this->addMeta('description',utf8_encode('Agence immobilière, achat vente appartements dans programmes neufs et renovation à Passy, Saint-gervais, Sallanches'));
		Xtremplate::$vars['sTitleH1'] = utf8_encode("Programmes neufs Saint-Gervais Passy Sallanches Contamines");
	}
	public function doDetailPrgNeuf ($aParams=array()) {
		$this->setLayout("layout");
		$this->addJavaScript(SCRIPTS.'prgneuf.js');
		
		$this->programme_identifiant = $aParams['programme_identifiant'];
			$oQuery = PROGRAMME::SELECT()->WHERE('programme_identifiant',$this->programme_identifiant)->exec();
			Xtremplate::$vars['programme']  = $oQuery;
			foreach($oQuery as $kQueyr=>$vQuery) {
				$sTitlePage = "Programme neuf : ".$vQuery->getprogramme_titre() ;
				$sDescPage = "JLP-IMMO, Agence immobilière, achat vente appartements dans ".$vQuery->getprogramme_titre()." à ";
				$sPrgH1 = $vQuery->getprogramme_titre();
				$this->programme_id = $vQuery->getprogramme_id();
				if($vQuery->getprogramme_partenaire() ==1)
				$this['bShowPartenaire'] = true;
				else
				$this['bShowPartenaire'] = false;
			}
			if(!empty($oQuery)){
			if(file_exists("web/images/programmeneufs/".$this->programme_id."/Bg_ProgrammeNeufImage.jpg")){
				Xtremplate::$vars['ProgPhoto'] = "programmeneufs/".$this->programme_id."/Bg_ProgrammeNeufImage.jpg";
				/*list($photoMediumL,$photoMediumH) = getimagesize("web/images/programmeneufs/".$this->programme_id."/ProgrammeNeufImage.jpg");
				$this['CadrePhotoPaddingTop'] = (512-($photoMediumH))/2;
				$this['ProgrammeNeufImage_1L'] = $photoMediumL;
				$this['ProgrammeNeufImage_1H'] = $photoMediumH;*/
				}
			$aImages = array();
			$aImg = self::searchdir("web/images/programmeneufs/".$this->programme_id."/");
			array_shift($aImg);
			foreach($aImg as $kImg=>$vImg){
				$sImgName = str_replace("web/images/programmeneufs/".$this->programme_id."/",'',$vImg);
				//echo $sImgName."<br>";
				$aImgs = explode('_',$sImgName);
				if($aImgs[0]=='Orig'){
					$aImages[] = "web/images/programmeneufs/".$this->programme_id."/".$sImgName;
				}
				
			}
			//echo"<pre>";print_r($aImages);echo"</pre>";die();
			//echo"<pre>";print_r($aImages);echo"</pre>";die();
			Xtremplate::$vars['aArrayLightBox'] = $aImages;
			
			
			/*$lightBoxArray = array();
			$miniArray = array();
			for($i=2;$i<=5;$i++){
				if(file_exists("web/images/programmeneufs/".$this->programme_id."/Big_ProgrammeNeufImage_".$i.".jpg")) {
					$lightBoxArray = array_merge($lightBoxArray,array($i=>"web/images/programmeneufs/".$this->programme_id."/Big_ProgrammeNeufImage_".$i.".jpg"));
					if(file_exists("web/images/programmeneufs/".$this->programme_id."/Mini_ProgrammeNeufImage_".$i.".jpg")) {
						$miniArray = array_merge($miniArray,array($i=>"web/images/programmeneufs/".$this->programme_id."/Mini_ProgrammeNeufImage_".$i.".jpg"));
					}
				}
			}	
			$this['aArrayLightBox'] = $lightBoxArray;*/
			$this['aMiniArray'] = $miniArray;
			$oQuery = PROGRAMME_ANNONCE::SELECT('programme_annonce.annonce_id','programme_annonce.programme_id','programme_titre','typeBien','categorie','chambres','nbSallesDEau','surface','montant','villeAAfficher','villePublique','montant','reference','listePhotoOrig','photoThumb','texte','textAnglais')->Join_ANNONCE()->ON('annonce.annonce_id',ANNONCE::Field('programme_annonce.annonce_id'))->Join_PROGRAMME('LEFT')->ON('programme.programme_id',PROGRAMME::Field('programme_annonce.programme_id'))->WHERE('programme_annonce.programme_id',$this->programme_id)->ORDERBY('montant')->exec();
		$this["listeAnnonce"] = $oQuery;
		$aListePlan = array();
		foreach($oQuery as $kQry=>$vQry){
			//echo"<pre>";print_r($vQry->getAnnonce(0)->getannonce_id());echo"</pre>";
			$aTmp = explode('|',$vQry->getAnnonce(0)->getlistePhotoOrig());
			$aListePlan[$vQry->getAnnonce(0)->getannonce_id()] = $aTmp[1]; 
			$sLocPrg = $vQry->getAnnonce(0)->getvillePublique();
		}
		$this['aListePlan'] = $aListePlan;
		
		$this->setTitle($sTitlePage ." - ". ucwords(strtolower($sLocPrg)));
		$this->addMeta('description',utf8_encode($sDescPage.ucwords(strtolower($sLocPrg))));
		Xtremplate::$vars['sTitleH1'] = utf8_encode("Achat appartement programme neuf ".$sPrgH1." ".ucwords(strtolower($sLocPrg)));
		//echo"<pre>";print_r($aListePlan);echo"</pre>";
		}
			
	}
}
?>