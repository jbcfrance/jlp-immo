<?php
class ColorPickerV2 extends Input {
	const DEFAULT_TPL = 'input_color_pickerv2.htm';
	
	private static $bIsFirst = true;
	
	private static $sDefaultTpl = null;
	
	public function __construct ($sLabel=null, $aParams=array(),$sTpl=null) {
		parent::__construct ($sLabel, $aParams,$sTpl);
		
		if ( self::$bIsFirst ) {
			$this['bIsFirst'] = true;
			Action::getActualAction()->addJavascript( urlToFile('web/js/ifx.js') );
			Action::getActualAction()->addJavascript( urlToFile('web/js/idrop.js') );
			Action::getActualAction()->addJavascript( urlToFile('web/js/idrag.js') );
			Action::getActualAction()->addJavascript( urlToFile('web/js/iutil.js') );
			Action::getActualAction()->addJavascript( urlToFile('web/js/islider.js') );
			Action::getActualAction()->addJavascript( urlToFile('web/js/ifx.js') );
			Action::getActualAction()->addJavascript( urlToFile('web/js/color_picker/color_picker.js') );
			Action::getActualAction()->addCSS( urlToFile('web/js/color_picker/color_picker.css') );
			Action::getActualAction()->addCSS( urlToFile('web/js/color_picker/color_picker-ie7.css') );
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