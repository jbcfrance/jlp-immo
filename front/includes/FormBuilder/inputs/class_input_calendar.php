<?php
class Calendar extends Text {
	const DEFAULT_TPL = 'input_calendar.htm';
	private static $bIsFirst = true;
	private static $sDefaultTpl = null;
	
	public static function setDefaultTpl ( $sDefaultTpl ) {
		Button::$sDefaultTpl = $sDefaultTpl;
	}
	public static function getDefaultTpl () {
		if ( self::$sDefaultTpl == null ) {
			return self::DEFAULT_TPL;
		} else {
			return self::$sDefaultTpl;
		}
	}
	
	private $sStart	= null;
	private $sEnd	= null;
	
	public function __construct ($sLabel=null, $aParams=array(),$sTpl=null) {
		if ( isset($aParams['start']) ) {
			$this->setStart( $aParams['start'] );
			unset($aParams['start']);
		} else {
			$this->setStart('00-00-0000');
		}
		if ( isset($aParams['end']) ) {
			$this->setEnd( $aParams['end'] );
			unset($aParams['end']);
		} else {
			$this->setEnd('99-99-9999');
		}
		
		if ( self::$bIsFirst ) {
			$this['bIsFirst'] = true;
			Action::getActualAction()->addJavascript( urlToFile('web/js/calendar.js') );
			Action::getActualAction()->addCSS( urlToFile('web/css/calendar.css') );
			self::$bIsFirst = false;
		} else {
			$this['bIsFirst'] = false;
		}
		parent::__construct($sLabel, $aParams,$sTpl);
	}
	
	public function setStart ($sStart) {
		$this->sStart = $sStart;
	}
	public function getStart () {
		return $this->sStart;
	}
	
	public function setEnd ($sEnd) {
		$this->sEnd = $sEnd;
	}
	public function getEnd () {
		return $this->sEnd;
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