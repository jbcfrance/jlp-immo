<?php
abstract class Input extends XTremplate {
	private static $iActualID	= 0;
	
	private $iID				= 0;
	private $oForm				= null;
	
	protected $sDisplay			= null;
	protected $aParams			= array();
	protected $sTag				= null;
	protected $sLabel			= null;
	protected $sTpl				= null;
	
	public function __construct ($sLabel=null, $aParams=array(),$sTpl=null) {
		Form::addElement( $this );
		$this->setLabel($sLabel);
		$this->setParams($aParams);
		$this->setTpl($sTpl);
		$this->setID();
	}
	
	protected function setID(){$this->iID=self::$iActualID++;}
	public function getID(){return $this->iID;}
	
	public function setForm ($oForm) {
		$this->oForm = $oForm;
	}
	public function getForm () {
		return $this->oForm;
	}
	
	public function addParam ( $sParamsName, $sParamsValue  ) {
		$this->aParams[ strtoupper($sParamsName) ] = $sParamsValue;
	}
	public function setParams ( $aParams ) {
		$this->aParams = array_change_key_case( $aParams, CASE_UPPER);
	}
	public function getParams () {
		$aParams = array_change_key_case( $this->aParams, CASE_UPPER);
		return $aParams;
	}
	public function getParam ( $sParam ) {
		$aParams = array_change_key_case( $this->aParams, CASE_UPPER);
		return $aParams[ strtoupper($sParam) ];
	}
	
	public function setLabel ($sLabel) {
		$this->sLabel = $sLabel;
	}
	public function getLabel () {
		return $this->sLabel;
	}
	
	public function onChange ( $oChangeEvent ) {
		if ( ! is_a($oChangeEvent, 'ChangeEvent') ) {
			return false;
		}
		if ( session_id() == '' ) {
			return false;
		}
		
		if (!isset($_SESSION['FORMS']))
			$_SESSION['FORMS'] = array();
		
		
		if ( $oForm = $this->getForm() ) {
			$iFormID = $oForm->getID();
		} else {
			$iFormID = 'null';
		}
		
		if (!isset($_SESSION['FORMS'][ $iFormID ]))
			$_SESSION['FORMS'][ $iFormID ] = array();
		if (!isset($_SESSION['FORMS'][ $iFormID ][ $this->getID() ]))
			$_SESSION['FORMS'][ $iFormID ][ $this->getID() ] = array();
		if (!isset($_SESSION['FORMS'][ $iFormID ][ $this->getID() ]['ONCHANGE']))
			$_SESSION['FORMS'][ $iFormID ][ $this->getID() ]['ONCHANGE'] = array();
		
		$_SESSION['FORMS'][ $iFormID ][ $this->getID() ]['ONCHANGE'][] = $oChangeEvent;
		
		$sOnChange = '';
		if ( $sPreviousOnChange = $this->getParam('onchange') ) {
			$sOnChange .= $sPreviousOnChange.';';
		}
		
		$sOnChange .= $oChangeEvent->getJSFunc().'(this,'. $iFormID.','.$this->getID().');';
		
		$this->addParam('onchange', $sOnChange);
	}
	
	public function display () {
		$aParams = $this->getParams();
		if ( !isset($aParams['ID']) ) {
			$aParams['ID'] = 'form'.$this->getForm()->getID().'_input'.$this->getID();
		}
		
		$sParams = '';
		foreach ( $aParams as $sKey => $sValue ) {
			$sParams .= ' '.strtolower($sKey).'="'.$sValue.'"';
		}
		
		$sTpl = $this->getTpl();
		
		$this->setTplURL( $sTpl );
		
		$this->tplVars['ID']		= $aParams['ID'];
		$this->tplVars['OBJ']		= $this;
		$this->tplVars['LABEL']		= $this->getLabel();
		$this->tplVars['PARAMS']	= $sParams;
		
		parent::display();
	}
	
	public function setTpl ($sFooter) {
		$this->sTpl = $sFooter;
	}
	
	public abstract static function setDefaultTpl ( $sTpl );
	public abstract static function getDefaultTpl ();
	public abstract function getTpl ();
}
?>