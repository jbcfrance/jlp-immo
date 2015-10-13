<?php
abstract class ChangeEvent {
	private $sFunc;
	private $aParams;
	
	public function __construct ($sFunc, $aParams=array()) {
		$this->setJSFunc( $sFunc );
		$this->setParams( $aParams );
	}
	abstract public function call ();
	
	public function setJSFunc ( $sFunc ) {
		$this->sFunc = $sFunc;
	}
	public function getJSFunc () {
		return $this->sFunc;
	}
	
	public function setParams ( $aParams ) {
		$this->aParams = $aParams;
	}
	public function getParams () {
		return $this->aParams;
	}
}
?>