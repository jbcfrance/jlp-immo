<?php
class OptGroup extends Input {
	const DEFAULT_TPL = 'input_option_group.htm';
	
	public static $sDefaultTpl = null;
	
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
	
	
	public static $oOptGroupOpen = null;
	
	public static function startSelect ( $oSelect ) {
		self::$oOptGroupOpen = $oSelect;
	}
	public static function closeSelect () {
		self::$oOptGroupOpen = null;
	}
	public static function addOption ( $oOpt ) {
		if ( self::$oOptGroupOpen != null ) {
			self::$oOptGroupOpen->addOpt( $oOpt );
			return true;
		} else {
			return false;
		}
	}
	
	
	private $aOptions	= array();
	protected $aParams	= null;
	
	public function __construct ($sName, $aParams=array()) {
		$this->setName($sName);
		$this->setParams($aParams);
		Select::addOption($this);
		self::closeSelect();
		self::startSelect( $this );
		
		parent::__construct();
		Form::clearElement($this);
	}
	
	private function addOpt ( $oOpt ) {
		$this->aOpt[] = $oOpt;
	}
	public function getOptions () {
		return $this->aOpt;
	}
	
	
	public function getTpl () {
		if ( $this->sTpl==null )  {
			return self::getDefaultTpl();
		} else {
			return $this->sTpl;
		}
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