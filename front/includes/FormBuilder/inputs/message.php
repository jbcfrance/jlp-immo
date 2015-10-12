<?php
class Message extends Input {
	const DEFAULT_TPL	= 'message.htm';
	const DEFAULT_STYLE	= 'background-color:#cccccc;';
	
	private static $sDefaultTpl		= null;
	private static $sDefaultStyle	= null;
	
	public static function getDefaultStyle () {
		if ( self::$sDefaultStyle===null ) {
			return self::DEFAULT_STYLE;
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
	
	
	private $oForm				= null;
	
	protected $sDisplay			= null;
	protected $aParams			= array();
	protected $sTag				= null;
	protected $sLabel			= null;
	
	public function __construct ($sLabel=null, $aParams=array(),$sTpl=null) {
		Form::addElement( $this );
		$this->setLabel($sLabel);
		$this->setParams($aParams);
		$this->setTpl($sTpl);	
	}
	
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
	
	public function display () {
		$aParams = $this->getParams();
		if ( ! isset($aParams['style']) ) {
				$aParams['style'] = self::getDefaultStyle();
			}		
		
		$sParams = '';
		foreach ( $aParams as $sKey => $sValue ) {
			$sParams .= ' '.strtolower($sKey).'="'.$sValue.'"';
		}
		$sTpl = $this->getTpl();
		
		$this->setTplURL( $sTpl );
		
		$this->tplVars['OBJ']		= $this;
		$this->tplVars['LABEL']		= $this->getLabel();
		$this->tplVars['PARAMS']	= $sParams;
		
		XTremplate::display();
	}
	
	public function setTpl ($sFooter) {
		$this->sTpl = $sFooter;
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