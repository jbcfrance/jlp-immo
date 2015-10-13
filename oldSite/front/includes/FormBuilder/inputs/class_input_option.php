<?php
class Option extends Input {
	const DEFAULT_TPL = 'input_option.htm';
	
	private static $sDefaultTpl = null;
	
	public static function setDefaultTpl ( $sDefaultTpl ) {
		self::$sDefaultTpl = $sDefaultTpl;
	}
	public static function getDefaultTpl () {
		if ( self::$sDefaultTpl == null ) {
			return self::DEFAULT_TPL;
		} else {
			return self::$sDefaultTpl;
		}
	}
	
	public function __construct ($sValue, $sName, $aParams=array()) {
		$this->setValue($sValue);
		$this->setName($sName);
		$this->setParams( $aParams );
		
		if ( ! OptGroup::addOption($this) ) {
			Select::addOption($this);
		}
		parent::__construct(null, $aParams);
		Form::clearElement($this);
	}
	
	public function getTpl () {
		if ( $this->sTpl==null )  {
			return self::getDefaultTpl();
		} else {
			return $this->sTpl;
		}
	}
	
	public function setValue ($sValue) {
		$this->sValue = $sValue;
	}
	public function getValue () {
		return $this->sValue;
	}
	
	public function setName ($sName) {
		$this->sName = $sName;
	}
	public function getName () {
		return $this->sName;
	}
	
	public function addParam ($sName, $sValue) {
		$this->aParams[ $sName ] = $sValue;
	}
	public function setParams ($aParams) {
		$this->aParams = $aParams;
	}
	public function getParams () {
		return $this->aParams;
	}
}
?>