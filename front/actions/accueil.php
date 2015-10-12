<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class accueilAction
 * Description		: Cette classe génére l'accueil du site.
 * @templates dir 	: templates/accueil
 *
*/
class accueilAction extends accueilAction_BASE {
	/**
	 * Default action for accueil
	 *	date	: 2009-09-15 11:55:42
	 */ 
	public function doDefault ($aParams=array()) {
		$this->setLayout("layout");
		if(!empty( $aParams['lang']) ){
			$_SESSION["lang"] = $aParams['lang'];
		}else{
			$_SESSION["lang"] = "fr";
		}
		$this->redirect('accueil','accueil');
	}
	
	public function doAccueil ($aParams = array()) {
		$this->setLayout("layout");
		Xtremplate::$vars['sTitleH1'] = utf8_encode("JLP-Immo.com, Agence immobilière à Saint-gervais, Le Fayet, Passy et Sallanches");
		$this->addMeta('description',utf8_encode("Achat - Vente - Conseils immobiliers dans la vallée de l'Arve. Des Contamines à Passy en passant par Saint-Gervais, Le Fayet et Sallanches"));
		$this["like"] = utf8_encode('<iframe src="http://www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fpages%2FJLP-IMMO%2F122479657785701&amp;width=292&amp;colorscheme=light&amp;show_faces=true&amp;stream=false&amp;header=true&amp;height=62" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>');
		$oImgPres = SITE::SELECT('site_valeur')->WHERE('site_variable','pres_image')->exec();
		$this['presImg'] = $oImgPres;
		if($_SESSION["lang"]== "en")
		$oTxtPres = SITE::SELECT('site_valeur')->WHERE('site_variable','pres_texte_en')->exec();
		else
		$oTxtPres = SITE::SELECT('site_valeur')->WHERE('site_variable','pres_texte_fr')->exec();
		$this['presTxt'] = $oTxtPres;
		$oImgActu = SITE::SELECT('site_valeur')->WHERE('site_variable','actu_image')->exec();
		$this['actuImg'] = $oImgActu;
		if($_SESSION["lang"]== "en")
		$oTxtActu = SITE::SELECT('site_valeur')->WHERE('site_variable','actu_texte_en')->exec();
		else
		$oTxtActu = SITE::SELECT('site_valeur')->WHERE('site_variable','actu_texte_fr')->exec();
		$this['actuTxt'] = $oTxtActu;
	}
	
	
}
?>