<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class defaultAction
 * Description		: Cette class génére l'introduction du site
 * @templates dir 	: templates/default
 *
*/
class defaultAction extends defaultAction_BASE {
	public function doDefault ($aParams=array()) {
		if(!isset($_SESSION["lang"]))$_SESSION["lang"] = "fr";
		
		//$this->setLayout("introLayout");
		
		$this->redirect('accueil','accueil');
		
		
		
		/*$this->setLayout("introLayout");
		$oQuery = DIAPOINTRO::SELECT()->exec();
		$sHauteur = 0;
		foreach($oQuery as $kDiapo=>$vDiapo) {
			list($largeur, $hauteur) = getimagesize('web/images/diapo/Diapo_'.$vDiapo->getdiapo_file());
			if($hauteur>$sHauteur)$sHauteur=$hauteur;
		}
		$this["DiapoHauteur"] = $sHauteur +4;
		$this["DiapoFile"] = $oQuery;*/
	}
}
?>