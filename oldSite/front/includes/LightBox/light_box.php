<?php
class lightBox extends XTremplate {
	private static $aUsedTemplates = array();
	
	const	DEFAULT_PATTERN	= '%s',
			DEFAULT_TPL		= 'defaultLightBox',
			DEFAULT_WIDTH	= 500,
			DEFAULT_HEIGHT	= 300,
			DEFAULT_PARAMS	= 300;
	
	public static function getDirect ($sUrlFor, $sPattern, $sTpl, $sWidth, $sHeight, $sParameters) {
		if ( ''===$sPattern )		$sPattern	= self::DEFAULT_PATTERN;
		if ( ''===$sTpl )			$sTpl		= self::DEFAULT_TPL;
		if ( ''===$sWidth )			$sWidth		= self::DEFAULT_WIDTH;
		if ( ''===$sHeight )		$sHeight	= self::DEFAULT_HEIGHT;
		if ( ''===$sParameters )	$sParameters	= self::DEFAULT_HEIGHT;
		
		$sRet = sprintf($sPattern, "lightBox('$sUrlFor', '$sParameters', '$sWidth', '$sHeight', '$sTpl');");
		if ( ! self::isUsedTemplates($sTpl) ) {
			self::addUsedTemplates($sTpl);
			$oTpl = new XTremplate(TEMPLATE_PATH . $sTpl . '.htm');
			$sRet .= $oTpl->display(true);
		}
		
		return $sRet;
	}
	
	public static function isUsedTemplates($sUsedTemplates) {
		return in_array($sUsedTemplates, self::$aUsedTemplates);
	}
	
	public static function getUsedTemplates() {
		return self::$aUsedTemplates;
	}
	
	public static function addUsedTemplates($sUsedTemplates) {
		self::$aUsedTemplates[] = $sUsedTemplates;
	}
}

function addLightBox(){
	Action::getActualAction()->addJavaScript(SCRIPTS.'light_box.js');
	Action::getActualAction()->addCSS(CSS.'light_box.css');
}
?>