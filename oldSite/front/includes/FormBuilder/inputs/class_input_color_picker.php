<?php
class ColorPicker extends Input {
	const DEFAULT_TPL = 'input_color_picker.htm';
	
	private static $bIsFirst = true;
	
	private static $sDefaultTpl = null;
	
	public function __construct ($sLabel=null, $aParams=array(),$sTpl=null) {
		parent::__construct ($sLabel, $aParams, $sTpl);
		
		if ( self::$bIsFirst ) {
			$this['bIsFirst'] = true;
			Action::getActualAction()->addJavascript( urlToFile('web/js/jscolor.js') );
			self::$bIsFirst = false;
		} else {
			$this['bIsFirst'] = false;
		}
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