<?php
class Negociateur {
	private $iIdAgence = null;
    private $iIdNegociateur = null;
	private $aNegociateurInfo =  array();
   
	public function __construct ($iIdNegociateur) {
        $this->iIdNegociateur = $iIdNegociateur;
    }
	
	public function setInfoNegociateur($sType="array",$aNegociateurInfo) {
		switch($sType) {
			case "array":
				$this->aNegociateurInfo = $aNegociateurInfo;
			break;
			case "bdd" : 
				/* Obtenir les info à partir de la bdd basé sur l'id du Negociateur*/
			break;
		}
		
	}
	
	public function setIdAgence ($iIdAgence) {
		$this->iIdAgence = $iIdAgence;
	}
	
	public function getIdAgence ($iIdAgence) {
		return $this->iIdAgence;
	}
	
	public function getId () {
		return $this->iIdNegociateur;
	}
		
	public function getField($sField) {
		return $this->aNegociateurInfo[$sField];
	}
	
	public function setField($sField,$sData) {
		$this->aNegociateurInfo[$sField]=$sData;
	}
}

?>