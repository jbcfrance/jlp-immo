<?php
class XTremplate implements ArrayAccess, Iterator, Countable {
	const CACHE_PATH	= '../template/cache';
	const TEMPLATE_PATH	= '../template';
	
	const CACHE_ON_TIME				= 1;
	const CACHE_ON_FILE_CHANGE		= 2;
	const CACHE_ON_VARIABLES_CHANGE	= 4;
	const CACHE_NEVER				= 8;
	const CACHE_ALWAYS				= 16;
	
	// STATIC //
	private static $bIsInitialize		= false;
	private static $sTemplatePath		= null;
	private static $sCachPath			= null;
	private static $aCache				= null;
	private static $aCachePHPGlobal		= null;
	private static $iPHPCacheMode		= 8;		// NEVER par defaut
	private static $iPHPCacheTime		= 0;		// 0 par defaut
	public static $vars					= array();	// Variales pour tous les templates
	
	private static $REGPattern			= array();	// REG PATTERNS
	private static $REGReplacement		= array();	// REG REPLACE
	
	//------------------------------------------------------------
	// Gestion des regles de remplacement :
	
	# Ajout d'une regle :
	public static function addREGRule ($sPattern, $sReplacement, $sREGName=null) {
		if ( !isset($sREGName) ) {
			$sREGName = 'custom' . self::$CustomRules;
			self::$CustomRules++;
		}
		self::$REGPattern[ $sREGName ]		= $sPattern;
		self::$REGReplacement[ $sREGName ]	= $sReplacement;
	}
	# Suppression d'une regle :
	public static function deleteREGRule ($sRuleName) {
		unset( self::$REGPattern[ $sRuleName ] );
		unset( self::$REGReplacement[ $sRuleName ]	);
	}
	# Supprimer toutes les regles :
	public static function clearAllREGRule () {
		self::$REGPattern = array();
		self::$REGReplacement = array();
	}
	# Retourne toutes les regles :
	public function getREGRules () {
		$aRet = array();
		$aPattern		= self::getREGPattern();
		$aReplacement	= self::getREGReplacement();
		foreach ( $aPattern as $kRule => $vRule ) {
			$aRet[ $aPattern[$kRule] ] = $aReplacement[$kRule];
		}
		return $aRet;
	}
	# Retourne seulement les pattern :
	protected static function getREGPattern () {
		return self::$REGPattern;
	}
	#Retourne seulement les remplacant :
	protected static function getREGReplacement () {
		return self::$REGReplacement;
	}
	
	// Cache
	private static function initCache () {
		$sCacheFile = self::getCachePath().'/cache.php';
		if ( file_exists($sCacheFile) ) {
			include($sCacheFile);
		}
		if ( ! is_array(self::$aCache) ) {
			self::$aCache = array('PHP'=>array());
		}
		
		$sCachePath = self::getCachePath();
		return;
		
		if ( ! is_dir($sCachePath) ) {
			mkdir($sCachePath, '0777');
		}
		if ( ! is_dir($sCachePath.'/php') ) {
			mkdir($sCachePath.'/php', '0777');
		}
	}
	public static function saveCache () {
		if ( ! is_dir(self::getCachePath()) ) {
			mkdir(self::getCachePath(), '0777');
		}
		$sCacheFile = self::getCachePath().'/cache.php';
		$rFile = fopen($sCacheFile, 'w+');
		fputs($rFile, '<?php
self::$aCache = '.var_export(self::$aCache, true).';
?>');
		fclose($rFile);
	}
	private static function initClass () {
		if ( ! self::$bIsInitialize ) {
			self::$bIsInitialize = true;
			if(self::getTemplatePath()==null){
				self::setTemplatePath(dirname(__FILE__) . '/' . self::TEMPLATE_PATH);
			}
			if(self::getCachePath()==null){
				self::setCachePath(dirname(__FILE__) . '/' . self::CACHE_PATH);
			}
			self::initCache();
		}
	}
	
	// Template
	public static function setTemplatePath ($sTemplatePath) {
		self::$sTemplatePath = $sTemplatePath;
	}
	public static function getTemplatePath () {
		return self::$sTemplatePath;
	}
	
	// Cache
	public static function setCachePath ( $sCachPath ) {
		self::$sCachPath = $sCachPath;
	}
	public static function getCachePath () {
		return self::$sCachPath;
	}
	
	public static function setPHPCacheMode_Global ($iPHPCacheMode, $iPHPCacheTime=0) {
		self::$aCache['PHP_GLOBAL'] = array(
			'TYPE'			=> $iPHPCacheMode,
			'TIME'			=> $iPHPCacheTime,
			'ACTUAL_TIME'	=> time(),
			'VARS'			=> md5(serialize(self::$vars)),
		);
	}
	public static function getPHPCacheMode_Global () {
		return self::$aCache['PHP_GLOBAL'];
	}
	
	private static function hasToCachePHP_Global () {
		$aCache	= self::getPHPCacheMode_Global();
		$iMode	= $aCache['TYPE'];
		
		if ( $iMode&self::CACHE_ALWAYS )															return true;
		if ( $iMode&self::CACHE_NEVER )																return false;
		if ( ($iMode&self::CACHE_ON_TIME) && (time()>$aCache['TIME']+$aCache['ACTUAL_TIME']) )		return true;
		if ( $iMode&self::CACHE_ON_FILE_CHANGE )													return false;
		if ( $iMode&self::CACHE_ON_VARIABLES_CHANGE )												return false;
	}
	
	// OBJECT //
	private $sPHPContent	= null;
	private $sHTMLContent	= null;
	private $sURL			= null;
	public $tplVars			= array();	// Variables pour le template
	
	public function __construct ($sTplURL=null) {
		self::initClass();
		
		if ( $sTplURL !== null ) {
			$this->setTplURL( $sTplURL );
		}
	}
	public function setTplURL ( $sURL ) {
		$sTMP_URL = $sURL;
		if ( ! file_exists($sTMP_URL) ) {
			$sTMP_URL = self::getTemplatePath() . $sURL;
		}
		if ( ! file_exists($sTMP_URL) ) {
			$sTMP_URL = self::getTemplatePath() .'/'. $sURL;
		}
		
		if ( ! file_exists($sTMP_URL) ) {
			throw new Exception('le fichier '. $sURL .' n\'existe pas.');
		}
		$this->sURL = $sTMP_URL;
		
		$this->setPHPContent( file_get_contents($sTMP_URL) );
		
		$this->setKey(md5($sTMP_URL));
	}
	public function getTplURL () {
		return $this->sURL;
	}
	
	
	private function setKey ( $sKey ) {
		$this->sKey = $sKey;
	}
	private function getKey () {
		return $this->sKey;
	}
	
	// PHP //
	public function setPHPContent ($sPHPContent ) {
		$this->sPHPContent	= $sPHPContent;
	}
	public function getPHPContent () {
		return $this->sPHPContent;
	}
	
	public function setPHPCacheMode ($iCacheType, $iTime=0) {
		$sURL = $this->getTplURL();
		if ( $sURL === null ) {
			return false;
		}
		$sKey = $this->getKey();
		self::$aCache['PHP'][ $sKey ] = array(
			'TYPE'			=> $iCacheType,
			'TIME'			=> $iTime,
			'ACTUAL_TIME'	=> time(),
			'VARS'			=> md5(serialize(self::$vars) . serialize($this->tplVars)),
			'FILE'			=> md5($this->getPHPContent()),
		);
	}
	public function getPHPCacheMode () {
		$sURL = $this->getTplURL();
		if ( $sURL === null ) {
			$aDefaultCacheMode = self::getPHPCacheMode_Global();
			return array(
				'TYPE'			=> $aDefaultCacheMode['TYPE'],
				'TIME'			=> $aDefaultCacheMode['TIME'],
				'ACTUAL_TIME'	=> time(),
				'VARS'			=> '',
				'FILE'			=> '',
			);
		}
		$sKey = $this->getKey();
		if ( ! isset(self::$aCache['PHP'][ $sKey ]) ) {
			$aDefaultCacheMode = self::getPHPCacheMode_Global();
			self::$aCache['PHP'][ $sKey ] = array(
				'TYPE'			=> $aDefaultCacheMode['TYPE'],
				'TIME'			=> $aDefaultCacheMode['TIME'],
				'ACTUAL_TIME'	=> time(),
				'VARS'			=> md5(serialize(self::$vars) . serialize($this->tplVars)),
				'FILE'			=> md5($this->getPHPContent()),
			);
		}
		return self::$aCache['PHP'][ $sKey ];
	}
	private function hasToCompilePHP () {
		$aCache	= $this->getPHPCacheMode();
		$iMode	= $aCache['TYPE'];
		
		if ( $iMode&self::CACHE_ALWAYS )																								return true;
		if ( $iMode&self::CACHE_NEVER )																									return false;
		if ( ($iMode&self::CACHE_ON_TIME) && (time()>$aCache['TIME']+$aCache['ACTUAL_TIME']) )											return true;
		if ( ($iMode&self::CACHE_ON_VARIABLES_CHANGE) && ($aCache['VARS']!=md5(serialize(self::$vars) . serialize($this->tplVars))) )	return true;
		if ( ($iMode&self::CACHE_ON_FILE_CHANGE) && ($aCache['FILE']!=md5($this->getPHPContent())) )									return true;
		
		return false;
	}
	
	// HTML //
	public function setHTMLContent ($sHTMLContent ) {
		$this->sHTMLContent	= $sHTMLContent;
	}
	public function getHTMLContent () {
		return $this->sHTMLContent;
	}
	
	// Compilations :
	private function compilePHP () {
		// recuperation des regs :
		$aPattern		= self::getREGPattern();
		$aReplacement	= self::getREGReplacement();
		
		// recuperation du template :
		$sTemp			= $this->getPHPContent();
		
		// modification :
		$sPHPTempContain = preg_replace($aPattern, $aReplacement, $sTemp);
		$this->setPHPContent( $sPHPTempContain );
		
		// On sauvegarde le fichier :
		$sKey = $this->getKey();
		$rFile = fopen(self::getCachePath().'/php/'.$sKey, 'w+');
		fputs($rFile, $sPHPTempContain);
		fclose($rFile);
		
		// MAJ des donnes cache :
		$aCache = $this->getPHPCacheMode();
		self::$aCache['PHP'][ $sKey ] = array(
			'TYPE'			=> $aCache['TYPE'],
			'TIME'			=> $aCache['TIME'],
			'ACTUAL_TIME'	=> time(),
			'VARS'			=> md5(serialize(self::$vars) . serialize($this->tplVars)),
			'FILE'			=> md5($this->getPHPContent()),
		);
	}
	
	private function compileHTML () {
		// On verifi que le fichier existe :
		$sKey = $this->getKey();
		
		$sHTMLFile = self::getCachePath().'/php/'.$sKey;
		if ( ! file_exists($sHTMLFile) ) {
			$this->compilePHP();
		}
		if ( ! file_exists($sHTMLFile) ) {
			throw new Exception('Impossible de créer le fichier template dans le dssier de cache PHP.');
		}
		
		// On recupere le contenu :
		ob_start();
		$this->realCompile($sHTMLFile);
		$sHTMLContent = ob_get_contents();
		ob_end_clean();
		$this->setHTMLContent( $sHTMLContent );
	}
	
	final private function realCompile ($FILE_NAME) {
		// Ressort toutes les variables du template puis inclu le php :
		foreach ( self::$vars as $___k => $___v ) {
			$$___k = $___v;
		}
		
		foreach ( $this->tplVars as $___k => $___v ) {
			$$___k = $___v;
		}
		
		unset($___k);
		unset($___v);
		
		include( $FILE_NAME );
	}
	
	public function display ($bGetContent=false) {
		$sContent = $this->getPHPContent();
		if ( $sContent === null ) {
			throw new Exception('le contenu du template n\'est pas défini.');
		}
		
		if ( $this->hasToCompilePHP() ) {
			$this->compilePHP();
		}
		$this->compileHTML();
		self::saveCache();
		if ( $bGetContent ) {
			return $this->getHTMLContent();
		} else {
			echo $this->getHTMLContent();
		}
	}
	
	public function __toString() {
		return $this->display(true);
	}
	
	// Implements Func :
	public function offsetExists($k){
		if(isset($this)){
			return isset($this->tplVars[$k]);
		}else{
			return isset(self::$vars[$k]);
		}
	}
	public function offsetGet($k){
		if(isset($this)){
			return $this->tplVars[$k];
		}else{
			return self::$vars[$k];
		}
	}
	public function offsetSet($k,$v){
		if(isset($this)){
			return $this->tplVars[$k]=$v;
		}else{
			return self::$vars[$k]=$v;
		}
	}
	public function offsetUnset($k){
		if(isset($this)){
			unset($this->tplVars[$k]);
		}else{
			unset(self::$vars[$k]);
		}
	}
	public function valid(){
		if(isset($this)){
			return array_key_exists(key($this->tplVars),$this->tplVars);
		}else{
			return array_key_exists(key(self::$vars),self::$vars);
		}
	}
	public function next(){
		if(isset($this)){
			next($this->tplVars);
			return $this->tplVars;
		}else{
			next(self::$vars);
			return self::$vars;
		}
	}
	public function rewind(){
		if(isset($this)){
			reset($this->tplVars);
			return $this;
		}else{
			reset(self::$vars);
			return self::$vars;
		}
	}
	public function key(){
		if(isset($this)){
			return key($this->tplVars);
		}else{
			return key(self::$vars);
		}
	}
	public function current(){
		if(isset($this)){
			return current($this->tplVars);
		}else{
			return current(self::$vars);
		}
	}
	public function count(){
		if(isset($this)){
			return count($this->tplVars);
		}else{
			return count(self::$vars);
		}
	}
}
