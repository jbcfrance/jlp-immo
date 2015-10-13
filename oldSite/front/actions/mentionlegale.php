<?php
/**
 * Auteur			: Jean-Baptiste CIEPKA
 * Date				: Decembre 2009
 *
 * Name				: Class mentionlegaleAction
 * Description		: Cette class génére les mention legale 
 * @templates dir 	: templates/mentionlegale
 *
*/
class mentionlegaleAction extends mentionlegaleAction_BASE {
	/**
	 * Default action for mentionlegale
	 *	date	: 2009-11-19 16:23:19
	 */
	public function doDefault ($aParams=array()) {
		if($_SESSION["lang"] == 'fr')
			Action::redirect('mentionlegale','MentionFr');
		else
			Action::redirect('mentionlegale','MentionEn');
	}
	public function doMentionFr ($aParams=array()){
		$this->addMeta('description',utf8_encode('JLP-IMMO - Immobilier Saint Gervais, Le Fayet, Passy et Sallanches, Agence immobilière Saint-Gervais, Le Fayet, Passy, Sallanches. Achat, Vente, Gestion, Conseils, Estimations'));
		
	}
	public function doMentionEn ($aParams=array()){
		$this->addMeta('description',utf8_encode('JLP-IMMO - Immobilier Saint Gervais, Le Fayet, Passy et Sallanches, Agence immobilière Saint-Gervais, Le Fayet, Passy, Sallanches. Achat, Vente, Gestion, Conseils, Estimations'));
		
	}
}
?>