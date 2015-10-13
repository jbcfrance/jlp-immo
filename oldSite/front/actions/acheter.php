<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class acheterAction
 * Description		: Cette class génére le module de recherche de biens
 * @templates dir 	: templates/acheter
 *
*/
class acheterAction extends acheterAction_BASE {
	/**
	 * Default action for acheter
	 *	date	: 2009-09-29 14:10:18
	 */
	public function doDefault ($aParams=array()) {
		$this->addJavaScript(SCRIPTS.'acheter.js');
		$this->addMeta('description',utf8_encode('JLP-IMMO - Immobilier Saint Gervais, Le Fayet, Passy et Sallanches, Agence immobilière Saint-Gervais, Le Fayet, Passy, Sallanches. Achat, Vente, Gestion, Conseils, Estimations'));
		Xtremplate::$vars['sTitleH1'] = utf8_encode("Acheter à Saint-Gervais, Sallanches, Passy");
		$_SESSION["localite"] 				= '';
		$_SESSION["typeDeBien_maison"] 		= '';
		$_SESSION["typeDeBien_appartement"] = '';
		$_SESSION["typeDeBien_bureau"] 		= '';
		$_SESSION["typeDeBien_terrain"] 	= '';
		$_SESSION["typeDeBien_immeuble"] 	= '';
		$_SESSION["typeDeBien_commerce"] 	= '';
		$_SESSION["typeDeBien_garage"] 		= '';
		$_SESSION["montantMin"]				= '';
		$_SESSION["montantMax"] 			= '';
		$_SESSION["surface"] 				= '';
		$_SESSION["surfaceterrain"] 		= '';
		$_SESSION["pieces"] 				= '';
		$_SESSION["chambres"] 				= '';
		$_SESSION["salledeau"] 				= '';
		$_SESSION["exposition"] 			= '';
		$_SESSION["nbgarages"] 				= '';
		$_SESSION["piscine"] 				= '';
		$aTypeBien = array();
		$aLocalite = array();
		$oQuery = ANNONCE::SELECT('typeBien','VillePublique')->exec();
		foreach($oQuery as $vField) {
			if( !in_array($vField->getTypeBien(),$aTypeBien))
				$aTypeBien[] = $vField->getTypeBien();
			if( !in_array($vField->getVillePublique(),$aLocalite))
				$aLocalite[] = $vField->getVillePublique();
		}
			$this['aTypeBien'] = $aTypeBien;
			$aArrayInit = $aLocalite;
			$aArrayFinal = array();
			foreach($aArrayInit as $k=>$v){
				$sResult = strtolower($v);
				$sResult = ucwords($sResult);
				foreach (array('-', '\'') as $delimiter) {
				  if (strpos($sResult, $delimiter)!==false) {
					$sResult =implode($delimiter, array_map('ucfirst', explode($delimiter, $sResult)));
				  }
				}
				if( !in_array($sResult,$aArrayFinal)){
						$aArrayFinal[] = $sResult;	
				}
			}
			sort($aArrayFinal);
			$aLocalite = array();
			$aLocalite = $aArrayFinal;
			$this['aLocalite'] = $aLocalite;
	}
	
	public function doTraitementresultats ($Params=array()) {
			/*METTRE LES POST EN SESSION*/
		if(params('localite', array(POST))!= '')				$_SESSION["localite"] 				= params('localite', array(POST));
		if(params('typeDeBien_maison', array(POST))!= '')		$_SESSION["typeDeBien_maison"] 		= params('typeDeBien_maison', array(POST));
		if(params('typeDeBien_appartement', array(POST))!= '')	$_SESSION["typeDeBien_appartement"] = params('typeDeBien_appartement', array(POST));
		if(params('typeDeBien_bureau', array(POST))!= '')		$_SESSION["typeDeBien_bureau"] 		= params('typeDeBien_bureau', array(POST));
		if(params('typeDeBien_terrain', array(POST))!= '')		$_SESSION["typeDeBien_terrain"] 	= params('typeDeBien_terrain', array(POST));
		if(params('typeDeBien_immeuble', array(POST))!= '')		$_SESSION["typeDeBien_immeuble"] 	= params('typeDeBien_immeuble', array(POST));
		if(params('typeDeBien_commerce', array(POST))!= '')		$_SESSION["typeDeBien_commerce"] 	= params('typeDeBien_commerce', array(POST));
		if(params('typeDeBien_garage', array(POST))!= '')		$_SESSION["typeDeBien_garage"] 		= params('typeDeBien_garage', array(POST));
		if(params('montantMin', array(POST))!= '')				$_SESSION["montantMin"]				= params('montantMin', array(POST));
		if(params('montantMax', array(POST))!= '')				$_SESSION["montantMax"] 			= params('montantMax', array(POST));
		if(params('surface', array(POST))!= '')					$_SESSION["surface"] 				= params('surface', array(POST));
		if(params('surfaceterrain', array(POST))!= '')			$_SESSION["surfaceterrain"] 		= params('surfaceterrain', array(POST));
		if(params('pieces', array(POST))!= '')					$_SESSION["pieces"] 				= params('pieces', array(POST));
		if(params('chambres', array(POST))!= '')				$_SESSION["chambres"] 				= params('chambres', array(POST));
		if(params('salledeau', array(POST))!= '')				$_SESSION["salledeau"] 				= params('salledeau', array(POST));
		if(params('exposition', array(POST))!= '')				$_SESSION["exposition"] 			= params('exposition', array(POST));
		if(params('nbgarages', array(POST))!= '')				$_SESSION["nbgarages"] 				= params('nbgarages', array(POST));
		if(params('piscine', array(POST))!= '')					$_SESSION["piscine"] 				= params('piscine', array(POST));
		

		header('Location: /acheter/resultatSearchAnnonce');
	
	}
	
	public function doTraitementresultatsrapide ($Params=array()) {
		$_SESSION["localite_fast"] 				= '';
		$_SESSION["typeDeBien_fast"] 			= '';
		$_SESSION["montant_fast"]				= '';
		if(params('localite_fast', array(POST))!= '')				$_SESSION["localite_fast"] 				= params('localite_fast', array(POST));
		if(params('typeDeBien_fast', array(POST))!= '')				$_SESSION["typeDeBien_fast"] 			= params('typeDeBien_fast', array(POST));
		if(params('montant_fast', array(POST))!= '')					$_SESSION["montant_fast"]				= params('montant_fast', array(POST));
		header('Location: /acheter/resultatSearchRapide');	
	}
	
	public function doResultatSearchAnnonce ($aParams=array()) {
			$aTypeBien = array();
			if($_SESSION['typeDeBien_maison']!='')			$aTypeBien[] = $_SESSION['typeDeBien_maison'];
			if($_SESSION['typeDeBien_appartement']!= '')	$aTypeBien[] = $_SESSION['typeDeBien_appartement'];
			if($_SESSION['typeDeBien_bureau']!= '')			$aTypeBien[] = $_SESSION['typeDeBien_bureau'];
			if($_SESSION['typeDeBien_terrain']!= '')		$aTypeBien[] = $_SESSION['typeDeBien_terrain'];
			if($_SESSION['typeDeBien_immeuble']!= '')		$aTypeBien[] = $_SESSION['typeDeBien_immeuble'];
			if($_SESSION['typeDeBien_commerce']!= '')		$aTypeBien[] = $_SESSION['typeDeBien_commerce'];
			if($_SESSION['typeDeBien_garage']!= '')			$aTypeBien[] = $_SESSION['typeDeBien_garage'];
		$oRequete = ANNONCE::SELECT(
						'annonce_id',
						'reference',
						'typeBien',
						'villeAAfficher',
						'surface',
						'montant',
						'surfaceTerrain',
						'pieces',
						'chambres',
						'nbSallesDEau',
						'nbGarages',
						'piscine',
						'exposition',
						'coupDeCoeur',
						'texte',
						'photoThumb'
					)
					->WHERE('1', '1');

		if($_SESSION['localite'] != '') {
			if(!empty($aTypeBien)){
				$oRequete	->_AND_('villePublique',$_SESSION['localite'])
							->_AND_('typeBien','IN',$aTypeBien);
			}else{
				$oRequete	->_AND_('villePublique',$_SESSION['localite']);
			}
		}else{
			if(!empty($aTypeBien)){
				$oRequete	->_AND_('typeBien','IN',$aTypeBien);
			}
		}
		
		//Budget
		if ($_SESSION['montantMin']!=0) {
			$oRequete->_AND_('montant', '>=', $_SESSION['montantMin'] );
		}
		if ($_SESSION['montantMax']!=0) {
			$oRequete->_AND_('montant', '<=', $_SESSION['montantMax'] );
		}
		
		//Surface
		if($_SESSION['surface']!=''){
			$aSurfaces = explode('-',$_SESSION['surface']);
			$iSurfaceMin = $aArray[0];
			$iSurfaceMax = $aArray[1];
			
			$oRequete->_AND_('surface', '>=', $iSurfaceMin);
			if ($iSurfaceMax != '+' ) {
				$oRequete->_AND_('surface', '<=', $iSurfaceMax);
			}
		}
		
		//Surface Terrain
		if($_SESSION['surfaceterrain']!=''){
			$aArray = explode('-',$_SESSION['surfaceterrain']) ;
			$iSurfaceTerrainMin = $aArray[0];
			$iSurfaceTerrainMax = $aArray[1];
			
			$oRequete->_AND_('surfaceterrain', '>=', $iSurfaceTerrainMin);
			if ($iSurfaceMax != '+' ) {
				$oRequete->_AND_('surfaceterrain', '<=', $iSurfaceTerrainMax);
			}
		}
		
		//Pièces
		if($_SESSION['pieces']!=''){
			$iNbr = $_SESSION['pieces'];
			if($iNbr == '5+') {
				$oRequete->_AND_('pieces', '>=', 5);
			}else{
				$oRequete->_AND_('pieces', '=', $iNbr);
			}
		}
		
		//Pièces
		if($_SESSION['chambres']!=''){
			$iNbr = $_SESSION['chambres'];
			if($iNbr == '5+') {
				$oRequete->_AND_('chambres', '>=', 5);
			}else{
				$oRequete->_AND_('chambres', '=', $iNbr);
			}
		}
		//Pièces
		if($_SESSION['salledeau']!=''){
			$iNbr = $_SESSION['salledeau'];
			if($iNbr == '5+') {
				$oRequete->_AND_('salledeau', '>=', 5);
			}else{
				$oRequete->_AND_('salledeau', '=', $iNbr);
			}
		}
		
		//Exposition
		if($_SESSION['exposition']!=''){
			$oRequete->_AND_('exposition', $_SESSION['exposition']);
		}
		
		//Garages
		if($_SESSION['nbgarages']!=''){
			$iNbr = $_SESSION['nbgarages'];
			if($iNbr == '5+') {
				$oRequete->_AND_('nbgarages', '>=', 5);
			}else{
				$oRequete->_AND_('nbgarages', '=', $iNbr);
			}
		}
		
		//Piscine
		if($_SESSION['piscine']!='indif') {
			if($_SESSION['piscine']=='oui')
				$oRequete->_AND_('piscine', $_SESSION['piscine']);
			elseif ($_SESSION['piscine']=='non')
				$oRequete->_AND_('piscine', $_SESSION['piscine']);
		}
		
/*		echo'<pre>';print_r($oRequete->get());echo'</pre>';
		die;*/
		$aAnnoncesProg = array();
		 $oQuery = PROGRAMME_ANNONCE::SELECT('annonce_id')->exec();
		 foreach($oQuery as $kQry=>$vQry){
			array_push($aAnnoncesProg,$vQry->getannonce_id());
		}
		Xtremplate::$vars['aAnnoncesProg'] = $aAnnoncesProg;
		
		 
		//$sIdsAnnonces = substr($sIdsAnnonces, 0, -1);
		//echo"<pre>";print_r($aIdsAnnonces);echo"</pre>";
			$iPage = isset($aParams['page'])?$aParams['page']:1;
			$sOrder = isset($aParams['order'])?$aParams['order']:'reference';
			$oQuery = $oRequete;
			//echo"<pre>";print_r($oQuery);echo"</pre>";
			$this->Pager = new Pager( $oQuery, $iPage, 10000 );
			$this->Pager->setTitles(array(
				'reference'				=>  'Ref',
				'typeBien'				=>  'Type de bien',
				'villePublique'		=>  'Ville',
				'montant'				=>	'Montant',
				'coupDeCoeur'			=>	'Coup de coeur',
				'texte'					=>	'Texte',
				'photoThumb'			=>	'Photo',
				
			));
			$this->Pager->setDefaultOrder('reference');
			$this->Pager->setTplURL(TEMPLATE_PATH.'acheter/pagerAnnonces.htm');
			$this->Pager->init();
			
	}
	
	public function doResultatSearchRapide ($aParams=array()) {
		$this->addMeta('description',utf8_encode('JLP-IMMO - Immobilier Saint Gervais, Le Fayet, Passy et Sallanches, Agence immobilière Saint-Gervais, Le Fayet, Passy, Sallanches. Achat, Vente, Gestion, Conseils, Estimations'));
		/*METTRE LES POST EN SESSION*/		
		$aTypeBienFast = array();
		if($_SESSION['typeDeBien_fast']!= '')			$aTypeBienFast[] = $_SESSION['typeDeBien_fast'];
		$oRequete = ANNONCE::SELECT(
						'annonce_id',
						'reference',
						'typeBien',
						'villePublique',
						'surface',
						'montant',
						'coupDeCoeur',
						'texte',
						'photoThumb'
					)
					->WHERE('1', '1');

		if($_SESSION['localite_fast'] != '') {
			if(!empty($aTypeBienFast)){
				$oRequete	->_AND_('villePublique',$_SESSION['localite_fast'])
							->_AND_('typeBien','IN',$aTypeBienFast);
			}else{
				$oRequete	->_AND_('villePublique',$_SESSION['localite_fast']);
			}
		}else{
			if(!empty($aTypeBienFast)){
				$oRequete	->_AND_('typeBien','IN',$aTypeBienFast);
			}
		}
		
		//Budget
		if($_SESSION['montant_fast']!=''){
			$aArray = explode('-',$_SESSION['montant_fast']);
			$iMontantMin = $aArray[0];
			$iMontantMax = $aArray[1];
			
			$oRequete->_AND_('montant', '>=', $iMontantMin);
			if ($iMontantMax != '+' ) {
				$oRequete->_AND_('montant', '<=', $iMontantMax);
			}
		}		
/*		echo "Localite : ".$_SESSION['localite_fast']."<br>";
		echo "Type :".$_SESSION['typeDeBien_fast']."<br>";
		echo'<pre>';print_r($oRequete->get());echo'</pre>';
		die;*/
		$aAnnoncesProg = array();
		 $oQuery = PROGRAMME_ANNONCE::SELECT('annonce_id')->exec();
		 foreach($oQuery as $kQry=>$vQry){
			array_push($aAnnoncesProg,$vQry->getannonce_id());
		}
		Xtremplate::$vars['aAnnoncesProg'] = $aAnnoncesProg;
		//$sIdsAnnonces = substr($sIdsAnnonces, 0, -1);
		//echo"<pre>";print_r($aIdsAnnonces);echo"</pre>";
			$iPage = isset($aParams['page'])?$aParams['page']:1;
			$sOrder = isset($aParams['order'])?$aParams['order']:'reference';
			$oQuery = $oRequete;
			//echo"<pre>";print_r($oQuery);echo"</pre>";
			$this->Pager = new Pager( $oQuery, $iPage, 10000 );
			$this->Pager->setTitles(array(
				'reference'				=>  'Ref',
				'typeBien'				=>  'Type de bien',
				'villePublique'		    =>  'Ville',
				'montant'				=>	'Montant',
				'coupDeCoeur'			=>	'Coup de coeur',
				'texte'					=>	'Texte',
				'photoThumb'			=>	'Photo',
				
			));
			$this->Pager->setDefaultOrder('reference');
			$this->Pager->setTplURL(TEMPLATE_PATH.'acheter/pagerAnnonces.htm');
			$this->Pager->init();
		
	}
}
?>