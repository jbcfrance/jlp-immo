<?php
class Action implements ArrayAccess, Iterator, Countable {
	private static $oActualAction	= null;
	private static $bIsInit			= false;
	
	private static function setActualAction ($oActualAction) {
		self::$oActualAction = $oActualAction;
	}
	
	public static function getActualAction () {
		return self::$oActualAction;
	}
	
	private	$oTemplate		= null,
			$sTitle			= '',
			$sLayout		= '',
			$sBase			= '',
			$aMeta			= array(),
			$aJavascript	= array(),
			$aCSS			= array();
	
	private static function init () {
		if ( ! self::$bIsInit ) {
			$aGlobalConf = file(CONFIG_PATH . 'globals_def.txt');
			foreach ( $aGlobalConf as $sLine ) {
				if ( ! in_array( substr($sLine, 0, 1), array(' ', '#') ) ) {
					$sTypeVar	= substr( $sLine, 0, strpos($sLine, ':') );
					$sValue		= substr( $sLine, strpos($sLine, ':')+1, strlen($sLine)-strpos($sLine, ':')-1 );
					$sValue		= str_replace(array(chr(10), chr(13)), '', $sValue);
					
					if ( defined($sValue) ) {
						$aDefs = get_defined_constants();
						$sValue = $aDefs[$sValue];
					}
					
					switch( strtolower($sTypeVar) ) {
						case 'title':{
							self::getActualAction()->setTitle($sValue);
							break;
						};
						case 'base':{
							self::getActualAction()->setBase($sValue);
							break;
						};
						case 'meta':{
							$aValues = explode('	', $sValue);
							self::getActualAction()->addMeta($aValues[0], $aValues[1]);
							break;
						};
						case 'css':{
							self::getActualAction()->addCSS(HTML_ROOT_PATH.'web/'.$sValue);
							break;
						};
						case 'script':{
							self::getActualAction()->addJavascript(HTML_ROOT_PATH.'web/'.$sValue);
							break;
						};
						case 'include':{
							include($sValue);
							break;
						};
					}
				}
			}
			
			self::$bIsInit = true;
		}
	}
	
	public final function __construct () {
		self::setActualAction($this);
		self::init();
	}
	
	public function start ($sMethod, $aParams) {
		$this->setLayout('layout');
		$sAction = $this->getActionName();
		
		$this->setTemplate(null);
		
		$sTmpMethod = 'do'.$sMethod;
		$this->$sTmpMethod( $aParams );
		
		if ( $this->getTemplate()->getTplURL() == null ) {
			if ( file_exists(TEMPLATE_PATH.$sAction.DIRECTORY_SEPARATOR.$sMethod.'.'.TEMPLATE_EXTENSION) ) {
				$sTemplateURL = TEMPLATE_PATH.$sAction.DIRECTORY_SEPARATOR.$sMethod.'.'.TEMPLATE_EXTENSION;
			} else if ( file_exists(TEMPLATE_PATH.$sAction.'_'.$sMethod.'.'.TEMPLATE_EXTENSION) ) {
				$sTemplateURL =TEMPLATE_PATH.$sAction.'_'.$sMethod.'.'.TEMPLATE_EXTENSION;
			} else if ( file_exists($this->getTemplateFile($sMethod)) ) {
				$sTemplateURL = $this->getTemplateFile($sMethod);
			} else {
				throw new Exception('Template introuvable :<br />Action :' . $sAction . '<br />Method : '.$sMethod);
			}
			$this->getTemplate()->setTplURL( $sTemplateURL );
		}
		
		// Composition du template :
		if ( $this->getLayout() !== null ) {
			$oLayout = new XTremplate($this->getLayout());
		
			if ( params(PARAM_GET_CONTENT) ){
				$this->oTemplate->display();
			} else {
				$oLayout['CONTENT']		= $this->oTemplate->display(true);
				$oLayout['TITLE']		= $this->getTitle();
				$oLayout['META']		= $this->getMeta();
				$oLayout['JAVASCRIPT']	= $this->getJavascript();
				$oLayout['CSS']			= $this->getCSS();
				$oLayout['BASE']		= $this->getBase();
				
				$oLayout->display();
			}
		} else {
			$this->oTemplate->display();
		}
	}
	
	protected function getLayout () {
		return $this->sLayout;
	}
	protected function setLayout ($sLayout) {
		if ( $sLayout === null ) {
			$this->sLayout = null;
		} else {
			$this->sLayout = TEMPLATE_PATH.$sLayout.'.'.TEMPLATE_EXTENSION;
		}
	}
	
	public function redirect ($sAction, $sMethod='default'){
		$sAction .= 'Action';
		$oRedirect = new $sAction($sMethod, Links::getActualParams());
		
		$oRedirect->setTitle		( $this->getRealTitle		() );
		$oRedirect->setBase			( $this->getRealBase		() );
		$oRedirect->setMeta			( $this->getRealMeta		() );
		$oRedirect->setJavascript	( $this->getRealJavascript	() );
		$oRedirect->setCSS			( $this->getRealCSS			() );
		
		$oRedirect->start($sMethod, Links::getActualParams());
		exit;
	}
	
	protected function getTemplateFile ($sMethod) {
		return TEMPLATE_PATH.'default'.'.'.TEMPLATE_EXTENSION;
	}
	protected function setTemplate ($sTemplate) {
		$this->oTemplate = new XTremplate($sTemplate);
	}
	protected function getTemplate () {
		return $this->oTemplate;
	}
	
	public function set ($k,$v) {
		if ( $this->getTemplate() !== null ) {
			$this->getTemplate()->tplVars[$k]=$v;
		}
	}
	
	public function getTitle		(){
		return $this->sTitle!=''?'<title>'.$this->sTitle.'</title>'."\r\n":'';
	}
	public function getBase			(){
		return $this->sBase!=''?'<base href="'.$this->sBase.'" />'."\r\n":'';
	}
	public function getMeta			(){
		$sMeta = '';
		foreach ( $this->aMeta as $sType => $aMeta ) {
			foreach($aMeta as $sName => $sValue ) {
				$sMeta .= '<meta '.$sType.'="'.$sName.'" content="'.$sValue.'" />'."\r\n";
			}
		}
		return $sMeta;
	}
	public function getJavascript	(){
		$sScript = '';
		foreach ( $this->aJavascript as $sType => $aSrc ) {
			$sScript .= '<script type="'.$sType.'" src="'.join($aSrc,'"></script>'."\r\n".'<script type="'.$sType.'" src="').'"></script>'."\r\n";
		}
		return $sScript;
	}
	public function getCSS			(){
		if ( count($this->aCSS) > 0 ) {
			return '<link rel="stylesheet" type="text/css" href="' . join($this->aCSS, '" />'."\r\n".'<link rel="stylesheet" type="text/css" href="') . '" />'."\r\n";
		} else {
			return '';
		}
	}
	
	public function getRealTitle		(){return $this->sTitle;}
	public function getRealBase			(){return $this->sBase;}
	public function getRealMeta			(){return $this->aMeta;}
	public function getRealJavascript	(){return $this->aJavascript;}
	public function getRealCSS			(){return $this->aCSS;}
	
	public function setTitle		($sTitle)		{ $this->sTitle			=$sTitle;		}
	public function setBase			($sBase)		{ $this->sBase			=$sBase;		}
	public function setMeta			($aMeta)		{ $this->aMeta			=$aMeta;		}
	public function setJavascript	($aJavascript)	{ $this->aJavascript	=$aJavascript;	}
	public function setCSS			($aCSS)			{ $this->aCSS			=$aCSS;			}
	
	public function addMeta($sName,$sContent,$sType='name'){
		$this->aMeta[ $sType ][ $sName ] = $sContent;
	}
	public function addJavascript($sSrc,$sType='text/javascript'){
		$this->aJavascript[$sType][] = $sSrc;
	}
	public function addCSS($sHref){
		$this->aCSS[] = $sHref;
	}
	
	// Implements Func :
	public function offsetExists($k){
		return isset($this->getTemplate()->tplVars[$k]);
	}
	public function offsetGet($k){
		return $this->getTemplate()->tplVars[$k];
	}
	public function offsetSet($k,$v){
		return $this->getTemplate()->tplVars[$k]=$v;
	}
	public function offsetUnset($k){
		unset($this->getTemplate()->tplVars[$k]);
	}
	public function valid(){
		return array_key_exists(key($this->getTemplate()->tplVars),$this->getTemplate()->tplVars);
	}
	public function next(){
		next($this->getTemplate()->tplVars);
		return $this->getTemplate()->tplVars;
	}
	public function rewind(){
		reset($this->getTemplate()->tplVars);
		return $this;
	}
	public function key(){
		return key($this->getTemplate()->tplVars);
	}
	public function current(){
		return current($this->getTemplate()->tplVars);
	}
	public function count(){
		return count($this->getTemplate()->tplVars);
	}
	
	public function __set($k,$v){
		if(!isset($this->$k)){
			$this->getTemplate()->tplVars[$k]=$v;
		} else {
			$this->$k=$v;
		}
	}
	public function __get($k){
		if(!isset($this->$k)){
			return $this->getTemplate()->tplVars[$k];
		} else {
			return $this->$k;
		}
	}
}
?>