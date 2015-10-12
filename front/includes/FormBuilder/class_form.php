<?php
class Form extends XTremplate implements ArrayAccess, Iterator, Countable {
	const DEFAULT_METHOD		= 'POST';
	const DEFAULT_ACTION		= '?';
	const DEFAULT_TPL			= 'form.htm';
	
	private static $iActualID	= 0;
	private static $aFormOpen	= array();
	private static $sDefaultTpl	= null;
	private static $aParams		= array();
	
	public static function addElement ( $oElem ) {
		foreach ( self::$aFormOpen as $oFormOpen ) {
			$oFormOpen->add( $oElem );
		}
	}
	
	public static function clearElement ( $oElem ) {
		foreach ( self::$aFormOpen as $oForm ) {
			$oForm->remove( $oElem );
		}
	}
	
	private static function startForm ( $oForm ) {
		foreach ( self::$aFormOpen as $oFormOpen ) {
			if ( $oFormOpen->getID() == $oForm->getID() ) {
				// Le form est deja ouvert :
				return false;
			}
		}
		self::$aFormOpen[] = $oForm;
		return true;
	}
	
	private static function closeForm ( $oForm ) {
		foreach ( self::$aFormOpen as $kForm => $oFormOpen ) {
			if ( $oFormOpen->getID() == $oForm->getID() ) {
				// Le form est ouvert :
				array_splice( self::$aFormOpen, $kForm, 1 );
				return true;
			}
		}
		return false;
	}
	
	public static function getOpenedForm () {
		return self::$aFormOpen;
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
	
	
	
	private $iID			= 0;
	private $aInputElements	= array();
	private $bIsOpen		= false;
	private $sTpl			= null;
	private $sMethod		= null;
	private $sAction		= null;
	
	public function __construct ($aParams=array()) {
		$this->setID();
		
		if ( session_id() == '' ) {
			session_start();
		}
		$_SESSION['FORMS'][ $this->getID() ] = array();
		$this->setParams($aParams);
	}
	
	private function setID(){$this->iID=self::$iActualID++;}
	public function getID(){return $this->iID;}
	private function add($oElem){$this->aInputElements[]=$oElem;$oElem->setForm($this);}
	private function remove($oElem){
		for ( $i=0, $iMax=count($this->aInputElements) ; $i<$iMax ; $i++ ) {		
			if ( $oElem == $this->aInputElements[$i] ) {
				array_splice( $this->aInputElements, $i, 1);
				return true;
			}
		}
		return false;
	}
	public function addText ( $sText ) {$this->aInputElements[]=$sText;}
	private function getElements(){return $this->aInputElements;}
	
	public function setMethod ($sMethod) {
		$this->sMethod = $sMethod;
	}
	public function getMethod () {
		return $this->sMethod;
	}
	
	public function setAction ($sAction) {
		$this->sAction = $sAction;
	}
	public function getAction () {
		return $this->sAction;
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
	
	
	public function start () {
		$this->bIsOpen = true;
		return self::startForm($this);
	}
	
	public function close () {
		$this->bIsOpen = false;
		return self::closeForm($this);
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
	
	public function display ( $bReturn=false ) {
		parent::__construct();
		
		$aParams = $this->getParams();
		$sParams = '';
		foreach ( $aParams as $sKey => $sValue ) {
			$sParams .= ' '.strtolower($sKey).'="'.$sValue.'"';
		}
		
		
		if ( $this->bIsOpen ) {
			$this->close();
		}
		if ( $bReturn ) {
			ob_start();
		}
		
		$sHiddens = '';
		foreach ( $this->getElements() as $oElement ) {
		
			if ( strtolower(get_class($oElement)) == 'hidden' ) {
				$sHiddens .= '<input type="hidden" name="'.$oElement->getParam('name').'" value="'.$oElement->getParam('value').'" id="'.$oElement->getParam('id').'" />';
				$this->remove( $oElement );
			}
		}
		
		$sOldTemplatePath = XTremplate::getTemplatePath();
		XTremplate::setTemplatePath( dirname(__FILE__).'/templates/' );
		$this->setTplURL( $this->getTpl() );
		
		$this->tplVars['FORM'][ $this->getID() ] = array(
			'METHOD'	=> $this->getMethod(),
			'ACTION'	=> $this->getAction(),
			'ELEMENTS'	=> $this->getElements(),
			'PARAMS'	=> $sParams,
			'sHiddens'	=> $sHiddens,
		);
		$this->tplVars['ACTUAL_ID'] =  $this->getID();
		
		parent::display();
		
		
		XTremplate::setTemplatePath( $sOldTemplatePath );
		
		if ( $bReturn ) {
			$sReturn = ob_get_contents();
			ob_end_clean();
			return $sReturn;
		}
	}

	public function __toString(){
		return $this->display(true);
	}
	
	public function offsetExists($k){
		return isset($this->aInputElements[$k]);
	}
	public function offsetGet($k){
		return $this->aInputElements[$k];
	}
	public function offsetSet($k,$v){
		return $this->add($v);
	}
	public function offsetUnset($k){
		unset($this->aInputElements[$k]);
	}
	public function valid(){
		return array_key_exists(key($this->aInputElements),$this->aInputElements);
	}
	public function next(){
		next($this->aInputElements);
		return $this;
	}
	public function rewind(){
		reset($this->aInputElements);
		return $this;
	}
	public function key(){
		return key($this->aInputElements);
	}
	public function current(){
		return current($this->aInputElements);
	}
	public function count(){
		return count($this->aInputElements);
	}
}

require_once('formValidators.php');
?>