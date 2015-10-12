<?php
class Error extends Message {
	const DEFAULT_TPL	= 'error.htm';
	const DEFAULT_STYLE	= 'background-color:#cccccc; color:#ff0000;';
	
	
	private static $sDefaultTpl		= null;
	private static $sDefaultStyle	= null;
	
	public static function getDefaultStyle () {
		if ( self::$sDefaultStyle===null ) {
			return Error::DEFAULT_STYLE;
		} else {
			return self::$sDefaultStyle;
		}
	}
	public static function setDefaultStyle ( $sStyle ) {
		self::$sDefaultStyle = $sStyle;
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
	
	
	public function display () {
		$aParams = $this->getParams();
		if ( ! isset($aParams['style']) ) {
			$this->addParam('style', Error::getDefaultStyle());
		}
		
		if ( $this->sTpl==null ) {
			$this->setTpl(Error::getDefaultTpl());
		}
		
		parent::display();
	}
}
?>