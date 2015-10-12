<?php
class outil_Agence {
    private $iIdAgence = null;
	private $aAgenceInfo  =  array();
    
	public function __construct ($iIdAgence) {
        $this->iIdAgence = $iIdAgence;
    }
	
	public function setInfoAgence($sType="array",$aAgenceInfo) {
		switch($sType) {
			case "array":
				$this->aAgenceInfo = $aAgenceInfo;
			break;
			case "bdd" : 
				/* Obtenir les info � partir de la bdd bas� sur l'id d'agence*/
			break;
		}
		
	}
	
	public function getId () {
		return $this->iIdAgence;
	}
		
	public function getField($sField) {
		return $this->aAgenceInfo[$sField];
	}
	
	public function setField($sField,$sData) {
		$this->aAgenceInfo[$sField]=$sData;
	}
}
?>