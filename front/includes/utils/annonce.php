<?php
class Annonce {
	private $iIdAgence = null;
    private $iIdNegociateur = null;
	private $iIdAnnonce = null;
	private $aAnnonceInfo =  array();
   
	public function __construct ($iIdAnnonce) {
        $this->iIdAnnonce = $iIdAnnonce;
    }
	
	public function setInfoAnnonce($sType="array",$aAnnonceInfo) {
		switch($sType) {
			case "array":
				$this->aAnnonceInfo = $aAnnonceInfo;
			break;
			case "bdd" : 
				/* Obtenir les info à partir de la bdd basé sur l'id d'Annonce*/
			break;
		}
		
	}
	public function setIdAgence ($iIdAgence) {
		$this->iIdAgence = $iIdAgence;
	}
	
	public function getIdAgence ($iIdAgence) {
		return $this->iIdAgence;
	}
	
	public function setIdNegociateur ($iIdNegociateur) {
		$this->iIdNegociateur = $iIdNegociateur;
	}
	
	public function getIdNegociateur ($iIdNegociateur) {
		return $this->iIdNegociateur;
	}
	
	public function getId () {
		return $this->iIdAnnonce;
	}
		
	public function getField($sField) {
		return $this->aAnnonceInfo[$sField];
	}
	
	public function setField($sField,$sData) {
		$this->aAnnonceInfo[$sField]=$sData;
	}
}
?>