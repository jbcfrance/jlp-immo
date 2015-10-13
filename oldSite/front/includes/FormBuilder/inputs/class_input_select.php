<?php
class Select extends Input {
	const DEFAULT_TPL = 'input_select.htm';
	
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
	
	
	public function getTpl () {
		if ( $this->sTpl==null )  {
			return self::getDefaultTpl();
		} else {
			return $this->sTpl;
		}
	}
	
	private static $oSelects = null;
	public static function startSelect ( $oSelect ) {
		self::$oSelects = $oSelect;
	}
	public static function closeSelect () {
		self::$oSelects = null;
		OptGroup::closeSelect();
	}
	public static function addOption ( $oOpt ) {
		if ( self::$oSelects != null ) {
			self::$oSelects->addOpt( $oOpt );
		}
	}
	
	private $aOpt	= array();
	private function addOpt ( $oOpt ) {
		$this->aOpt[] = $oOpt;
	}
	public function getOptions () {
		return $this->aOpt;
	}
	public function start () {
		self::startSelect($this);
	}
	public function close () {
		self::closeSelect($this);
	}
	
}
?>