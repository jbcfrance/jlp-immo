<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Février 2012
 *
 * Name				: Class passerelle
 * Description		: Cette class génére la passerrelle de mise à jours du site à partir du zip envoyé par le logiciel Connect'Immo de RodaCom
 * @templates dir 	: templates/passerelle
 *
*/
class passerellePage extends Page {
	
	const 	LOCAL_PATH = "front/web/import/";
	const 	FLASH_XML_DIR = "front/web/flash/";
	const 	TARGET_UNZIP_DIR = "front/web/import/annonces/";
	const 	LOG_FILE = "front/web/import/";
	const	ZIP_FILE = "connectimmo.zip";
	
	private $sLog = '',
			$iNbAnnonceTraite = 0,
			$iNbPhotoMaj = 0,
			$iNbAnnonceSuppr = 0,
			$iNbAnnonceAjouter = 0,
			$bStatusPasserelle = 0;
	
	public function traitementPage() {		
		$this->aVars[$this->aVars['sAction']] = array();
		
		if($this->aVars['sAction']=='imageGd')
		$this->{'imageGd'}();
		else
		$this->{'passerelle'}();
		
	} 
	
	public function passerelle(){
		//$oUpdate = Annonce::Update();
		//echo'<pre>--------------------------------------------
		
		//</pre>';
		/*Passage des annonces en StandBy*/
		// die;
		//echo $oUpdate->getConnexion()->protect('standby');
		//die;
		
		/*
		$oUpdate->set('status_annonce', $oUpdate->protect('standby'));
		$oUpdate->exec();
		*/
		
		if($this->aVars['sAction']=='rodacom')self::ToLog("Accès de Rodacom");
		//exit;
		$this->setLayout('blankLayout.htm');
		$this->setTpl($this->sRequestPage.'/passerelle.htm');	
		$this->setSubTitle('Passerelle');
		self::finalisePasserelle();
		//if(!$this->PrepAnnonces(self::ZIP_FILE)) {
		if(!$this->PrepAnnonces(self::ZIP_FILE)){
		
			/*STOP FICHIER NON IMPORTER*/
			exit;
		}else{
			self::ToLog("Fichier zip pret pour passerelle.");
			
			/*FICHIER IMPORTER AVEC SUCCES*/
			//	AGENCE::delete()->exec();
			//	NEGOCIATEUR::delete()->exec();
			//	ANNONCE::delete()->exec();
			
			$oXml = simplexml_load_file(self::LOCAL_PATH."/annonces/annonces.xml");
			
			
			//echo'<pre>--------------------------------------------
			
			//</pre>';
			/*Passage des annonces en StandBy*/
			//$oUpdate = Annonce::Update();
			//$oUpdate->protect('standby');
			
			
			$oUpdate = Annonce::Update()->set('status_annonce', "standby")->exec();
			//$oUpdate->exec();
			
			//echo'<pre>';print_r( $oUpdate->protect('standby') );echo'</pre>';
			//die;
			/*$aAnnoncesStandby = ANNONCE::SELECT()->exec();
			foreach($aAnnoncesStandby as $kAnn=>$vAnn){
				echo $vAnn->getAnnonce_id()."<br>";
				$oAnnonce = new annonceRecord();
				$oAnnonce->setannonce_id($vAnn->getAnnonce_id());
				$oAnnonce->setstatus_annonce('standby');
				$oAnnonce->udpate();
				$oAnnonce->select();
				//echo"<pre>";print_r($oAnnonce);echo"</pre>";
								
				echo $vAnn->getannonce_id()." : ".$oAnnonce->getstatus_annonce()."<br>";
			}*/
			//die();
			
			$aAgences 		= array();
			$aNegociateurs 	= array();
			
			foreach($oXml->annonce as $oNode) {
				/*Vars*/
				$aAgenceInfo 		= array();
				$aNegociateurInfo 	= array();
				$aAnnonceInfo 		= array();
				/*Traitement préliminaire du XML*/
				foreach ($oNode->children() as $elementAnnonce) {
					if(strstr($elementAnnonce->getName(),"A")=="Agence") {
						$tmp = $elementAnnonce;
						$aAgenceInfo = array_merge($aAgenceInfo,array($elementAnnonce->getName()=>(string)$tmp));
					}
					if(strstr($elementAnnonce->getName(),"N")=="Negociateur") {
						$tmp = $elementAnnonce;
						$aNegociateurInfo = array_merge($aNegociateurInfo,array($elementAnnonce->getName()=>(string)$tmp));
					}
					if((strstr($elementAnnonce->getName(),"A")!="Agence") && (strstr($elementAnnonce->getName(),"N")!="Negociateur")) {
						$tmp = $elementAnnonce;
						$aAnnonceInfo = array_merge($aAnnonceInfo,array($elementAnnonce->getName()=>(string)$tmp));
					}
				}
				// Agence :
				/*echo"<pre>";print_r($aAgenceInfo);echo"</pre>";
				echo"<pre>";print_r($aNegociateurInfo);echo"</pre>";
				echo"<pre>";print_r($aAnnonceInfo);echo"</pre>"; */
				$iAgenceID = $aAgenceInfo["idAgence"];
				$iNegociateurID = $aNegociateurInfo["idNegociateur"];
				
				
				/*
				 *	DEBUT AGENCE
				 */
				 $oAgence = new agenceRecord();
				foreach($aAgenceInfo as $kAgence=>$vAgence){
						if($kAgence=='idAgence')$kAgence = 'agence_id';
						$sFunc = 'set'.$kAgence;
						$oAgence->{$sFunc}($vAgence);
						
				}
				$oAgenceBdd = AGENCE::SELECT('agence_id')
					->where('agence_id', $iAgenceID )
					->getOne();
				
				if(isset($oAgenceBdd) && $oAgenceBdd->getagence_id()==$iAgenceID){
					// update :
						if(!in_array($iAgenceID,$aAgences)){
							$aAgences[] = $iAgenceID;
							self::ToLog("Agence   ".$iAgenceID." mise à jours");
						}
						$oAgence->update();
				}else{
					// insert :
						if(!in_array($iAgenceID,$aAgences)){
							$aAgences[] = $iAgenceID;
							self::ToLog("Agence   ".$iAgenceID." ajoutée");
						}
						$oAgence->insert();
				}
				/*
				 *	FIN AGENCE
				 */
				 /*
				 *	DEBUT NEGO
				 */
				  $oNegociateur = new negociateurRecord();
				foreach($aNegociateurInfo as $kNegociateur=>$vNegociateur){
						if($kNegociateur=='idNegociateur')$kNegociateur = 'negociateur_id';
						$sFunc = 'set'.$kNegociateur;
						$oNegociateur->{$sFunc}($vNegociateur);
						
				}
				$oNegociateurBdd = Negociateur::SELECT('negociateur_id')
					->where('negociateur_id', $iNegociateurID )
					->getOne();
				if(isset($oNegociateurBdd) && $oNegociateurBdd->getnegociateur_id()==$iNegociateurID){
					// update :
						if(!in_array($iNegociateurID,$aNegociateurs)){
							$aNegociateurs[] = $iNegociateurID;
							self::ToLog("Négociateur  ".$iNegociateurID." mit à jours");
						}
						$oNegociateur->update();
				}else{
					// insert :
						if(!in_array($iNegociateurID,$aNegociateurs)){
							$aNegociateurs[] = $iNegociateurID;
							self::ToLog("Négociateur  ".$iNegociateurID." ajouté");
						}
						$oNegociateur->insert();
				}
				/*
				 *	FIN NEGO
				 */
				 
				/*
				 *	DEBUT Annonce
				 */

				  $oAnnonce = new annonceRecord();
				$iAnnonceID = $aAnnonceInfo["identifiant"];
				$iAnnonceRef = $aAnnonceInfo["reference"];
				foreach($aAnnonceInfo as $kAnnonce=>$vAnnonce){
						if($kAnnonce=='identifiant')$kAnnonce = 'annonce_id';
						if($kAnnonce=='idAgence')$kAnnonce = 'annonce_agence_id';
						if($kAnnonce=='idNegociateur')$kAnnonce = 'annonce_negociateur_id';
						if($kAnnonce=='texteAnglais')$kAnnonce = 'textAnglais';
						if($kAnnonce=='numMandat'){
							if($vAnnonce=='') $vAnnonce = 0;	
						}
						if($kAnnonce != 'photos'){
						$sFunc = 'set'.$kAnnonce;
						$oAnnonce->{$sFunc}($vAnnonce);
						}
						
				}
				$this->iNbAnnonceTraite++;
				$aAnnoncePhotos = array();
				foreach ($oNode->photos->children() as $oPhoto) {
					$aAnnoncePhotos[] = (string)$oPhoto;
				}
				//echo"<pre>";print_r($aAnnoncePhotos);echo"</pre>";
				
				$oAnnonceBdd = Annonce::SELECT()
				->where('annonce_id', $iAnnonceID )
				->getOne();
				if(isset($oAnnonceBdd) && $oAnnonceBdd->getannonce_id()==$iAnnonceID){
					$bAnnonceNew = false;	
				}else{
					$bAnnonceNew = true;
				}
				if(isset($aAnnoncePhotos[0])){
					$oPhotoPrincipal = new outil_Photo( $aAnnoncePhotos[0] );
					if(!$bAnnonceNew){
						$oMd5PhotoOrig = ANNONCE::SELECT('photoOrigMd5')->WHERE('annonce_id',$iAnnonceID)->getOne();
						if(md5_file(BASE_PATH.'front/web/import/annonces/'.$oPhotoPrincipal->getName()) != $oMd5PhotoOrig->getPhotoOrigMd5()){
							$bUpdatePhoto = true;
						}else{
							$bUpdatePhoto = false;
						}
					}else{
						$bUpdatePhoto = true;
					}
					
					if($bUpdatePhoto){
						$bVerifTraitement = false;
						if($oPhotoPrincipal->createMediumFile("Medium".$iAnnonceID)) {
							$bVerifTraitement = true;	
						}
						if($oPhotoPrincipal->createThumbFile("Thumb".$iAnnonceID)) {
							$bVerifTraitement = true;	
						}
						if($bVerifTraitement){
							self::ToLog("Annonce Ref : ".$iAnnonceRef." : Photo principale traitée");
						}	
						$this->iNbPhotoMaj++;
					}elseif(!$bAnnonceNew){
						self::ToLog("Annonce Ref : ".$iAnnonceRef." Photo principale identique");
					}
					$ListingOrig = array();
						
					foreach ($aAnnoncePhotos as $vPhoto) {
						$oPhoto = new outil_Photo($vPhoto);
							$ListingOrig[] = (string)$oPhoto->getName();			
					}
					$oAnnonce->setPhotoCoeur('coeur/Coeur'.$iAnnonceID.'.jpg');
					$oAnnonce->setPhotoMedium('medium/Medium'.$iAnnonceID.'.jpg');
					$oAnnonce->setListePhotoOrig(''.implode('|',$ListingOrig).'');
					$oAnnonce->setPhotoThumb('thumb/Thumb'.$iAnnonceID.'.jpg');
				}else{
					$oAnnonce->setPhotoCoeur('');
					$oAnnonce->setPhotoMedium('');
					$oAnnonce->setListePhotoOrig('');
					$oAnnonce->setPhotoThumb('');
				}
				if($oAnnonce->getannonce_id()==0)$oAnnonce->setannonce_id($iAnnonceID);
				if(!$bAnnonceNew){
					// update :
						self::ToLog("Annonce Ref ".$iAnnonceRef." mise à jours");
						$oAnnonce->setstatus_annonce('valid');
						$oAnnonce->update();
				}else{
					// insert :
						$this->iNbAnnonceAjouter++;
						self::ToLog("Annonce Ref ".$iAnnonceRef." ajoutée");
						$oAnnonce->setstatus_annonce('valid');
						$oAnnonce->insert();
				}
				
				
			}
			
			/*
			 *	FIN Annonce
			 */
			 $AnnoncesStandby = ANNONCE::SELECT()->WHERE('status_annonce','standby')->exec();
			foreach($AnnoncesStandby as $kAnnoncesStandby=>$vAnnoncesStandby) {
				if(is_file("front/web/images/annonces/medium/Medium".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("front/web/images/annonces/medium/Medium".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("front/web/images/annonces/thumb/Thumb".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("front/web/images/annonces/thumb/Thumb".$vAnnoncesStandby->getAnnonce_id().".jpg");
				$tmpArrayOrig= explode("|",$vAnnoncesStandby->getlistePhotoOrig());
				foreach($tmpArrayOrig as $ktmpArrayOrig=>$vtmpArrayOrig){
					if(is_file("front/web/import/annonces/".$vtmpArrayOrig))
					unlink("front/web/import/annonces/".$vtmpArrayOrig);
				}
				$this->iNbAnnonceSuppr++;
				self::ToLog("Annonce Ref : ".$vAnnoncesStandby->getreference()." supprimée");
				ANNONCE::delete()->WHERE('annonce_id',$vAnnoncesStandby->getAnnonce_id())->exec();
			}
			
			$oTrad=new Traduction();
			$sXml = "<slides></slides>";
			$oXmlFR = new SimpleXMLElement($sXml);
			$oXmlEN = new SimpleXMLElement($sXml);
			$oQueryXml = ANNONCE::SELECT()->WHERE('coupdecoeur','oui')->exec();
			foreach($oQueryXml->getRecords() as $kQueryXml=>$vQueryXml) {
				$oXmlFR->addChild("slide");
					$oXmlFR->slide[$kQueryXml]->addAttribute("image","web/images/annonces/thumb/Thumb".$vQueryXml->getannonce_id().".jpg");
					$oXmlFR->slide[$kQueryXml]->addAttribute("type",$oTrad->getFr(strtolower($vQueryXml->gettypeBien())));
					$oXmlFR->slide[$kQueryXml]->addAttribute("lieu",ucwords(strtolower($vQueryXml->getvillePublique())));
					$oXmlFR->slide[$kQueryXml]->addAttribute("ref",$vQueryXml->getreference());
					$oXmlFR->slide[$kQueryXml]->addAttribute("content",$vQueryXml->gettexte());
					$oXmlFR->slide[$kQueryXml]->addAttribute("url","/annonce/".$vQueryXml->getannonce_id());
					$oXmlFR->slide[$kQueryXml]->addAttribute("prix",$vQueryXml->getmontant()." €");
					$oXmlFR->slide[$kQueryXml]->addAttribute("dpe",$vQueryXml->getconsommationenergie());
					$oXmlFR->slide[$kQueryXml]->addAttribute("ges",$vQueryXml->getemissionges());
				$oXmlEN->addChild("slide");
					$oXmlEN->slide[$kQueryXml]->addAttribute("image","web/images/annonces/thumb/Thumb".$vQueryXml->getannonce_id().".jpg");
					$oXmlEN->slide[$kQueryXml]->addAttribute("type",$oTrad->getEn(strtolower($vQueryXml->gettypeBien())));
					$oXmlEN->slide[$kQueryXml]->addAttribute("lieu",ucwords(strtolower($vQueryXml->getvillePublique())));
					$oXmlEN->slide[$kQueryXml]->addAttribute("ref",$vQueryXml->getreference());
					$oXmlEN->slide[$kQueryXml]->addAttribute("content",$vQueryXml->gettextAnglais());
					$oXmlEN->slide[$kQueryXml]->addAttribute("url","/annonce/".$vQueryXml->getannonce_id());
					$oXmlEN->slide[$kQueryXml]->addAttribute("prix",$vQueryXml->getmontant()." €");
					$oXmlEN->slide[$kQueryXml]->addAttribute("dpe",$vQueryXml->getconsommationenergie());
					$oXmlEN->slide[$kQueryXml]->addAttribute("ges",$vQueryXml->getemissionges());
			}
			$FileFR = "front/web/flash/frSlider.xml";
			$FileEN = "front/web/flash/enSlider.xml";
			if(file_exists($FileFR)){
				if(is_writable($FileFR)) {
					if(!$handle = fopen($FileFR, 'w')) {
						self::ToLog("Impossible d'ouvir le fichier XML Français");
						exit;
					}
					if(fwrite($handle,$oXmlFR->asXML()) === FALSE) {
						self::ToLog("Impossible d'écrire dans le fichier XML Français");
						exit;
					}
					self::ToLog("Mise à jours du Slider version française réussit");
				}else{
					self::ToLog("Le fichier XML Français n'est pas accèssible en écriture");
				}
			}
			
			if(file_exists($FileEN)){
				if(is_writable($FileEN)) {
					if(!$handle = fopen($FileEN, 'w')) {
						self::ToLog("Impossible d'ouvir le fichier XML Anglais");
						exit;
					}
					if(fwrite($handle,$oXmlEN->asXML()) === FALSE) {
						self::ToLog("Impossible d'écrire dans le fichier XML Anglais");
						exit;
					}
					self::ToLog("Mise à jours du Slider version anglaise réussit");
				}else{
					self::ToLog("Le fichier XML Anglais n'est pas accèssible en écriture");
				}
			}
			
			// Génération du sitemap
			$sXmlSite = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"></urlset>';
			$oXmlSite = new SimpleXMLElement($sXmlSite);
			$sSiteUrl = 'http://www.jlp-immo.com/';
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl);
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','daily');
			$oUrl->addChild('priority','1.00');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'programmeneuf');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','daily');
			$oUrl->addChild('priority','0.50');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'acheter');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','daily');
			$oUrl->addChild('priority','0.50');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'contact');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','monthly');
			$oUrl->addChild('priority','0.50');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'mentionlegale');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','yearly');
			$oUrl->addChild('priority','0.50');
			$oAnnonce = ANNONCE::SELECT()->exec();
			foreach($oAnnonce->getRecords() as $kAnn=>$vAnn){
				$oUrl = $oXmlSite->addChild('url');
				$oUrl->addChild('loc',$sSiteUrl.'annonce/'.$vAnn->getannonce_id());
				$oUrl->addChild('lastmod',date('c'));
				$oUrl->addChild('changefreq','daily');
				$oUrl->addChild('priority','0.50');	
			}
			$oProg = PROGRAMME::SELECT()->exec();
			foreach($oProg->getRecords() as $kProg=>$vProg){
				$oUrl = $oXmlSite->addChild('url');
				$oUrl->addChild('loc',$sSiteUrl.'prgneuf/'.$vProg->getprogramme_identifiant());
				$oUrl->addChild('lastmod',date('c'));
				$oUrl->addChild('changefreq','daily');
				$oUrl->addChild('priority','0.50');	
			}
			$FileSitemap = "front/sitemap.xml";
			if(file_exists($FileSitemap)){
				if(is_writable($FileSitemap)) {
					if(!$handle = fopen($FileSitemap, 'w')) {
						self::ToLog("Impossible d'ouvir le Sitemap");
						exit;
					}
					if(fwrite($handle,$oXmlSite->asXML()) === FALSE) {
						self::ToLog("Impossible d'écrire dans le Sitemap");
						exit;
					}
					self::ToLog("Mise à jours du Sitemap réussit");
				}else{
					self::ToLog("Le Sitemap n'est pas accèssible en écriture");
				}
			}
			
		}
		$this->bStatusPasserelle = 1;
		self::finalisePasserelle();
		
		
		
	}
	
	public function testXml(){
		$this->setLayout('blankLayout.htm');
		$this->setTpl($this->sRequestPage.'/passerelle.htm');	
		$this->setSubTitle('Passerelle');
		// Génération du sitemap
			$sXmlSite = '<?xml version="1.0" encoding="UTF-8"?>
<?xml-stylesheet type="text/xsl" href="gss.xsl"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd"></urlset>';
			$oXmlSite = new SimpleXMLElement($sXmlSite);
			$sSiteUrl = 'http://www.jlp-immo.com/';
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl);
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','daily');
			$oUrl->addChild('priority','1.00');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'programmeneuf');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','daily');
			$oUrl->addChild('priority','0.50');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'acheter');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','daily');
			$oUrl->addChild('priority','0.50');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'contact');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','monthly');
			$oUrl->addChild('priority','0.50');
			$oUrl = $oXmlSite->addChild('url');
			$oUrl->addChild('loc',$sSiteUrl.'mentionlegale');
			$oUrl->addChild('lastmod',date('c'));
			$oUrl->addChild('changefreq','yearly');
			$oUrl->addChild('priority','0.50');
			$oAnnonce = ANNONCE::SELECT()->exec();
			foreach($oAnnonce->getRecords() as $kAnn=>$vAnn){
				$oUrl = $oXmlSite->addChild('url');
				$oUrl->addChild('loc',$sSiteUrl.'annonce/'.$vAnn->getannonce_id());
				$oUrl->addChild('lastmod',date('c'));
				$oUrl->addChild('changefreq','daily');
				$oUrl->addChild('priority','0.50');	
			}
			$oProg = PROGRAMME::SELECT()->exec();
			foreach($oProg->getRecords() as $kProg=>$vProg){
				$oUrl = $oXmlSite->addChild('url');
				$oUrl->addChild('loc',$sSiteUrl.'prgneuf/'.$vProg->getprogramme_identifiant());
				$oUrl->addChild('lastmod',date('c'));
				$oUrl->addChild('changefreq','daily');
				$oUrl->addChild('priority','0.50');	
			}
			$FileSitemap = "front/sitemap.xml";
			if(file_exists($FileSitemap)){
				if(is_writable($FileSitemap)) {
					if(!$handle = fopen($FileSitemap, 'w')) {
						self::ToLog("Impossible d'ouvir le Sitemap");
						exit;
					}
					if(fwrite($handle,$oXmlSite->asXML()) === FALSE) {
						self::ToLog("Impossible d'écrire dans le Sitemap");
						exit;
					}
					self::ToLog("Mise à jours du Sitemap réussit");
				}else{
					self::ToLog("Le Sitemap n'est pas accèssible en écriture");
				}
			}
			echo $oXmlSite->asXML();
			die();	
	}
	public function imageGd(){
		$this->setLayout('blankLayout.htm');
		$this->setTpl($this->sRequestPage.'/imageGd.htm');
		require_once 'ImageText.php';
		
		$sTexte = "Situé sur le coteau de Passy, chalet bénéficiant d'une vue dégagée sur le Mont Joly. Agencé sur 3 niveaux, ce chalet offre un bel espace de vie avec cuisine rustique, 3 grandes chambres et possibilité d'un appartement indépendant au RDC. Classe énergie :C EXCELLENT ENSOLEILLEMENT !";

		//$im = imagecreate(500, 700);
		//$background = imagecolorallocate($im, 158, 158, 158);
		
	 $font_size = 4;

  //$ts=explode("\n",$sTexte);
  $width=0;
  $maxwidth = 300;
  $aTexte = array();
	while(strlen($sTexte)>=$maxwidth){
		$tmpStringStart = substr($sTexte,0,300);
		$tmpStringEnd = substr($sTexte,301,strlen($sTexte));
		$sLastSpace = strrpos(' ',$tmpStringStart);
		$sString = substr($sTexte,0,$sLastSpace);
		$sTexte = substr($sTexte,$sLastSpace,strlen($sTexte));
		array_push($aTexte,$sString);
	}
	echo"<pre>";print_r($aTexte);echo"</pre>";die();
  foreach ($aTexte as $k=>$string) { //compute width
    $width=max($width,strlen($string));
  }

  // Create image width dependant on width of the string
  $width  = imagefontwidth($font_size)*$width;
  // Set height to that of the font
  $height = imagefontheight($font_size)*count($ts);
  $el=imagefontheight($font_size);
  $em=imagefontwidth($font_size);
  // Create the image pallette
  $img = imagecreatetruecolor($width,$height);
  // Dark red background
  $bg = imagecolorallocate($img, 0xAA, 0x00, 0x00);
  imagefilledrectangle($img, 0, 0,$width ,$height , $bg);
  // White font color
  $color = imagecolorallocate($img, 255, 255, 255);

  foreach ($aTexte as $k=>$string) {
    // Length of the string
    $len = strlen($string);
    // Y-coordinate of character, X changes, Y is static
    $ypos = 0;
    // Loop through the string
    for($i=0;$i<$len;$i++){
      // Position of the character horizontally
      $xpos = $i * $em;
      $ypos = $k * $el;
      // Draw character
      imagechar($img, $font_size, $xpos, $ypos, $string, $color);
      // Remove character from string
      $string = substr($string, 1);      
    }
  }
	/*$browser = new COM("IEWindows.Application") or die("Impossible d'instancier l'application InternetExplorer");
	$handle = $browser->HWND;
	$browser->Visible = true;
	$browser->Navigate("http://www.jlp-immo.com/annonce/1241234");
	/* Still working? */
	/*while ($browser->Busy) {
		com_message_pump(4000);
	}
	$im = imagegrabwindow($handle, 0);
	$browser->Quit();
	imagejpeg($im,"externe/test-screen.jpeg");
	imagedestroy($im);*/
	//header ('Content-Type: image/png');
	imagepng($img);
	imagedestroy($img);
	}
	private function finalisePasserelle(){
		$oPass = PASSERELLE::SELECT()->WHERE('passerelle_date',date('d-m-Y H'))->getOne();
		if(isset($oPass)){
			$iId = $oPass->getPasserelle_id();	
		}
		
		$oPasserelle = new PasserelleRecord();
		$oPasserelle->setpasserelle_log($this->sLog);
		$oPasserelle->setpasserelle_nbannonceajouter($this->iNbAnnonceAjouter);
		$oPasserelle->setpasserelle_nbannoncesuppr($this->iNbAnnonceSuppr);
		$oPasserelle->setpasserelle_nbphotomaj($this->iNbPhotoMaj);
		$oPasserelle->setpasserelle_nbannoncetraite($this->iNbAnnonceTraite);
		$oPasserelle->setpasserelle_statut($this->bStatusPasserelle);
		if(isset($iId)){
			$oPasserelle->setpasserelle_id($iId);
			$oPasserelle->update();
		}else{
			$oPasserelle->setpasserelle_date(date('d-m-Y H'));
			$oPasserelle->insert();
		}
		
	}
	
	private function searchdir ( $path , $maxdepth = -1 , $mode = "FULL" , $d = 0 ){
		if ( substr ( $path , strlen ( $path ) - 1 ) != '/' ) {
			$path .= '/' ;
		}     
		$dirlist = array () ;
		if ( $mode != "FILES" ) {
			$dirlist[] = $path ;
		}
		if ( $handle = opendir ( $path ) ) {
		   while ( false !== ( $file = readdir ( $handle ) ) ) {
			   if ( $file != '.' && $file != '..' ) {
				   $file = $path . $file ;
				   if ( ! is_dir ( $file ) ) { 
					   if ( $mode != "DIRS" )  { 
							$dirlist[] = $file ; 
					   } 
				} elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) ) {
					   $result = searchdir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
					   $dirlist = array_merge ( $dirlist , $result ) ;
				}
		   	}
		}
		closedir ( $handle ) ;
	   }
	   if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
	   return ( $dirlist ) ;
	}
	
	private function ToLog($sString) {
		$handle = fopen(self::LOG_FILE.date("d-m-Y")."log.txt" , "a");
		$time = date("d/M/Y -H:i:s.u");
		$sLog =  $time."\t".$sString."\n";
		$this->sLog .= $sLog;
		fwrite($handle,$sLog);
		fclose($handle);
	}	
	
	private function PrepAnnonces($sFileName) {
		$oZipFile = new UtilFile($sFileName);
		self::ToLog("ZIP : ".$sFileName);
		if ( isset($oZipFile) ) {
			//Nettoyage du dossier de destination
			$aFilesDel = $this->searchdir(self::TARGET_UNZIP_DIR,0,"FILES");
			foreach ($aFilesDel as $k=>$v) {
				unlink($v);
			}
			//Upload du Zip
			if($this->UpAndExtract($oZipFile,self::TARGET_UNZIP_DIR)){
				self::ToLog("Extraction du fichier ".$sFileName." réussit");
				//$oZipFile->delete();
				$this->bStatusPasserelle = 1;
				return true;
			}else{
				self::ToLog("Erreur lors de l'extraction du fichier ".$sFileName);
				$this->bStatusPasserelle = 0;
				return false;
			}
		}	
	}
	
	private function UpAndExtract($oFichier,$sDir) {
		$sZipName = $oFichier->getFileName();
		//$oFichier->copy($sDir);
		$oZip = new ZipArchive();
		$oZip->open($sZipName);
		$bFile = $oZip->extractTo($sDir);
		$oZip->close();
		$aFilesOrig = $this->searchdir($sDir,0,"FILES");
		$i = 0;
		foreach ($aFilesOrig as $k=>$v) {
			list($width, $height, $type, $attr) = getimagesize($v);
			if($type==2) {
				$aFiles[$i] = $v;
				$i++;
			}
		}
		if(sizeof($aFiles>0)){
			if(file_exists($sDir.$sZipName)){
				if(unlink($sDir.$sZipName)) {
					return true;
				}
			}else{
				return true;
			}
		}
		return $bFile;
	}
}
?>