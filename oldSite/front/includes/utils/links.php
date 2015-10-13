<?php
class Links {
	const DEFAULT_ACTION = 'default';
	const DEFAULT_METHOD = 'default';
	
	private static $aShortLinks			= array();
	private static $aShortLinksReverse	= array();
	private static $aDefinition			= array();
	private static $bDefined			= false;
	
	public static function reInit(){
		self::$bDefined=false;
		self::definePage();
	}
	
	private static function definePage(){
		if ( self::$bDefined ) {
			return;
		}
		self::$bDefined = true;
		
		// Recuperer ce qui nous interresse
		if ( ! isset($_SERVER['REDIRECT_URL']) ) {
			// On est passe directement par l'index.php
			$sRequestURI = substr($_SERVER['PATH_INFO'], 1);
		} else {
			// On a mis un lien :
			$sRequestURI = substr(urldecode($_SERVER['REQUEST_URI']),strrpos($_SERVER['SCRIPT_NAME'],'/')+1);
		}
		// Effacer les parametres en GET :
		if ( strchr($sRequestURI, '?') ) {
			$sRequestURI = substr($sRequestURI, 0, strpos($sRequestURI, '?') );
		}
		
		// Re-encodage de l'url (pour eviter les problemes de caracteres speciaux) :
		$sRealURL = HTML_ROOT_PATH.$sRequestURI;
		
		// Priorite aux parametres definis en dur par l'utilisateur :
		foreach ( self::$aShortLinksReverse as $aLinkDefinition ) {
			$bMatch = false;
			$sLink = str_replace('%s', '([^/]*)', $aLinkDefinition['TEMPLATE']);
			
			preg_match('@'.$sLink.'@', $sRealURL, $aMatches1);
			preg_match('@'.$sLink.'/@', $sRealURL, $aMatches2);
			
			if ( count($aMatches1) ) {
				// On a matche le bon link :
				$bMatch = true;
			} else if ( count($aMatches2) ) {
				// On a matche le link mais en rajouttant un /
				$bMatch = true;
				$sLink .= '/';
			}
			
			if ( $bMatch ) {
				$aMatches = array();
				preg_match('@^'.$sLink.'$@iU', $sRealURL, $aMatches);
				
				if ( count($aMatches) == count($aLinkDefinition['PARAMS'])+1 ) {
					// La, on est sur d'etre bon :
					$aParams = array();
					for ( $i=1,$iMax=count($aMatches) ; $i<$iMax ; $i++ ) {
						$aParams[ $aLinkDefinition['PARAMS'][$i-1] ] = $aMatches[$i];
					}
					
					self::$aDefinition = array(
						'ACTION'	=> $aLinkDefinition['ACTION'],
						'METHOD'	=> $aLinkDefinition['METHOD'],
						'TEMPLATE'	=> $aLinkDefinition['TEMPLATE'],
						'PARAMS'	=> $aParams,
					);
					return;
				}
			}
		}
		
		$aRequest = explode('/', $sRequestURI);
		// On va tente de voir si ca pourrait pas etre quelques chose d'approximatif :
		
		// D'abord, on fait un peu de nettoyage :
		if ( trim($aRequest[0]) == '' ) {
			array_shift($aRequest);
		}
		if ( count($aRequest) == 0 ) {
			$aRequest[0] = 'default';
		}
		if ( trim($aRequest[0]) == '' ) {
			$aRequest[0] = 'default';
		}
		if ( count($aRequest) == 1 ) {
			$aRequest[1] = 'default';
		}
		if ( trim($aRequest[1]) == '' ) {
			$aRequest[1] = 'default';
		}
		
		if ( trim(end($aRequest)) == '' ) {
			array_pop($aRequest);
		}
		
		foreach ( self::$aShortLinksReverse as $aLinkDefinition ) {
			$sLink = str_replace('%s', '([^/]*)', $aLinkDefinition['TEMPLATE']);
			preg_match('@'.$sLink.'/([^/]*)*@', $sRealURL, $aMatches1 );
			preg_match('@'.$sLink.'([^/]*)*@', $sRealURL, $aMatches2);
			if ( count($aMatches1) ) {
				// On a matche un link qui pourrait etre bon (si on rajoutte des params et un slash) :
				$bMatch = true;
				$sLink .= '/';
			} else if ( count($aMatches2) ) {
				// On a matche un link qui pourrait etre bon (si on rajoutte des params) :
				$bMatch = true;
			}
			
			
			if ( $bMatch ) {
				$sTempURL = preg_replace('@'.$sLink.'@', '', $sRealURL);
				
				$aMatches1 = array();
				preg_match('@^'.$sLink.'@iU', $sRealURL, $aMatches1);
				$aMatches2 = explode('/', $sTempURL);
				$aMatches = array_merge($aMatches1, $aMatches2);
				
				if ( count($aMatches) >= count($aLinkDefinition['PARAMS'])+1 ) {
					// La, on est sur d'etre bon :
					$aParams = array();
					for ( $i=1,$iMax=count($aMatches) ; $i<$iMax ; $i++ ) {
						if ( isset($aLinkDefinition['PARAMS'][$i-1]) ) {
							$aParams[ $aLinkDefinition['PARAMS'][$i-1] ] = $aMatches[$i];
						} else {
							if ( $i<$iMax-1 || trim($aMatches[$i]) != '' ) {
								$aParams[] = $aMatches[$i];
							}
						}
					}
					
					self::$aDefinition = array(
						'ACTION'	=> $aLinkDefinition['ACTION'],
						'METHOD'	=> $aLinkDefinition['METHOD'],
						'TEMPLATE'	=> $aLinkDefinition['TEMPLATE'],
						'PARAMS'	=> $aParams,
					);
					return;
				}
			}
		}
		
		// Bon bahh maintenant, on va juste passer en normal (voir sur default/default) :
		$aActionClasses = array_change_key_case( $GLOBALS['ACTIONS'], CASE_LOWER);
		if ( isset($aActionClasses[ strtolower($aRequest[0].'action') ]) && file_exists(ACTION_PATH.$aActionClasses[ strtolower($aRequest[0].'action') ].'.php') ) {
			$aMethods = get_class_methods( $aRequest[0].'action' );
			array_walk($aMethods, create_function('&$v, $k', '$v=strtolower($v);') );
			if ( in_array( strtolower('do'.$aRequest[1]), $aMethods) ) {
				self::$aDefinition = array(
					'ACTION'	=> $aRequest[0],
					'METHOD'	=> $aRequest[1],
					'TEMPLATE'	=> '',
					'PARAMS'	=> array_slice($aRequest, 2),
				);
				return;
			} else {
				self::$aDefinition = array(
					'ACTION'	=> $aRequest[0],
					'METHOD'	=> 'default',
					'TEMPLATE'	=> '',
					'PARAMS'	=> array_slice($aRequest, 1),
				);
				return;
			}
		}
		
		self::$aDefinition = array(
			'ACTION'	=> 'default',
			'METHOD'	=> 'default',
			'TEMPLATE'	=> '',
			'PARAMS'	=> array(),
		);
		
		/*
		if ( ! self::$bDefined ) {
			if ( isset($_SERVER['REDIRECT_URL']) ) {
				$sAddURL = '/index.php';
				if ( defined('DEV_VUE_LOADED') ) {
					$sAddURL = '/dev.php';
				}
			} else {
				if ( defined('DEV_VUE_LOADED') ) {
					$sAddURL = '/dev.php';
				} else {
					$sAddURL = '';
				}
			}
			
			$sRequestURI = $sAddURL.$_SERVER['REDIRECT_URL'];
			$sRequestURI = eregi_replace('[\?\&]'.PARAM_GET_CONTENT.'=(.*)', '', $sRequestURI);
			$aRequest = explode('/', $sRequestURI);
			$aRequest = array_splice($aRequest, 3);
			
			if(count($aRequest)==2){
				$GLOBALS['ACTIONS']=array_change_key_case($GLOBALS['ACTIONS'],CASE_LOWER);
				if(isset($GLOBALS['ACTIONS'][strtolower($aRequest[0].'action')])){
					$sClass=strtolower($aRequest[0].'action');
					if(in_array('do'.$aRequest[1],array_map('strtolower',get_class_methods($sClass)))){
						self::$aDefinition=array(
							'ACTION'=>$aRequest[0],
							'METHOD'=>$aRequest[1],
							'TEMPLATE'=>$sDirectLink,
							'PARAMS'=>array(),
						);
						self::$bDefined=true;
						return;
					}
				}
			}
			
			$sDirectLink = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			$sDirectLink = eregi_replace('[\?\&]'.PARAM_GET_CONTENT.'=(.*)', '', $sDirectLink);
			
			if ( defined('DEV_VUE_LOADED') ) {
				$sDirectLink = str_replace('/dev.php', '', $sDirectLink);
			}
			if(strrpos($sDirectLink,'/')==strlen($sDirectLink)-1){
				$sDirectLink = substr($sDirectLink,0,strlen($sDirectLink)-1);
			}
			$sDirectLink = urldecode( $sDirectLink );
			
			foreach ( self::$aShortLinksReverse as $sLink => $aValues ) {
				$sPattern = str_replace('%s', '([^/]*)', $aValues['TEMPLATE']);
				$aMatches = array();
				preg_match('@^'.$sPattern.'$@iU', $sDirectLink, $aMatches);
				
				if ( count($aMatches)>0 ) {
					self::$aDefinition = $aValues;
					$aParams = $aValues['PARAMS'];
					$aRequestParams = array();
					if ( count($aParams)==count($aMatches)-1 ) {
						for($i=0,$iMax=count($aParams);$i<$iMax;$i++){
							$aRequestParams[ $aParams[$i] ] = $aMatches[$i+1];
						}
					}
					self::$aDefinition['PARAMS'] = $aRequestParams;
					self::$bDefined=true;
					return;
				}
			}
			
			if ( (isset($aRequest[0])) && (trim($aRequest[0])!='') ) {
				$sAction = $aRequest[0];
			} else {
				$sAction = 'default';
			}
			if ( ! isset($GLOBALS['AUTO_LOAD'][strtolower($sAction).'action']) ) {
				$sAction = 'default';
			}
			if ( (isset($aRequest[1])) && (trim($aRequest[1])!='') ) {
				$sMethod = $aRequest[1];
			} else {
				$sMethod = 'default';
			}
			
			$aMethods = get_class_methods($sAction.'action');
			foreach($aMethods as &$sMethodClass)$sMethodClass=strtolower($sMethodClass);
			if ( ! in_array(strtolower('do'.$sMethod), $aMethods) ) {
				$sMethod = 'default';
			}
			
			$aParams = array_splice($aRequest, 2);
			
			self::$aDefinition = array(
				'ACTION'	=> $sAction,
				'METHOD'	=> $sMethod,
				'TEMPLATE'	=> $sDirectLink,
				'PARAMS'	=> $aParams,
			);
			
			self::$bDefined = true;
		}
		*/
	}
	/*
	private static function definePage(){
		if ( self::$bDefined ) {
			return;
		}
		self::$bDefined = true;
		
		// Recuperer ce qui nous interresse
		if ( ! isset($_SERVER['REDIRECT_URL']) ) {
			// On est passe directement par l'index.php
			$sRequestURI = substr($_SERVER['PATH_INFO'], 1);
		} else {
			// On a mis un lien :
			$sRequestURI = substr(urldecode($_SERVER['REQUEST_URI']),strrpos($_SERVER['SCRIPT_NAME'],'/')+1);
		}
		// Effacer les parametres en GET :
		if ( strchr($sRequestURI, '?') ) {
			$sRequestURI = substr($sRequestURI, 0, strpos($sRequestURI, '?') );
		}
		
		// Re-encodage de l'url (pour eviter les problemes de caracteres speciaux) :
		$sRealURL = HTML_ROOT_PATH.$sRequestURI;
		
		// Priorite aux parametres definis en dur par l'utilisateur :
		foreach ( self::$aShortLinksReverse as $aLinkDefinition ) {
			$bMatch = false;
			$sLink = str_replace('%s', '([^/]*)', $aLinkDefinition['TEMPLATE']);
			if ( ereg($sLink, $sRealURL) ) {
				// On a matche le bon link :
				$bMatch = true;
			} else if ( ereg($sLink.'/', $sRealURL) ) {
				// On a matche le link mais en rajouttant un /
				$bMatch = true;
				$sLink .= '/';
			}
			
			if ( $bMatch ) {
				$aMatches = array();
				preg_match('@^'.$sLink.'$@iU', $sRealURL, $aMatches);
				
				if ( count($aMatches) == count($aLinkDefinition['PARAMS'])+1 ) {
					// La, on est sur d'etre bon :
					$aParams = array();
					for ( $i=1,$iMax=count($aMatches) ; $i<$iMax ; $i++ ) {
						$aParams[ $aLinkDefinition['PARAMS'][$i-1] ] = $aMatches[$i];
					}
					
					self::$aDefinition = array(
						'ACTION'	=> $aLinkDefinition['ACTION'],
						'METHOD'	=> $aLinkDefinition['METHOD'],
						'TEMPLATE'	=> $aLinkDefinition['TEMPLATE'],
						'PARAMS'	=> $aParams,
					);
					return;
				}
			}
		}
		
		$aRequest = explode('/', $sRequestURI);
		// On va tente de voir si ca pourrait pas etre quelques chose d'approximatif :
		
		// D'abord, on fait un peu de nettoyage :
		if ( trim($aRequest[0]) == '' ) {
			array_shift($aRequest);
		}
		if ( count($aRequest) == 0 ) {
			$aRequest[0] = 'default';
		}
		if ( trim($aRequest[0]) == '' ) {
			$aRequest[0] = 'default';
		}
		if ( count($aRequest) == 1 ) {
			$aRequest[1] = 'default';
		}
		if ( trim($aRequest[1]) == '' ) {
			$aRequest[1] = 'default';
		}
		
		if ( trim(end($aRequest)) == '' ) {
			array_pop($aRequest);
		}
		
		foreach ( self::$aShortLinksReverse as $aLinkDefinition ) {
			$sLink = str_replace('%s', '([^/]*)', $aLinkDefinition['TEMPLATE']);
			if ( ereg($sLink.'/([^/]*)*', $sRealURL) ) {
				// On a matche un link qui pourrait etre bon (si on rajoutte des params et un slash) :
				$bMatch = true;
				$sLink .= '/';
			} else if ( ereg($sLink.'([^/]*)*', $sRealURL) ) {
				// On a matche un link qui pourrait etre bon (si on rajoutte des params) :
				$bMatch = true;
			}
			
			
			if ( $bMatch ) {
				$sTempURL = ereg_replace($sLink, '', $sRealURL);
				
				$aMatches1 = array();
				preg_match('@^'.$sLink.'@iU', $sRealURL, $aMatches1);
				$aMatches2 = explode('/', $sTempURL);
				$aMatches = array_merge($aMatches1, $aMatches2);
				
				if ( count($aMatches) >= count($aLinkDefinition['PARAMS'])+1 ) {
					// La, on est sur d'etre bon :
					$aParams = array();
					for ( $i=1,$iMax=count($aMatches) ; $i<$iMax ; $i++ ) {
						if ( isset($aLinkDefinition['PARAMS'][$i-1]) ) {
							$aParams[ $aLinkDefinition['PARAMS'][$i-1] ] = $aMatches[$i];
						} else {
							if ( $i<$iMax-1 || trim($aMatches[$i]) != '' ) {
								$aParams[] = $aMatches[$i];
							}
						}
					}
					
					self::$aDefinition = array(
						'ACTION'	=> $aLinkDefinition['ACTION'],
						'METHOD'	=> $aLinkDefinition['METHOD'],
						'TEMPLATE'	=> $aLinkDefinition['TEMPLATE'],
						'PARAMS'	=> $aParams,
					);
					return;
				}
			}
		}
		
		// Bon bahh maintenant, on va juste passer en normal (voir sur default/default) :
		$aActionClasses = array_change_key_case( $GLOBALS['ACTIONS'], CASE_LOWER);
		if ( isset($aActionClasses[ strtolower($aRequest[0].'action') ]) && file_exists(ACTION_PATH.$aActionClasses[ strtolower($aRequest[0].'action') ].'.php') ) {
			$aMethods = get_class_methods( $aRequest[0].'action' );
			array_walk($aMethods, create_function('&$v, $k', '$v=strtolower($v);') );
			if ( in_array( strtolower('do'.$aRequest[1]), $aMethods) ) {
				self::$aDefinition = array(
					'ACTION'	=> $aRequest[0],
					'METHOD'	=> $aRequest[1],
					'TEMPLATE'	=> '',
					'PARAMS'	=> array_slice($aRequest, 2),
				);
				return;
			} else {
				self::$aDefinition = array(
					'ACTION'	=> $aRequest[0],
					'METHOD'	=> 'default',
					'TEMPLATE'	=> '',
					'PARAMS'	=> array_slice($aRequest, 1),
				);
				return;
			}
		}
		
		self::$aDefinition = array(
			'ACTION'	=> 'default',
			'METHOD'	=> 'default',
			'TEMPLATE'	=> '',
			'PARAMS'	=> array(),
		);
	}
	*/
	public static function getActualAction(){
		self::definePage();
		return self::$aDefinition['ACTION'];
	}
	public static function getActualMethod(){
		self::definePage();
		return self::$aDefinition['METHOD'];
	}
	public static function getActualParams(){
		self::definePage();
		return self::$aDefinition['PARAMS'];
	}
	
	public static function createShortLink ($sTemplate, $sAction, $sMethod, $aParams=array()) {
		if ( !isset(self::$aShortLinks[$sAction]) ) {
			self::$aShortLinks[$sAction] = array();
		}
		if ( !isset(self::$aShortLinks[$sAction][$sMethod]) ) {
			self::$aShortLinks[$sAction][$sMethod] = array();
		}
		self::$aShortLinks[$sAction][$sMethod][] = array(
			'TEMPLATE'	=> $sTemplate,
			'PARAMS'	=> $aParams,
		);
		
		self::$aShortLinksReverse[] = array(
			'ACTION'	=> $sAction,
			'METHOD'	=> $sMethod,
			'TEMPLATE'	=> $sTemplate,
			'PARAMS'	=> $aParams,
		);
	}
	
	public static function initFromTextFile($sFile) {
		$aDefinitions = file($sFile);
		foreach($aDefinitions as $sLine){
			
			if (substr($sLine,0,1)==='#')continue;
			if (trim($sLine)==='')continue;
			
			$aLineDef = explode('	',$sLine);
			if ( ! isset($aLineDef[1]) )throw new Exception('Probleme dans le fichier de definition des liens.');
			if ( ! isset($aLineDef[2]) )$aLineDef[2] = 'default';
			if ( ! isset($aLineDef[3]) )$aLineDef[3] = '';
			
			Links::createShortLink(
						HTML_ROOT_PATH.$aLineDef[0],
						$aLineDef[1],
						$aLineDef[2],
						explode(',', trim($aLineDef[3]) )
					);
		}
	}
	
	public $sAction = self::DEFAULT_ACTION;
	public $sMethod = self::DEFAULT_METHOD;
	public $aParams = array();
	
	public function __construct ($sAction='', $sMethod='', $aParams=array()) {
		$this->setAction($sAction);
		$this->setMethod($sMethod);
		$this->setParams($aParams);
	}
	
	public function setAction($sAction){
		$this->sAction = $sAction;
	}
	public function getAction(){
		if ( defined('DEV_VUE_LOADED') ) {
			return $this->sAction;
		}
		return $this->sAction;
	}
	
	public function setMethod($sMethod){
		$this->sMethod = $sMethod;
	}
	public function getMethod(){
		return $this->sMethod;
	}
	
	public function setParams($aParams){
		$this->aParams = $aParams;
	}
	public function getParams(){
		return $this->aParams;
	}
	
	public function __toString() {
		$sAction = $this->getAction();
		$sMethod = $this->getMethod();
		$aParams = $this->getParams();
		
		if ( isset(self::$aShortLinks[$sAction], self::$aShortLinks[$sAction][$sMethod]) ) {
			foreach(self::$aShortLinks[$sAction][$sMethod] as $aLink ) {
				$aParamsLinks = $aLink['PARAMS'];
				$bValid=true;
				foreach ( $aParamsLinks as $sParam ) {
					if (!isset($aParams[$sParam])) {
						$bValid=false;
					}
				}
				foreach ( $aParams as $sParam => $sValue ) {
					if (!in_array($sParam, $aParamsLinks)) {
						$bValid=false;
					}
				}
				// Si le lien correspond :
				if($bValid) {
					if ( defined('DEV_VUE_LOADED') ) {
						$aLink['TEMPLATE'] = str_replace(HTML_ROOT_PATH.'index.php/', HTML_ROOT_PATH.'dev.php/',$aLink['TEMPLATE']);
					}
					$aLink['TEMPLATE'] = str_replace(HTML_ROOT_PATH.'index.php/', HTML_ROOT_PATH, $aLink['TEMPLATE']);
					
					return vsprintf($aLink['TEMPLATE'], $aParams);
				}
			}
		}
		
		if ( self::DEFAULT_METHOD==$sMethod && empty($aParams) ) {
			return HTML_ROOT_PATH.$sAction;
		} else if ( self::DEFAULT_METHOD==$sMethod ) {
			return HTML_ROOT_PATH.$sAction.'/'.join($aParams,'/');
		} else if ( empty($aParams) ) {
			return HTML_ROOT_PATH.$sAction.'/'.$sMethod;
		} else {
			return HTML_ROOT_PATH.$sAction.'/'.$sMethod.'/'.join($aParams,'/');
		}
	}
}
?>