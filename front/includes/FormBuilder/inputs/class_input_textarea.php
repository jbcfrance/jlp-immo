<?php
class Textarea extends Input {
	const DEFAULT_TPL = 'input_textarea.htm';
	
	private static $sDefaultTpl = null;
	private $sValue = '';
	
	public function __construct ($sLabel=null, $aParams=array(),$sTpl=null) {
		if ( isset($aParams['value']) ) {
			$this->setValue( $aParams['value'] );
			unset( $aParams['value'] );
		}
		
		parent::__construct($sLabel, $aParams, $sTpl);
	}
	
	public function setValue ( $sValue ) {
		$this->sValue = $sValue;
	}
	public function getValue () {
		return $this->sValue;
	}
	
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
}
?>