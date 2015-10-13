<?php
class Filecheckbox extends File {
	const DEFAULT_TPL = 'input_filecheckbox.htm';
	
	private static $sDefaultTpl = null;
	
	public function __construct ($sLabel=null, $aParams=array(), $sTpl=null) {
		parent::__construct ($sLabel, $aParams, $sTpl);
		$this->getForm()->addParam('enctype', 'multipart/form-data');
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