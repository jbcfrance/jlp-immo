<?php
class MultiSelect extends Input {
	const DEFAULT_TPL = 'input_multiselect.htm';
	
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
	
	private $oFirstSelect	= null;
	private $oSecondSelect	= null;
	
	public function __construct ($sLabel=null, $aParams=array()) {
		parent::__construct ($sLabel, $aParams);
		$this->oFirstSelect = new Select('', array());
		Form::clearElement($this->oFirstSelect);
		
		$this->oSecondSelect = new Select('', array());
		Form::clearElement($this->oSecondSelect);
	}
	
	public function firstStart () {
		Select::closeSelect();
		$this->getFirst()->start();
	}
	public function firstClose () {
		$this->getFirst()->close();
	}
	public function getFirst () {
		return $this->oFirstSelect;
	}
	public function getParams1 () {
		$aParams = $this->getParams();
		$aParams['ID'] = 'form'.$this->getForm()->getID().'_input'.$this->getID().'_1';
		$aParams['MULTIPLE'] = 'multiple';
		
		if ( isset($aParams['NAME']) ) {
			unset($aParams['NAME']);
		}
		if ( ! isset($aParams['size']) ) {
			$aParams['size'] = 10;
		}
		if ( ! isset($aParams['style']) ) {
			$aParams['style'] = 'width:120px;';
		}
		
		$sParams = '';
		foreach ( $aParams as $sKey => $sValue ) {
			$sParams .= ' '.strtolower($sKey).'="'.$sValue.'"';
		}
		
		return $sParams;
	}
	
	public function secondStart () {
		Select::closeSelect();
		$this->getSecond()->start();
	}
	public function secondClose () {
		$this->getSecond()->close();
	}
	public function getSecond () {
		return $this->oSecondSelect;
	}
	public function getParams2 () {
		$aParams = $this->getParams();
		$aParams['ID'] = 'form'.$this->getForm()->getID().'_input'.$this->getID().'_2';
		$aParams['MULTIPLE'] = 'multiple';
		
		$sParams = '';
		foreach ( $aParams as $sKey => $sValue ) {
			$sParams .= ' '.strtolower($sKey).'="'.$sValue.'"';
		}
		if ( ! isset($aParams['size']) ) {
			$aParams['size'] = 10;
		}
		if ( ! isset($aParams['style']) ) {
			$aParams['style'] = 'width:120px;';
		}
		
		$sParams = '';
		foreach ( $aParams as $sKey => $sValue ) {
			$sParams .= ' '.strtolower($sKey).'="'.$sValue.'"';
		}
		
		return $sParams;
	}
	
	public function display () {
		$aParams = $this->getParams();
		$sID1 = 'form'.$this->getForm()->getID().'_input'.$this->getID().'_1';
		$sID2 = 'form'.$this->getForm()->getID().'_input'.$this->getID().'_2';
		
		foreach ( $this->getFirst()->getOptions() as $oOpt ) {
			$oOpt->addParam('ondblclick', 'pass(this,\''.$sID1.'\',\''.$sID2.'\');');
		}
		foreach ( $this->getSecond()->getOptions() as $oOpt ) {
			$oOpt->addParam('ondblclick', 'pass(this,\''.$sID1.'\',\''.$sID2.'\');');
		}
		
		parent::display();
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