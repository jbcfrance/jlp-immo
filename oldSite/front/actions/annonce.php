<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class annonceAction
 * Description		: Cette class génére les fiches des biens
 * @templates dir 	: templates/annonce
 *
*/
class annonceAction extends annonceAction_BASE {
	/**
	 * Default action for coupdecoeur
	 *	date	: 2009-09-29 14:10:04
	 */
	public function doDefault ($aParams=array()) {
	$this->setLayout("layout");
	}
	
	public function doAnnonce ($aParams=array()) {
		$this->setLayout("layout");
		$this->addJavaScript(SCRIPTS.'annonce.js');
		$this->annonce_id = $aParams['annonce_id'];
		$oTrad=new Traduction();
		$oQuery = PROGRAMME_ANNONCE::SELECT('programme_annonce.annonce_id','programme_annonce.programme_id','programme.programme_titre','programme.programme_id','programme.programme_identifiant')->Join_ANNONCE('LEFT')->ON('annonce.annonce_id',ANNONCE::Field('programme_annonce.annonce_id'))->Join_PROGRAMME('LEFT')->ON('programme.programme_id',PROGRAMME::Field('programme_annonce.programme_id'))->WHERE('programme_annonce.annonce_id',$this->annonce_id)->exec();
		if(!empty($oQuery)){
			foreach($oQuery as $kProg=>$vProg){
				$bAnnonceInProg = true;
				Xtremplate::$vars['iProgramme_id'] = $vProg->getProgramme_id();
				Xtremplate::$vars['sProgramme_identifiant'] = $vProg->getProgramme(0)->getProgramme_identifiant();
			}
		}else{
			$bAnnonceInProg = false;
			Xtremplate::$vars['iProgramme_id'] = '';
			Xtremplate::$vars['sProgramme_identifiant'] = '';
		}
		$oQuery = ANNONCE::SELECT()->WHERE('annonce_id',$this->annonce_id)->exec();
		Xtremplate::$vars['annonce']  = $oQuery;
		foreach($oQuery as $kQueyr=>$vQuery) {
			$this->setTitle("Achat ".$oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique()." Ref: ".$vQuery->getreference() );
			if($vQuery->getmontant()!= 0) {
				if(round($vQuery->getmontant())== $vQuery->getmontant()) {
			$sMontant = number_format($vQuery->getmontant(),0,","," ")." €";
				}else{
			$sMontant = number_format($vQuery->getmontant(),2,","," ")." €";
				}
				}else{
			$sMontant = "NC €";
				}
			
			$this->addMeta('description',"Achat ".$oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique()." ".$vQuery->getpieces()." pièces référence ".$vQuery->getreference().". A vendre au prix de ".$sMontant."");
			Xtremplate::$vars['sTitleH1'] = utf8_encode("Achat-Vente ".$oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique());
			Xtremplate::$vars['sTitleH2'] = $oTrad->getTrad($vQuery->gettypeBien())." ".$vQuery->getvillePublique();
			if($bAnnonceInProg){
				Xtremplate::$vars['sClassBandeau'] = 'BandeauProgNeuf';
			}elseif($vQuery->getcoupDeCoeur() == 'oui'){
				Xtremplate::$vars['sClassBandeau'] = 'BandeauCdc';
			}else{
				Xtremplate::$vars['sClassBandeau'] = 'BandeauNormal';
			}
		}
		$oLightBox = ANNONCE::SELECT('listePhotoOrig')->WHERE('annonce_id',$this->annonce_id)->exec();
		foreach($oLightBox as $kLight=>$vLight){
			$lightBoxArray= explode("|",$vLight->getlistePhotoOrig());
			$this['firstImage'] = array_shift($lightBoxArray);
			$this['lightBoxArray'] = $lightBoxArray;
		}
	}
}

?>