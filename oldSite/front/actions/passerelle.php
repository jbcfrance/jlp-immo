<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class passerelleAction
 * Description		: Cette class génére la passerrelle de mise à jours du site à partir du zip envoyé par le logiciel Connect'Immo de RodaCom
 * @templates dir 	: templates/passerelle
 *
*/
class passerelleAction extends passerelleAction_BASE {
	
	const	FTP_LOGIN		=	"jlpimmo";
	const	FTP_PASSWORD	=	"JLP_!mm0";
	const	FTP_URL			=	"193.252.178.125";
	const	FTP_PATH		=	"";
	
	const 	LOCAL_PATH = "front/web/import/";
	const 	FLASH_XML_DIR = "front/web/flash/";
	const 	TARGET_UNZIP_DIR = "front/web/import/annonces/";
	const 	LOG_FILE = "front/web/import/log.txt";
	const	ZIP_FILE = "connectimmo.zip";
	
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
		$handle = fopen(self::LOG_FILE , "a");
		$time = date("d/M/Y -H:i:s.u");
		$sLog =  $time."\t".$sString."\n";
		fwrite($handle,$sLog);
		fclose($handle);
	}
	private function UpAndExtract($oFichier,$sDir) {
		$sZipName = $oFichier->getFileName();
		//$oFichier->copy($sDir);
		$oZip = new ZipArchive();
		$oZip->open($sZipName);
		$rFile = $oZip->extractTo($sDir);
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
	}
	
	private function ImportFileFTP($sFileName) {
		$conn = ftp_connect(self::FTP_URL);
		if($conn) {
			if (ftp_login($conn, self::FTP_LOGIN, self::FTP_PASSWORD)) 
			{ 
				if (ftp_get($conn, self::LOCAL_PATH.$sFileName, self::FTP_PATH.$sFileName, FTP_BINARY,0)) {
					self::ToLog("IP :  ".self::FTP_URL);
					self::ToLog("Fichier ".self::ZIP_FILE." téléchargé.");
				} else {
					self::ToLog("Erreur de téléchargement du fichier");
					exit;
				} 
			}else{
				self::ToLog("Erreur d'identification au serveur FTP");
				exit;
			}
		}else{
			self::ToLog("Erreur de connexion au serveur FTP");
			exit;
		}
		ftp_close($conn);
		
		return new UtilFile(linkTo("web/import/".self::ZIP_FILE));
	}	
	private function ImportAnnonces($sFileName) {
		$oZipFile = self::ImportFileFTP($sFileName);
		if ( isset($oZipFile) ) {
			//Nettoyage du dossier de destination
			$aFilesDel = $this->searchdir(self::TARGET_UNZIP_DIR,0,"FILES");
			foreach ($aFilesDel as $k=>$v) {
				unlink($v);
			}
			//Upload du Zip
			if($this->UpAndExtract($oZipFile,self::TARGET_UNZIP_DIR)){
				self::ToLog("Extraction du fichier ".self::ZIP_FILE." réussit");
				$oZipFile->delete();
				return true;
			}else{
				self::ToLog("Erreur lors de l'extraction du fichier ".self::ZIP_FILE);
				return false;
			}
		}	
	}
	
	private function PrepAnnonces($sFileName) {
		$oZipFile = new UtilFile(linkTo($sFileName));
		self::ToLog("ZIP : ".linkTo($sFileName));
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
				return true;
			}else{
				self::ToLog("Erreur lors de l'extraction du fichier ".$sFileName);
				return false;
			}
		}	
	}
	
	public function doDefault ($aParams=array()) {
		$this->setTitle("passerelle");
		$this->setLayout("blankLayout");
		$iNbAnnonceTraite = 0;
		$iNbPhotoMaj = 0;
		$iNbAnnonceSuppr = 0;
		$iNbAnnonceAjouter = 0;
		/*if(!copy(self::ZIP_FILE,"web/import/".self::ZIP_FILE)){
			self::ToLog("Problème de déplacement du fichier zip.");
			exit;
		}else{
			self::ToLog("Fichier zip pret pour passerelle.");
		}*/
		if(!$this->PrepAnnonces(self::ZIP_FILE)) {
			/*STOP FICHIER NON IMPORTER*/
			exit;
		}else{
		self::ToLog("Fichier zip pret pour passerelle.");
		
			/*FICHIER IMPORTER AVEC SUCCES*/
			//	AGENCE::delete()->exec();
			//	NEGOCIATEUR::delete()->exec();
			//	ANNONCE::delete()->exec();

			$oXml = simplexml_load_file(linkTo(self::LOCAL_PATH."/annonces/annonces.xml"));
			/*Passage des annonces en StandBy*/
			ANNONCE::UPDATE()
				->SET('status_annonce')
				->VALUES('standby')
				->exec();
			
			$aAgence 		= array();
			$aNegociateur 	= array();
			$aAnnonce 		= array();
			$aPhoto			= array();
			
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
				$oAgence = new outilAgence($aAgenceInfo["idAgence"]);
				$oAgence->setInfoAgence("array",$aAgenceInfo);
				
				$AgenceAdd = new Agence();
				$AgenceAdd->setAgence_id(				$oAgence->getId());
				$AgenceAdd->setRaisonSocialeAgence(		$oAgence->getField("raisonSocialeAgence"));
				$AgenceAdd->setEnseigneAgence(			$oAgence->getField("enseigneAgence"));
				$AgenceAdd->setAdresseAgence(			$oAgence->getField("adresseAgence"));
				$AgenceAdd->setCodePostalAgence(		$oAgence->getField("codePostalAgence"));
				$AgenceAdd->setVilleAgence(				$oAgence->getField("villeAgence"));
				$AgenceAdd->setPaysAgence(				$oAgence->getField("paysAgence"));
				$AgenceAdd->setTelephoneAgence(			$oAgence->getField("telephoneAgence"));
				$AgenceAdd->setFaxAgence(				$oAgence->getField("faxAgence"));
				$AgenceAdd->setEmailAgence(				$oAgence->getField("emailAgence"));
				$AgenceAdd->setSiteWebAgence(			$oAgence->getField("siteWebAgence"));
				
				$aAgence[ $oAgence->getId() ] = $AgenceAdd;
				
				// Negociateur :
				$oNegociateur = new outil_Negociateur($aNegociateurInfo["idNegociateur"]);
				$oNegociateur->setInfoNegociateur("array",$aNegociateurInfo);
				
				$NegociateurAdd = new Negociateur();
				$NegociateurAdd->setNegociateur_id(			$oNegociateur->getId());
				$NegociateurAdd->setnegociateur_agence_id(	$oAgence->getId());
				$NegociateurAdd->setPrenomNegociateur(		$oNegociateur->getField("prenomNegociateur"));
				$NegociateurAdd->setNomNegociateur(			$oNegociateur->getField("nomNegociateur"));
				$NegociateurAdd->setTelephoneNegociateur(	$oNegociateur->getField("telephoneNegociateur"));
				$NegociateurAdd->setEmailNegociateur(		$oNegociateur->getField("emailNegociateur"));
				
				$aNegociateur[ $oNegociateur->getId() ] = $NegociateurAdd;
				
				// Annonces :
				$oAnnonce = new outil_Annonce($aAnnonceInfo["identifiant"]);
				$oAnnonce->setInfoAnnonce("array",$aAnnonceInfo);
				$AnnonceAdd = new Annonce();
				$AnnonceAdd->setAnnonce_id(							$oAnnonce->getId());
				$AnnonceAdd->setAnnonce_agence_id(					$oAgence->getId());
				$AnnonceAdd->setAnnonce_negociateur_id(				$oNegociateur->getId());
				$AnnonceAdd->setStatus_annonce(						"valid");
				$AnnonceAdd->setreference(							$oAnnonce->getField("reference"));
				$AnnonceAdd->setnumMandat(							$oAnnonce->getField("numMandat"));
				$AnnonceAdd->settypeMandat(							$oAnnonce->getField("typeMandat"));
				$AnnonceAdd->setcategorieOffre(						$oAnnonce->getField("categorieOffre"));
				$AnnonceAdd->settypeBien(							$oAnnonce->getField("typeBien"));
				$AnnonceAdd->setcategorie(							$oAnnonce->getField("categorie"));
				$AnnonceAdd->setdateCreation(						$oAnnonce->getField("dateCreation"));
				$AnnonceAdd->setdateModification(					$oAnnonce->getField("dateModification"));
				$AnnonceAdd->setdateDebutMandat(					$oAnnonce->getField("dateDebutMandat"));
				$AnnonceAdd->setdateEcheanceMandat(					$oAnnonce->getField("dateEcheanceMandat"));
				$AnnonceAdd->setdateDisponibiliteOuLiberation(		$oAnnonce->getField("dateDisponibiliteOuLiberation"));
				$AnnonceAdd->setadresse(							$oAnnonce->getField("adresse"));
				$AnnonceAdd->setcodePostalPublic(					$oAnnonce->getField("codePostalPublic"));
				$AnnonceAdd->setvillePublique(						$oAnnonce->getField("villePublique"));
				$AnnonceAdd->setvilleAAfficher(						$oAnnonce->getField("villeAAfficher"));
				$AnnonceAdd->setpays(								$oAnnonce->getField("pays"));
				$AnnonceAdd->setquartier(							$oAnnonce->getField("quartier"));
				$AnnonceAdd->setenvironnement(						$oAnnonce->getField("environnement"));
				$AnnonceAdd->setproximite(							$oAnnonce->getField("proximite"));
				$AnnonceAdd->settransports(							$oAnnonce->getField("transports"));
				$AnnonceAdd->setmontant(							$oAnnonce->getField("montant"));
				$AnnonceAdd->setcharges(							$oAnnonce->getField("charges"));
				$AnnonceAdd->setloyer(								$oAnnonce->getField("loyer"));
				$AnnonceAdd->setdepotGarantie(						$oAnnonce->getField("depotGarantie"));
				$AnnonceAdd->setfraisDivers(						$oAnnonce->getField("fraisDivers"));
				$AnnonceAdd->setloyerGarage(						$oAnnonce->getField("loyerGarage"));
				$AnnonceAdd->setageTete(							$oAnnonce->getField("ageTete"));
				$AnnonceAdd->settypeRente(							$oAnnonce->getField("typeRente"));
				$AnnonceAdd->settaxeHabitation(						$oAnnonce->getField("taxeHabitation"));
				$AnnonceAdd->settaxeFonciere(						$oAnnonce->getField("taxeFonciere"));
				$AnnonceAdd->setfraisDeNotaireReduits(				$oAnnonce->getField("fraisDeNotaireReduits"));
				$AnnonceAdd->setpieces(								$oAnnonce->getField("pieces"));
				$AnnonceAdd->setchambres(							$oAnnonce->getField("chambres"));
				$AnnonceAdd->setsdb(								$oAnnonce->getField("sdb"));
				$AnnonceAdd->setnbSallesDEau(						$oAnnonce->getField("nbSallesDEau"));
				$AnnonceAdd->setnbWC(								$oAnnonce->getField("nbWC"));
				$AnnonceAdd->setnbParking(							$oAnnonce->getField("nbParking"));
				$AnnonceAdd->setnbGarages(							$oAnnonce->getField("nbGarages"));
				$AnnonceAdd->setniveaux(							$oAnnonce->getField("niveaux"));
				$AnnonceAdd->setnbEtages(							$oAnnonce->getField("nbEtages"));
				$AnnonceAdd->setetage(								$oAnnonce->getField("etage"));
				$AnnonceAdd->setsurface(							$oAnnonce->getField("surface"));
				$AnnonceAdd->setsurfaceCarrezOuHabitable(			$oAnnonce->getField("surfaceCarrezOuHabitable"));
				$AnnonceAdd->setsurfaceTerrain(						$oAnnonce->getField("surfaceTerrain"));
				$AnnonceAdd->setsurfaceSejour(						$oAnnonce->getField("surfaceSejour"));
				$AnnonceAdd->setsurfaceTerrasse(					$oAnnonce->getField("surfaceTerrasse"));
				$AnnonceAdd->setsurfaceBalcon(						$oAnnonce->getField("surfaceBalcon"));
				$AnnonceAdd->setaccesHandicape(						$oAnnonce->getField("accesHandicape"));
				$AnnonceAdd->setalarme(								$oAnnonce->getField("alarme"));
				$AnnonceAdd->setascenseur(							$oAnnonce->getField("ascenseur"));
				$AnnonceAdd->setbalcon(								$oAnnonce->getField("balcon"));
				$AnnonceAdd->setbureau(								$oAnnonce->getField("bureau"));
				$AnnonceAdd->setcave(								$oAnnonce->getField("cave"));
				$AnnonceAdd->setcellier(							$oAnnonce->getField("cellier"));
				$AnnonceAdd->setdependances(						$oAnnonce->getField("dependances"));
				$AnnonceAdd->setdressing(							$oAnnonce->getField("dressing"));
				$AnnonceAdd->setgardien(							$oAnnonce->getField("gardien"));
				$AnnonceAdd->setinterphone(							$oAnnonce->getField("interphone"));
				$AnnonceAdd->setlotissement(						$oAnnonce->getField("lotissement"));
				$AnnonceAdd->setmeuble(								$oAnnonce->getField("meuble"));
				$AnnonceAdd->setmitoyenne(							$oAnnonce->getField("mitoyenne"));
				$AnnonceAdd->setpiscine(							$oAnnonce->getField("piscine"));
				$AnnonceAdd->setterrasse(							$oAnnonce->getField("terrasse"));
				$AnnonceAdd->setanciennete(							$oAnnonce->getField("anciennete"));
				$AnnonceAdd->setanneeConstruction(					$oAnnonce->getField("anneeConstruction"));
				$AnnonceAdd->setexposition(							$oAnnonce->getField("exposition"));
				$AnnonceAdd->settypeChauffage(						$oAnnonce->getField("typeChauffage"));
				$AnnonceAdd->setnatureChauffage(					$oAnnonce->getField("natureChauffage"));
				$AnnonceAdd->setmodeChauffage(						$oAnnonce->getField("modeChauffage"));
				$AnnonceAdd->settypeCuisine(						$oAnnonce->getField("typeCuisine"));
				$AnnonceAdd->setcoupDeCoeur(						$oAnnonce->getField("coupDeCoeur"));
				$AnnonceAdd->settexte(								$oAnnonce->getField("texte"));
				$AnnonceAdd->settexteAnglais(						$oAnnonce->getField("texteAnglais"));
				$AnnonceAdd->seturlVisiteVirtuelle(					$oAnnonce->getField("urlVisiteVirtuelle"));
				$AnnonceAdd->setconsommationenergie(				$oAnnonce->getField("consommationenergie"));
				$AnnonceAdd->setemissionges(						$oAnnonce->getField("emissionges"));
				
				$aAnnonce[ $oAnnonce->getId() ] = $AnnonceAdd;
				$aAnnoncePhotos = array();
				foreach ($oNode->photos->children() as $oPhoto) {
					$aAnnoncePhotos[] = (string)$oPhoto;
				}
				$aPhoto[ $oAnnonce->getId() ] = $aAnnoncePhotos;
			}
			
			/*
			 *	DEBUT AGENCE
			 */
			// Creation des Agences :
			$aAllAgences = AGENCE::SELECT('agence_id')
				->where('agence_id', 'IN', array_keys($aAgence) )
				->exec();
			// Formatage de l'array :
			$aAllAgencesIDs = array();
			foreach ( $aAllAgences as $oAgence ) {
				$aAllAgencesIDs[] = $oAgence->getAgence_id();
			}
			
			// Verifications :
			foreach ( $aAgence as $oAgence ) {
				if ( in_array( $oAgence->getAgence_id(), $aAllAgencesIDs ) ) {
					// update :
					self::ToLog("Agence   ".$oAgence->getAgence_id()." mise à jours");
					$oAgence->update();
				} else {
					// insert :
					self::ToLog("Agence   ".$oAgence->getAgence_id()." ajoutée");
					$oAgence->insert();
				}
			}
			
			/*
			 *	FIN AGENCE
			 */
			 
			 /*
			 *	DEBUT NEGO
			 */
			// Creation des Negociateur :
			$aAllNegociateurs = Negociateur::SELECT('negociateur_id')
				->where('negociateur_id', 'IN', array_keys($aNegociateur) )
				->exec();
			// Formatage de l'array :
			$aAllNegociateursIDs = array();
			foreach ( $aAllNegociateurs as $oNegociateur ) {
				$aAllNegociateursIDs[] = $oNegociateur->getNegociateur_id();
			}
			
			// Verifications :
			foreach ( $aNegociateur as $oNegociateur ) {
				if ( in_array( $oNegociateur->getNegociateur_id(), $aAllNegociateursIDs ) ) {
					// update :
					self::ToLog("Négociateur  ".$oNegociateur->getNegociateur_Id()." mit à jours");
					$oNegociateur->update();
				} else {
					// insert :
					self::ToLog("Négociateur  ".$oNegociateur->getNegociateur_Id()." ajouté");
					$oNegociateur->insert();
				}
			}
			
			/*
			 *	FIN NEGO
			 */
			 
			 
			/*
			 *	DEBUT Annonce
			 */
			// Creation des Annonce :
			$aAllAnnonces = Annonce::SELECT('annonce_id')
				->where('annonce_id', 'IN', array_keys($aAnnonce) )
				->exec();
			// Formatage de l'array :
			$aAllAnnoncesIDs = array();
			foreach ( $aAllAnnonces as $oAnnonce ) {
				$aAllAnnoncesIDs[] = $oAnnonce->getAnnonce_id();
			}
			$iNbAnnonceTraite = count($aAllAnnoncesIDs);
			// Verifications :
			foreach ( $aAnnonce as $oAnnonce ) {
				if ( in_array( $oAnnonce->getAnnonce_id(), $aAllAnnoncesIDs ) ) {
					// update :
					$oPhotoPrincipal = new outil_Photo( $aPhoto[ $oAnnonce->getAnnonce_id() ][0] );
					$oMd5PhotoOrig = ANNONCE::SELECT('photoOrigMd5')->WHERE('annonce_id',$oAnnonce->getAnnonce_id())->exec();
					
					foreach($oMd5PhotoOrig as $vMd5PhotoOrig) {
						if(md5_file(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName()) != $vMd5PhotoOrig->getPhotoOrigMd5()){
							$bVerifTraitement = false;
							if($oPhotoPrincipal->createCoeurFile("Coeur".$oAnnonce->getAnnonce_id())) {
								$bVerifTraitement = true;	
							}
							if($oPhotoPrincipal->createMediumFile("Medium".$oAnnonce->getAnnonce_id())) {
								$bVerifTraitement = true;	
							}
							if($oPhotoPrincipal->createFlashFile("Flash".$oAnnonce->getAnnonce_id())) {
								$bVerifTraitement = true;	
							}
							if($oPhotoPrincipal->createThumbFile("Thumb".$oAnnonce->getAnnonce_id())) {
								$bVerifTraitement = true;	
							}
							if($bVerifTraitement){
								self::ToLog("Annonce Ref : ".$oAnnonce->getreference()." : Photo principale traitée");
							}
							/*if($oPhotoPrincipal->createCoeurFile("Coeur".$oAnnonce->getAnnonce_id()) && $oPhotoPrincipal->createMediumFile("Medium".$oAnnonce->getAnnonce_id()) && $oPhotoPrincipal->createFlashFile("Flash".$oAnnonce->getAnnonce_id())&& $oPhotoPrincipal->createThumbFile("Thumb".$oAnnonce->getAnnonce_id())) {
								self::ToLog("Annonce ".$oAnnonce->getAnnonce_id()." : Photo principale traitée");
							}*/
						}else{
							self::ToLog("Annonce Ref : ".$oAnnonce->getreference()." Fichier identique");
						}
					}
					
					$ListingOrig = array();
					
					foreach ($aPhoto[ $oAnnonce->getAnnonce_id() ] as $vPhoto) {
						$oPhoto = new outil_Photo($vPhoto);
							$ListingOrig[] = (string)$oPhoto->getName();			
					}
					$oAnnonce->setPhotoCoeur('coeur/Coeur'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setPhotoMedium('medium/Medium'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setListePhotoOrig(''.implode("|",$ListingOrig).'');
					$oAnnonce->setPhotoThumb('thumb/Thumb'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->update();
					
					self::ToLog("Annonce Ref : ".$oAnnonce->getreference()." mise à jours");
				} else {
					// insert :
					$oPhotoPrincipal = new outil_Photo( $aPhoto[ $oAnnonce->getAnnonce_id() ][0] );
					if(file_exists(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName())){
						$oAnnonce->setPhotoOrigMd5(md5_file(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName()));
					}else{
						self::ToLog(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName());
						self::ToLog("Erreure md5");
					}
					
					$bVerifTraitement = false;
					if($oPhotoPrincipal->createCoeurFile("Coeur".$oAnnonce->getAnnonce_id())) {
						$bVerifTraitement = true;	
					}else{
						$bVerifTraitement = true;
					}
					if($oPhotoPrincipal->createMediumFile("Medium".$oAnnonce->getAnnonce_id())) {
						$bVerifTraitement = true;	
					}else{
						$bVerifTraitement = true;
					}
					if($oPhotoPrincipal->createFlashFile("Flash".$oAnnonce->getAnnonce_id())) {
						$bVerifTraitement = true;	
					}else{
						$bVerifTraitement = true;
					}
					if($oPhotoPrincipal->createThumbFile("Thumb".$oAnnonce->getAnnonce_id())) {
						$bVerifTraitement = true;	
					}else{
						$bVerifTraitement = true;
					}
					if($bVerifTraitement){
						self::ToLog("Annonce Ref : ".$oAnnonce->getreference()." : Photo principale traitée");
						$iNbPhotoMaj++;
					}else{
						self::ToLog("Annonce Ref : ".$oAnnonce->getreference()." : Erreure de traitement");
					}
					
					$ListingOrig = array();
					
					foreach ($aPhoto[ $oAnnonce->getAnnonce_id() ] as $vPhoto) {
						$oPhoto = new outil_Photo($vPhoto);
							$ListingOrig[] = (string)$oPhoto->getName();				
					}
					
					$oAnnonce->setPhotoCoeur('coeur/Coeur'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setPhotoMedium('medium/Medium'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setListePhotoOrig(''.implode("|",$ListingOrig).'');
					$oAnnonce->setPhotoThumb('thumb/Thumb'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->insert();
					$iNbAnnonceAjouter++;
					self::ToLog("Annonce  Ref : ".$oAnnonce->getreference()." ajoutée");
				}
			}
			self::ToLog("FIN ANNONCE");
			$oStats = new Site();
			$oStats->setSite_variable('passerelle_ajouter');
			$oStats->setSite_valeur($iNbAnnonceAjouter);
			$oStats->update();
			$oStats = new Site();
			$oStats->setSite_variable('passerelle_suppr');
			$oStats->setSite_valeur($iNbAnnonceSuppr);
			$oStats->update();
			$oStats = new Site();
			$oStats->setSite_variable('passerelle_traiter');
			$oStats->setSite_valeur($iNbAnnonceTraite);
			$oStats->update();
			$oStats = new Site();
			$oStats->setSite_variable('passerelle_photomaj');
			$oStats->setSite_valeur($iNbPhotoMaj);
			$oStats->update();
			/*
			 *	FIN Annonce
			 */
			 $AnnoncesStandby = ANNONCE::SELECT()->WHERE('status_annonce','standby')->exec();
			foreach($AnnoncesStandby as $kAnnoncesStandby=>$vAnnoncesStandby) {
				if(is_file("web/images/annonces/coeur/Coeur".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/coeur/Coeur".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("web/images/annonces/medium/Medium".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/medium/Medium".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("web/images/annonces/flash/Flash".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/flash/Flash".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("web/images/annonces/thumb/Thumb".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/thumb/Thumb".$vAnnoncesStandby->getAnnonce_id().".jpg");
				$tmpArrayOrig= explode("|",$vAnnoncesStandby->getlistePhotoOrig());
				foreach($tmpArrayOrig as $ktmpArrayOrig=>$vtmpArrayOrig){
					if(is_file("web/import/annonces/".$vtmpArrayOrig))
					unlink("web/import/annonces/".$vtmpArrayOrig);
				}
				$iNbAnnonceSuppr++;
				self::ToLog("Annonce Ref : ".$vAnnoncesStandby->getreference()." supprimée");
				ANNONCE::delete()->WHERE('annonce_id',$vAnnoncesStandby->getAnnonce_id())->exec();
			}
			
			$oTrad=new Traduction();
			$sXml = "<icons></icons>";
			$oXmlFR = new SimpleXMLElement($sXml);
			$oXmlEN = new SimpleXMLElement($sXml);
			$oQuery = ANNONCE::SELECT()->WHERE('coupdecoeur','oui')->exec();
			foreach($oQuery as $kQuery=>$vQuery) {
				$oXmlFR->addChild("icon");
					$oXmlFR->icon[$kQuery]->addAttribute("image","web/images/flash/Flash".$vQuery->getannonce_id().".jpg");
					$oXmlFR->icon[$kQuery]->addAttribute("titre",$oTrad->getFr(strtolower($vQuery->gettypeBien()))." ".ucfirst($vQuery->getvillePublique()));
					$oXmlFR->icon[$kQuery]->addAttribute("content",$vQuery->gettexte());
					$oXmlFR->icon[$kQuery]->addAttribute("url","http://www.jlp-immo.com/annonce/".$vQuery->getannonce_id());
					$oXmlFR->icon[$kQuery]->addAttribute("prix",$vQuery->getmontant()." €");
				$oXmlEN->addChild("icon");
					$oXmlEN->icon[$kQuery]->addAttribute("image","web/images/flash/Flash".$vQuery->getannonce_id().".jpg");
					$oXmlEN->icon[$kQuery]->addAttribute("titre",$oTrad->getEn(strtolower($vQuery->gettypeBien()))." ".ucfirst($vQuery->getvillePublique()));
					$oXmlEN->icon[$kQuery]->addAttribute("content",$vQuery->gettextAnglais());
					$oXmlEN->icon[$kQuery]->addAttribute("url","http://www.jlp-immo.com/annonce/".$vQuery->getannonce_id());
					$oXmlEN->icon[$kQuery]->addAttribute("prix",$vQuery->getmontant()." €");
			}
			$FileFR = "web/flash/frIcons.xml";
			$FileEN = "web/flash/enIcons.xml";
			
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
					self::ToLog("Mise à jours du Carroussel version française réussit");
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
					self::ToLog("Mise à jours du Carroussel version anglaise réussit");
				}else{
					self::ToLog("Le fichier XML Anglais n'est pas accèssible en écriture");
				}
			}
			
		}
			
	}
	
	public function doTraitementXML ($aParams=array()) {
	
	$oTrad=new Traduction();
			$sXml = "<icons></icons>";
			$oXmlFR = new SimpleXMLElement($sXml);
			$oXmlEN = new SimpleXMLElement($sXml);
			$oQuery = ANNONCE::SELECT()->WHERE('coupdecoeur','oui')->exec();
			foreach($oQuery as $kQuery=>$vQuery) {
				$oXmlFR->addChild("icon");
					$oXmlFR->icon[$kQuery]->addAttribute("image","web/images/flash/Flash".$vQuery->getannonce_id().".jpg");
					$oXmlFR->icon[$kQuery]->addAttribute("titre",$oTrad->getFr(strtolower($vQuery->gettypeBien()))." ".ucfirst($vQuery->getvillePublique()));
					$oXmlFR->icon[$kQuery]->addAttribute("content",$vQuery->gettexte());
					$oXmlFR->icon[$kQuery]->addAttribute("url","http://www.jlp-immo.com/annonce/".$vQuery->getannonce_id());
					$oXmlFR->icon[$kQuery]->addAttribute("prix",$vQuery->getmontant()." €");
				$oXmlEN->addChild("icon");
					$oXmlEN->icon[$kQuery]->addAttribute("image","web/images/flash/Flash".$vQuery->getannonce_id().".jpg");
					$oXmlEN->icon[$kQuery]->addAttribute("titre",$oTrad->getEn(strtolower($vQuery->gettypeBien()))." ".ucfirst($vQuery->getvillePublique()));
					$oXmlEN->icon[$kQuery]->addAttribute("content",$vQuery->gettextAnglais());
					$oXmlEN->icon[$kQuery]->addAttribute("url","http://www.jlp-immo.com/annonce/".$vQuery->getannonce_id());
					$oXmlEN->icon[$kQuery]->addAttribute("prix",$vQuery->getmontant()." €");
			}
			$FileFR = "web/flash/frIcons.xml";
			$FileEN = "web/flash/enIcons.xml";
			
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
					self::ToLog("Mise à jours du Carroussel version française réussit");
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
					self::ToLog("Mise à jours du Carroussel version anglaise réussit");
				}else{
					self::ToLog("Le fichier XML Anglais n'est pas accèssible en écriture");
				}
			}

	}
	
	public function doTest ($aParams=array()) {
		$this->setTitle("passerelle");
		$this->setLayout("blankLayout");
		//self::ToLog("TIME".ini_get('max_execution_time'));
		if(!$this->PrepAnnonces(self::ZIP_FILE)) {
			echo"Erreur";
			exit;
		}else{
			/*FICHIER IMPORTER AVEC SUCCES*/
			//	AGENCE::delete()->exec();
			//	NEGOCIATEUR::delete()->exec();
			//	ANNONCE::delete()->exec();
			echo"Extract ok";
		
		
			$oXml = simplexml_load_file(linkTo(self::LOCAL_PATH."/annonces/annonces.xml"));
			/*Passage des annonces en StandBy*/
			ANNONCE::UPDATE()
				->SET('status_annonce')
				->VALUES('standby')
				->exec();
			
			$aAgence 		= array();
			$aNegociateur 	= array();
			$aAnnonce 		= array();
			$aPhoto			= array();
			
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
				$oAgence = new outilAgence($aAgenceInfo["idAgence"]);
				$oAgence->setInfoAgence("array",$aAgenceInfo);
				
				$AgenceAdd = new Agence();
				$AgenceAdd->setAgence_id(				$oAgence->getId());
				$AgenceAdd->setRaisonSocialeAgence(		$oAgence->getField("raisonSocialeAgence"));
				$AgenceAdd->setEnseigneAgence(			$oAgence->getField("enseigneAgence"));
				$AgenceAdd->setAdresseAgence(			$oAgence->getField("adresseAgence"));
				$AgenceAdd->setCodePostalAgence(		$oAgence->getField("codePostalAgence"));
				$AgenceAdd->setVilleAgence(				$oAgence->getField("villeAgence"));
				$AgenceAdd->setPaysAgence(				$oAgence->getField("paysAgence"));
				$AgenceAdd->setTelephoneAgence(			$oAgence->getField("telephoneAgence"));
				$AgenceAdd->setFaxAgence(				$oAgence->getField("faxAgence"));
				$AgenceAdd->setEmailAgence(				$oAgence->getField("emailAgence"));
				$AgenceAdd->setSiteWebAgence(			$oAgence->getField("siteWebAgence"));
				
				$aAgence[ $oAgence->getId() ] = $AgenceAdd;
				
				// Negociateur :
				$oNegociateur = new outil_Negociateur($aNegociateurInfo["idNegociateur"]);
				$oNegociateur->setInfoNegociateur("array",$aNegociateurInfo);
				
				$NegociateurAdd = new Negociateur();
				$NegociateurAdd->setNegociateur_id(			$oNegociateur->getId());
				$NegociateurAdd->setnegociateur_agence_id(	$oAgence->getId());
				$NegociateurAdd->setPrenomNegociateur(		$oNegociateur->getField("prenomNegociateur"));
				$NegociateurAdd->setNomNegociateur(			$oNegociateur->getField("nomNegociateur"));
				$NegociateurAdd->setTelephoneNegociateur(	$oNegociateur->getField("telephoneNegociateur"));
				$NegociateurAdd->setEmailNegociateur(		$oNegociateur->getField("emailNegociateur"));
				
				$aNegociateur[ $oNegociateur->getId() ] = $NegociateurAdd;
				
				// Annonces :
				$oAnnonce = new outil_Annonce($aAnnonceInfo["identifiant"]);
				$oAnnonce->setInfoAnnonce("array",$aAnnonceInfo);
				$AnnonceAdd = new Annonce();
				$AnnonceAdd->setAnnonce_id(							$oAnnonce->getId());
				$AnnonceAdd->setAnnonce_agence_id(					$oAgence->getId());
				$AnnonceAdd->setAnnonce_negociateur_id(				$oNegociateur->getId());
				$AnnonceAdd->setStatus_annonce(						"valid");
				$AnnonceAdd->setreference(							$oAnnonce->getField("reference"));
				$AnnonceAdd->setnumMandat(							$oAnnonce->getField("numMandat"));
				$AnnonceAdd->settypeMandat(							$oAnnonce->getField("typeMandat"));
				$AnnonceAdd->setcategorieOffre(						$oAnnonce->getField("categorieOffre"));
				$AnnonceAdd->settypeBien(							$oAnnonce->getField("typeBien"));
				$AnnonceAdd->setcategorie(							$oAnnonce->getField("categorie"));
				$AnnonceAdd->setdateCreation(						$oAnnonce->getField("dateCreation"));
				$AnnonceAdd->setdateModification(					$oAnnonce->getField("dateModification"));
				$AnnonceAdd->setdateDebutMandat(					$oAnnonce->getField("dateDebutMandat"));
				$AnnonceAdd->setdateEcheanceMandat(					$oAnnonce->getField("dateEcheanceMandat"));
				$AnnonceAdd->setdateDisponibiliteOuLiberation(		$oAnnonce->getField("dateDisponibiliteOuLiberation"));
				$AnnonceAdd->setadresse(							$oAnnonce->getField("adresse"));
				$AnnonceAdd->setcodePostalPublic(					$oAnnonce->getField("codePostalPublic"));
				$AnnonceAdd->setvillePublique(						$oAnnonce->getField("villePublique"));
				$AnnonceAdd->setvilleAAfficher(						$oAnnonce->getField("villeAAfficher"));
				$AnnonceAdd->setpays(								$oAnnonce->getField("pays"));
				$AnnonceAdd->setquartier(							$oAnnonce->getField("quartier"));
				$AnnonceAdd->setenvironnement(						$oAnnonce->getField("environnement"));
				$AnnonceAdd->setproximite(							$oAnnonce->getField("proximite"));
				$AnnonceAdd->settransports(							$oAnnonce->getField("transports"));
				$AnnonceAdd->setmontant(							$oAnnonce->getField("montant"));
				$AnnonceAdd->setcharges(							$oAnnonce->getField("charges"));
				$AnnonceAdd->setloyer(								$oAnnonce->getField("loyer"));
				$AnnonceAdd->setdepotGarantie(						$oAnnonce->getField("depotGarantie"));
				$AnnonceAdd->setfraisDivers(						$oAnnonce->getField("fraisDivers"));
				$AnnonceAdd->setloyerGarage(						$oAnnonce->getField("loyerGarage"));
				$AnnonceAdd->setageTete(							$oAnnonce->getField("ageTete"));
				$AnnonceAdd->settypeRente(							$oAnnonce->getField("typeRente"));
				$AnnonceAdd->settaxeHabitation(						$oAnnonce->getField("taxeHabitation"));
				$AnnonceAdd->settaxeFonciere(						$oAnnonce->getField("taxeFonciere"));
				$AnnonceAdd->setfraisDeNotaireReduits(				$oAnnonce->getField("fraisDeNotaireReduits"));
				$AnnonceAdd->setpieces(								$oAnnonce->getField("pieces"));
				$AnnonceAdd->setchambres(							$oAnnonce->getField("chambres"));
				$AnnonceAdd->setsdb(								$oAnnonce->getField("sdb"));
				$AnnonceAdd->setnbSallesDEau(						$oAnnonce->getField("nbSallesDEau"));
				$AnnonceAdd->setnbWC(								$oAnnonce->getField("nbWC"));
				$AnnonceAdd->setnbParking(							$oAnnonce->getField("nbParking"));
				$AnnonceAdd->setnbGarages(							$oAnnonce->getField("nbGarages"));
				$AnnonceAdd->setniveaux(							$oAnnonce->getField("niveaux"));
				$AnnonceAdd->setnbEtages(							$oAnnonce->getField("nbEtages"));
				$AnnonceAdd->setetage(								$oAnnonce->getField("etage"));
				$AnnonceAdd->setsurface(							$oAnnonce->getField("surface"));
				$AnnonceAdd->setsurfaceCarrezOuHabitable(			$oAnnonce->getField("surfaceCarrezOuHabitable"));
				$AnnonceAdd->setsurfaceTerrain(						$oAnnonce->getField("surfaceTerrain"));
				$AnnonceAdd->setsurfaceSejour(						$oAnnonce->getField("surfaceSejour"));
				$AnnonceAdd->setsurfaceTerrasse(					$oAnnonce->getField("surfaceTerrasse"));
				$AnnonceAdd->setsurfaceBalcon(						$oAnnonce->getField("surfaceBalcon"));
				$AnnonceAdd->setaccesHandicape(						$oAnnonce->getField("accesHandicape"));
				$AnnonceAdd->setalarme(								$oAnnonce->getField("alarme"));
				$AnnonceAdd->setascenseur(							$oAnnonce->getField("ascenseur"));
				$AnnonceAdd->setbalcon(								$oAnnonce->getField("balcon"));
				$AnnonceAdd->setbureau(								$oAnnonce->getField("bureau"));
				$AnnonceAdd->setcave(								$oAnnonce->getField("cave"));
				$AnnonceAdd->setcellier(							$oAnnonce->getField("cellier"));
				$AnnonceAdd->setdependances(						$oAnnonce->getField("dependances"));
				$AnnonceAdd->setdressing(							$oAnnonce->getField("dressing"));
				$AnnonceAdd->setgardien(							$oAnnonce->getField("gardien"));
				$AnnonceAdd->setinterphone(							$oAnnonce->getField("interphone"));
				$AnnonceAdd->setlotissement(						$oAnnonce->getField("lotissement"));
				$AnnonceAdd->setmeuble(								$oAnnonce->getField("meuble"));
				$AnnonceAdd->setmitoyenne(							$oAnnonce->getField("mitoyenne"));
				$AnnonceAdd->setpiscine(							$oAnnonce->getField("piscine"));
				$AnnonceAdd->setterrasse(							$oAnnonce->getField("terrasse"));
				$AnnonceAdd->setanciennete(							$oAnnonce->getField("anciennete"));
				$AnnonceAdd->setanneeConstruction(					$oAnnonce->getField("anneeConstruction"));
				$AnnonceAdd->setexposition(							$oAnnonce->getField("exposition"));
				$AnnonceAdd->settypeChauffage(						$oAnnonce->getField("typeChauffage"));
				$AnnonceAdd->setnatureChauffage(					$oAnnonce->getField("natureChauffage"));
				$AnnonceAdd->setmodeChauffage(						$oAnnonce->getField("modeChauffage"));
				$AnnonceAdd->settypeCuisine(						$oAnnonce->getField("typeCuisine"));
				$AnnonceAdd->setcoupDeCoeur(						$oAnnonce->getField("coupDeCoeur"));
				$AnnonceAdd->settexte(								$oAnnonce->getField("texte"));
				$AnnonceAdd->settexteAnglais(						$oAnnonce->getField("texteAnglais"));
				$AnnonceAdd->seturlVisiteVirtuelle(					$oAnnonce->getField("urlVisiteVirtuelle"));
				$AnnonceAdd->setconsommationenergie(				$oAnnonce->getField("consommationenergie"));
				$AnnonceAdd->setemissionges(						$oAnnonce->getField("emissionges"));
				
				$aAnnonce[ $oAnnonce->getId() ] = $AnnonceAdd;
				$aAnnoncePhotos = array();
				foreach ($oNode->photos->children() as $oPhoto) {
					$aAnnoncePhotos[] = (string)$oPhoto;
				}
				$aPhoto[ $oAnnonce->getId() ] = $aAnnoncePhotos;
			}
			
			/*
			 *	DEBUT AGENCE
			 */
			// Creation des Agences :
			$aAllAgences = AGENCE::SELECT('agence_id')
				->where('agence_id', 'IN', array_keys($aAgence) )
				->exec();
			// Formatage de l'array :
			$aAllAgencesIDs = array();
			foreach ( $aAllAgences as $oAgence ) {
				$aAllAgencesIDs[] = $oAgence->getAgence_id();
			}
			
			// Verifications :
			foreach ( $aAgence as $oAgence ) {
				if ( in_array( $oAgence->getAgence_id(), $aAllAgencesIDs ) ) {
					// update :
					self::ToLog("Agence   ".$oAgence->getAgence_id()." mise à jours");
					echo "Agence   ".$oAgence->getAgence_id()." mise à jours<br>";
					//$oAgence->update();
				} else {
					// insert :
					self::ToLog("Agence   ".$oAgence->getAgence_id()." ajoutée");
					echo "Agence   ".$oAgence->getAgence_id()." ajoutée<br>";
					//$oAgence->insert();
				}
			}
			
			/*
			 *	FIN AGENCE
			 */
			 
			 /*
			 *	DEBUT NEGO
			 */
			// Creation des Negociateur :
			$aAllNegociateurs = Negociateur::SELECT('negociateur_id')
				->where('negociateur_id', 'IN', array_keys($aNegociateur) )
				->exec();
			// Formatage de l'array :
			$aAllNegociateursIDs = array();
			foreach ( $aAllNegociateurs as $oNegociateur ) {
				$aAllNegociateursIDs[] = $oNegociateur->getNegociateur_id();
			}
			
			// Verifications :
			foreach ( $aNegociateur as $oNegociateur ) {
				if ( in_array( $oNegociateur->getNegociateur_id(), $aAllNegociateursIDs ) ) {
					// update :
					self::ToLog("Négociateur  ".$oNegociateur->getNegociateur_Id()." mit à jours");
					echo "Négociateur  ".$oNegociateur->getNegociateur_Id()." mit à jours <br>";
					//$oNegociateur->update();
				} else {
					// insert :
					self::ToLog("Négociateur  ".$oNegociateur->getNegociateur_Id()." ajouté");
					echo "Négociateur  ".$oNegociateur->getNegociateur_Id()." ajouté <br>";
					//$oNegociateur->insert();
				}
			}
			
			/*
			 *	FIN NEGO
			 */
			/*
			 *	DEBUT Annonce
			 */
			// Creation des Annonce :
			$aAllAnnonces = Annonce::SELECT('annonce_id')
				->where('annonce_id', 'IN', array_keys($aAnnonce) )
				->exec();
			// Formatage de l'array :
			$aAllAnnoncesIDs = array();
			foreach ( $aAllAnnonces as $oAnnonce ) {
				$aAllAnnoncesIDs[] = $oAnnonce->getAnnonce_id();
			}
			
			// Verifications :
			foreach ( $aAnnonce as $oAnnonce ) {
				if ( in_array( $oAnnonce->getAnnonce_id(), $aAllAnnoncesIDs ) ) {
					// update :
					$oPhotoPrincipal = new outil_Photo( $aPhoto[ $oAnnonce->getAnnonce_id() ][0] );
					$oMd5PhotoOrig = ANNONCE::SELECT('photoOrigMd5')->WHERE('annonce_id',$oAnnonce->getAnnonce_id())->exec();
					
					foreach($oMd5PhotoOrig as $vMd5PhotoOrig) {
						if(md5_file(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName()) != $vMd5PhotoOrig->getPhotoOrigMd5()){
							if($oPhotoPrincipal->createCoeurFile("Coeur".$oAnnonce->getAnnonce_id()) && $oPhotoPrincipal->createMediumFile("Medium".$oAnnonce->getAnnonce_id()) && $oPhotoPrincipal->createFlashFile("Flash".$oAnnonce->getAnnonce_id())&& $oPhotoPrincipal->createThumbFile("Thumb".$oAnnonce->getAnnonce_id())) {
								self::ToLog("Annonce ".$oAnnonce->getAnnonce_id()." : Photo principale traitée");
							}
						}else{
							self::ToLog($oAnnonce->getAnnonce_id()." Fichier identique");
						}
					}
					
					$ListingOrig = array();
					
					foreach ($aPhoto[ $oAnnonce->getAnnonce_id() ] as $vPhoto) {
						$oPhoto = new outil_Photo($vPhoto);
							$ListingOrig[] = (string)$oPhoto->getName();			
					}
					$oAnnonce->setPhotoCoeur('coeur/Coeur'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setPhotoMedium('medium/Medium'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setListePhotoOrig(''.implode("|",$ListingOrig).'');
					$oAnnonce->setPhotoThumb('thumb/Thumb'.$oAnnonce->getAnnonce_Id().'.jpg');
					echo "Annonce ".$oAnnonce->getAnnonce_id()." Update <br>";
					//$oAnnonce->update();
					self::ToLog("Annonce ".$oAnnonce->getAnnonce_Id()." mise à jours");
				} else {
					// insert :
					$oPhotoPrincipal = new outil_Photo( $aPhoto[ $oAnnonce->getAnnonce_id() ][0] );
					if(file_exists(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName())){
						$oAnnonce->setPhotoOrigMd5(md5_file(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName()));
					}else{
						self::ToLog(BASE_PATH.'web/import/annonces/'.$oPhotoPrincipal->getName());
						self::ToLog("Erreure md5");
					}
					
					if($oPhotoPrincipal->createCoeurFile("Coeur".$oAnnonce->getAnnonce_Id()) && $oPhotoPrincipal->createMediumFile("Medium".$oAnnonce->getAnnonce_Id()) && $oPhotoPrincipal->createFlashFile("Flash".$oAnnonce->getAnnonce_Id())&& $oPhotoPrincipal->createThumbFile("Thumb".$oAnnonce->getAnnonce_Id())) {
						echo "Annonce ".$oAnnonce->getAnnonce_Id()." : Photo principale traitée <br>";
						self::ToLog("Annonce ".$oAnnonce->getAnnonce_Id()." : Photo principale traitée");
					}
					
					$ListingOrig = array();
					
					foreach ($aPhoto[ $oAnnonce->getAnnonce_id() ] as $vPhoto) {
						$oPhoto = new outil_Photo($vPhoto);
							$ListingOrig[] = (string)$oPhoto->getName();				
					}
					
					$oAnnonce->setPhotoCoeur('coeur/Coeur'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setPhotoMedium('medium/Medium'.$oAnnonce->getAnnonce_Id().'.jpg');
					$oAnnonce->setListePhotoOrig(''.implode("|",$ListingOrig).'');
					$oAnnonce->setPhotoThumb('thumb/Thumb'.$oAnnonce->getAnnonce_Id().'.jpg');
					echo "Annonce ".$oAnnonce->getAnnonce_id()." Insert <br>";
					//$oAnnonce->insert();
					self::ToLog("Annonce ".$oAnnonce->getAnnonce_Id()." ajoutée");
				}
			}
			
			/*
			 *	FIN Annonce
			 */
			 $AnnoncesStandby = ANNONCE::SELECT()->WHERE('status_annonce','standby')->exec();
			foreach($AnnoncesStandby as $kAnnoncesStandby=>$vAnnoncesStandby) {
				if(is_file("web/images/annonces/coeur/Coeur".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/coeur/Coeur".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("web/images/annonces/medium/Medium".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/medium/Medium".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("web/images/annonces/flash/Flash".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/flash/Flash".$vAnnoncesStandby->getAnnonce_id().".jpg");
				if(is_file("web/images/annonces/thumb/Thumb".$vAnnoncesStandby->getAnnonce_id().".jpg"))unlink("web/images/annonces/thumb/Thumb".$vAnnoncesStandby->getAnnonce_id().".jpg");
				$tmpArrayOrig= explode("|",$vAnnoncesStandby->getlistePhotoOrig());
				foreach($tmpArrayOrig as $ktmpArrayOrig=>$vtmpArrayOrig){
					if(is_file("web/import/annonces/".$vtmpArrayOrig))
					unlink("web/import/annonces/".$vtmpArrayOrig);
				}
				self::ToLog("Annonce ".$vAnnoncesStandby->getAnnonce_id()." supprimée");
				ANNONCE::delete()->WHERE('annonce_id',$vAnnoncesStandby->getAnnonce_id())->exec();
			}
			
			$oTrad=new Traduction();
			$sXml = "<icons></icons>";
			$oXmlFR = new SimpleXMLElement($sXml);
			$oXmlEN = new SimpleXMLElement($sXml);
			$oQuery = ANNONCE::SELECT()->WHERE('coupdecoeur','oui')->exec();
			foreach($oQuery as $kQuery=>$vQuery) {
				$oXmlFR->addChild("icon");
					$oXmlFR->icon[$kQuery]->addAttribute("image","web/images/flash/Flash".$vQuery->getannonce_id().".jpg");
					$oXmlFR->icon[$kQuery]->addAttribute("titre",$oTrad->getFr(strtolower($vQuery->gettypeBien()))." ".ucfirst($vQuery->getvillePublique()));
					$oXmlFR->icon[$kQuery]->addAttribute("content",$vQuery->gettexte());
					$oXmlFR->icon[$kQuery]->addAttribute("url","http://www.jlp-immo.com/annonce/".$vQuery->getannonce_id());
					$oXmlFR->icon[$kQuery]->addAttribute("prix",$vQuery->getmontant()." €");
				$oXmlEN->addChild("icon");
					$oXmlEN->icon[$kQuery]->addAttribute("image","web/images/flash/Flash".$vQuery->getannonce_id().".jpg");
					$oXmlEN->icon[$kQuery]->addAttribute("titre",$oTrad->getEn(strtolower($vQuery->gettypeBien()))." ".ucfirst($vQuery->getvillePublique()));
					$oXmlEN->icon[$kQuery]->addAttribute("content",$vQuery->gettextAnglais());
					$oXmlEN->icon[$kQuery]->addAttribute("url","http://www.jlp-immo.com/annonce/".$vQuery->getannonce_id());
					$oXmlEN->icon[$kQuery]->addAttribute("prix",$vQuery->getmontant()." €");
			}
			$FileFR = "web/flash/frIcons.xml";
			$FileEN = "web/flash/enIcons.xml";
			
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
					self::ToLog("Mise à jours du Carroussel version française réussit");
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
					self::ToLog("Mise à jours du Carroussel version anglaise réussit");
				}else{
					self::ToLog("Le fichier XML Anglais n'est pas accèssible en écriture");
				}
			}
		}
			
	} 
}
?>