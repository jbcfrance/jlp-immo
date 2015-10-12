<?php
class Composer_Mysql implements SQLComposerSGBD {
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 23/07/2009
	 *
	 * Name			: private $rDB
	 * Description	: la ressource DB
	 * @return		: Ressource
	*/
	private $rDB = null;
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private function isDigit
	 * Description	: retourne true si le parametre est un digit, sinon false
	 * @params		: - $mV	: misc value
	 * @return		: Boolean
	*/
	private function isDigit ( $mV ) {
		return (	( is_int($mV) )
				||	( is_float($mV) )
				||	( is_double($mV) )
				||	( is_integer($mV) )
				||	( is_long($mV) )
				||	( is_numeric($mV) )
			);
	}
	
	private $oComposer = null;
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function __construct
	 * Description	: Initialise la connexion
	 * @params		: - $aParams	: array(HOST, USER, PASS, BASE)
	 * @return		: void
	*/
	public function __construct ( $aParams ) {
		$rDB = mysql_connect($aParams['HOST'], $aParams['USER'], $aParams['PASS']);
		mysql_select_db($aParams['BASE'], $rDB );
		$this->rDB = $rDB;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected function isOperator
	 * Description	: Verifie si une variable est un operateur pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur, sinon false
	*/
	public function isOperator ( $mTryStr ) {
		return in_array( trim($mTryStr), array('=', '>', '<', '>=', '<=', '<>', '!=', 'LIKE', 'IN', 'NOT LIKE', 'NOT IN') );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isArrayOperator
	 * Description	: Verifie si une variable est une liste d'operateur pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est une liste d'operateur, sinon false
	*/
	private function isArrayOperator ( $mTryArray ) {
		if ( is_array($mTryArray) ) {
			foreach( $mTryArray as $kTryArray => $vTryArray ) {
				if ( ! $this->isOperator( $vTryArray ) ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected function isLiaison
	 * Description	: Verifie si une variable est un operateur de liaison pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur de liaison, sinon false
	*/
	public function isLiaison ( $mTryStr ) {
		return in_array( trim($mTryStr), array('AND', 'OR', 'XOR', 'AND NOT', 'OR NOT', 'XOR NOT' ) );
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected function isJointure
	 * Description	: Verifie si une variable est un operateur de jointure pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur de jointure, sinon false
	*/
	public function isJointure ( $sTryStr ) {
		return in_array( strtoupper(trim($sTryStr)), array('LEFT', 'RIGHT', 'INNER', 'OUTER') );
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public function useBind
	 * Description	: permet d'utilise un bind
	 * Params		: - $sBindName	: nom du bind a utiliser
	 * Params		: - $aVars		: liste des valeurs a remplacer
	 * Return		: true si la requete a bien ete executee, sinon false
	*/
	public function useBind ( $sBindName, $aVars ) {
		$aBind = SQLComposer::getBind($sBindName);
		
		if ( count($aVars) != count($aBind['VARS']) ) {
			return false;
		}
		
		$sReq = $aBind['REQ'];
		
		foreach ( $aBind['VARS'] as $vBindVar ) {
			if ( ! isset($aVars[ $vBindVar ]) ) {
				return false;
			} else {
				$sReq = str_replace( '%'.$vBindVar.'%', $aVars[ $vBindVar ], $sReq);
			}
		}
		
		if ( mysql_query($sReq) ) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private function createBind
	 * Description	: ajoutte un bind
	 * Params		: - $sBindName	: nom de la requete
	 * Params		: - $aParams	: parametres de la requete
	 * Return		: void
	*/
	public function createBind ( $sBindName, $aParams ) {
		SQLComposer::$aBinds[ $sBindName ] = $aParams;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeWhereClause
	 * Description	: compose une clause WHERE
	 * @params		: - $aParams	: liste des parametres de composition de la clause
	 * @return		: - String correspondant a la clause WHERE
	*/
	//	public function composeWhereClause ( $aParams ) {
	public function composeWhereClause ( $sFields, $aValues, $aOperators, $aLinks ) {
		/*
			ici, les params noralement, c'est :
			- String champs / Array valeurs / Array OPERATEUR / String LIAISON
		*/
		$sPattern    = '@([[:alnum:]_\.\(\)]*)([^[:alnum:]_\.\(\)])@isemU';
		$aMatches	= array();
		if (	( is_string($sFields) )
			&&	( is_array($aValues) )
			&&	( $this->isArrayOperator($aOperators) )
			&&	( $this->isLiaison($aLinks) ) ) {
			$aMatches	= array();
			preg_match_all($sPattern, ' '.$sFields.' ', $aMatches);
			
			$sResult = '';
			$iNbOp = 0;
			for ( $i=0, $iMax=count($aMatches[0]) ; $i<$iMax ; $i++ ) {
				if ( trim( $aMatches[1][$i] ) !== '' ) {
					if ( in_array($aMatches[1][$i], array('AND', 'OR', 'XOR')) ) {
						$sResult .= trim( $aMatches[1][$i] ) . $aMatches[2][$i];
					} else {
						$sResult .= trim( $aMatches[1][$i] ).' '.$aOperators[$iNbOp].' %s' . $aMatches[2][$i];
						$iNbOp++;
					}
				} else {
					$sResult .= $aMatches[2][$i];
				}
			}
			
			$aReturn = array();
			if ( is_array($aValues) ) {
				for ( $i=0, $iMax=count($aValues) ; $i<$iMax ; $i++ ) {
					for ( $j=0, $jMax=count($aValues[$i]) ; $j<$jMax ; $j++ ) {
						if ( SQLComposer::is_Field($aValues[$i][$j]) ) {
							$aValues[$i][$j] = $aValues[$i][$j]->getField();
						} else if ( $this->isDigit($aValues[$i][$j]) ){
							$aValues[$i][$j] = $aValues[$i][$j];
						} else if ( is_string($aValues[$i][$j]) ) {
							$aValues[$i][$j] = '\'' . mysql_real_escape_string($aValues[$i][$j]) . '\'';
						} else if ( is_array($aValues[$i][$j]) ) {
							if ( is_string( reset($aValues[$i][$j]) ) ) {
								$aValues[$i][$j] = '(\'' . join($aValues[$i][$j], '\', \'') . '\') ';
							} else {
								$aValues[$i][$j] = '(' . join($aValues[$i][$j], ', ') . ') ';
							}
						} else if ( SQLComposer::is_SQLComposer($aValues[$i][$j]) ) {
							$aValues[$i][$j] = '(' . $aValues[$i][$j]. ') ';
						} else {
							echo 'heuuu chaipo';
						}
					}
					$aReturn[] = ' (' . vsprintf($sResult, $aValues[$i]) . ') ';
				}
				$sReturn = join($aReturn, $aLinks);
				$sReturn = str_replace('  ', ' ',$sReturn);
				return $sReturn;
			}
		}
		// si c'est 1 : on retourne pas une erreur mais bon... faudrai quand même hein merde.
		return $sDefaultReturn;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeUpdateClause
	 * Description	: Compose les clauses SET et VALUE d'une requete UPDATE
	 * @params		: - $aFields	: contenue des champs SET
	 * @params		: - $aValues	: contenue des champs VALUE
	 * @return		: - String correspondant a la clause
	*/
	public function composeUpdateClause ( $aFields, $aValues ) {
		if ( count($aFields) == count($aValues) ) {
			$aReqFields = array();
			for ( $i=0, $iMax=count($aFields) ; $i<$iMax ; $i++ ) {
				$sStrQ = $aFields[$i].'=';
				
				if ( SQLComposer::is_Field($aValues[$i]) ) {
					$sStrQ .= $aValues[$i]->getField();
				} else if ( $this->isDigit($aValues[$i]) ){
					$sStrQ .= $aValues[$i];
				} else if ( is_string($aValues[$i]) ) {
					$sStrQ .= '\'' . mysql_real_escape_string($aValues[$i]) . '\'';
				} else if ( SQLComposer::is_SQLComposer($aValues[$i]) ) {
					$sStrQ .= '(' . $aValues[$i]->get() . ') ';
				} else {
					return false;
				}
				$aReqFields[] = $sStrQ;
			}
			return join($aReqFields, ', ');
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeInsertClause
	 * Description	: Compose les clauses SET et VALUE d'une requete INSERT
	 * @params		: - $aFields	: contenue des champs SET
	 * @params		: - $aValues	: contenue des champs VALUE
	 * @return		: - String correspondant a la clause
	*/
	public function composeInsertClause ( $aFields, $aValues ) {
		if ( count($aFields) == count($aValues) ) {
			for ( $i=0, $iMax=count($aFields) ; $i<$iMax ; $i++ ) {
				if ( $this->isDigit($aValues[$i]) ){
					$aValues[$i] = $aValues[$i];
				} else if ( is_string($aValues[$i]) ) {
					$aValues[$i] = '\'' . mysql_real_escape_string($aValues[$i]) . '\'';
				} else if ( SQLComposer::is_SQLComposer($aValues[$i]) ) {
					$aValues[$i] = '(' . $aValues[$i]->get() . ') ';
				} else if ( SQLComposer::is_field($aValues[$i]) ) {
					$aValues[$i] = $aValues[$i]->getField();
				} else {
					return false;
				}
				$aReqFields[] = $sStrQ;
			}
			
			return ' ( ' . join($aFields, ', ') . ' ) VALUES ( '. join($aValues, ', ') . ' )';
		}
		return false;
	}
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function compose
	 * Description	: Compose une requete SQL
	 * @return		: - retourne une requete SQL
	*/
	public function compose ( $oComposer ) {
		$aRequest = $oComposer->getRequestElement();
		reset($aRequest);
		$sTableName = call_user_func(array(get_class($oComposer), 'getTableName'));
		switch ( $oComposer->getType() ) {
			case SQLComposer::$TYPE_REQ_SELECT	: {
				$sStrQ							= '';
				$oComposer->aTablesOpeneds		= array();
				$aTablesOpeneds[ $sTableName ]	= $oComposer->getObjectClassName($sTableName);
				$oComposer->aTablesOpeneds[]	= $oComposer->getObjectClassName($sTableName);
				$bOnIsOpen						= false;
				$bWhereIsOpen					= false;
				for ( $i=0, $iMax=count($aRequest) ; $i<$iMax ; $i++ ) {
					$aClause = $aRequest[$i];
					
					switch ( $aClause['TYPE'] ) {
						case 'SELECT' :		{
							$sStrQ = 'SELECT ';
							if ( $aRequest[$i+1]['TYPE'] == 'DISTINCT' ) {
								$sStrQ .= ' DISTINCT ';
							}
							$aFieldsSelecteds = array();
							if ( count($aClause['PARAMS']) > 0 ) {
								for ( $j=0, $jMax=count($aClause['PARAMS']) ; $j<$jMax ; $j++ ) {
									if ( empty($aClause['PARAMS'][$j]) ) {
										$aFieldsSelecteds[] = ' *';
									} else if ( is_array($aClause['PARAMS'][$j]) ) {
										$aFieldsSelecteds[] = ' '.join($aClause['PARAMS'][$j], ', ');
									} else if ( is_string($aClause['PARAMS'][$j]) ) {
										$aFieldsSelecteds[] = ' '.$aClause['PARAMS'][$j];
									} else if ( SQLComposer::is_field($aClause['PARAMS'][$j]) ) {
										$aFieldsSelecteds[] = ' '.$aClause['PARAMS'][$j]->getField();
									}
								}
							} else {
								$aFieldsSelecteds[] = ' *';
							}
							$sStrQ .= join($aFieldsSelecteds, ', ') . ' FROM '.
								call_user_func( array( SQLComposer::getTableClassName($sTableName), 'getTableName'));
						} break;
						case 'DISTINCT' :	{
						} break;
						case 'JOIN' :		{
							if ( $this->isJointure($aClause['PARAMS'][1]) ) {
								$sStrQ .= ' '.$aClause['PARAMS'][1];
							} else if ( ( isset($aClause['PARAMS'][1]) ) && ($this->isJointure($aClause['PARAMS'][1][0]) ) ) {
								$sStrQ .= ' '.$aClause['PARAMS'][1][0];
							} 
							
							$sStrQ .= ' JOIN ';
							if ( is_string($aClause['PARAMS'][0]) ) {
								$aTablesOpeneds[ $aClause['PARAMS'][0] ] = $aClause['PARAMS'][0];
								$oComposer->aTablesOpeneds[] = $oComposer->getObjectClassName($aClause['PARAMS'][0]);
								$sStrQ .= $aClause['PARAMS'][0];
							} else if ( SQLComposer::is_SQLComposer($aClause['PARAMS'][0]) ) {
								$sStrQ .= '( ' . $aClause['PARAMS'][0]->get() . ' )';
							} else {
								throw new Exception('Type Error');
							}
							$bOnIsOpen = false;
						} break;
						case 'ALIAS' :		{
							if ( is_string($aClause['PARAMS'][0]) ) {
								$sStrQ .= ' ' . $aClause['PARAMS'][0];
								
								end($aTablesOpeneds);
								$sLastOpened = key( $aTablesOpeneds );
								$aTablesOpeneds[ $sLastOpened ] = $oComposer->getObjectClassName($aClause['PARAMS'][0]);
								
							} else {
								throw new Exception('Type Error');
							}
						} break;
						case 'ON' :			{
							$sStrQ .= ' ON ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'WHERE' :		{
							$sStrQ .= ' WHERE ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'GROUPBY' :	{
							if ( count($aClause['PARAMS']) > 0 ) {
								$sStrQ .= ' GROUP BY '. join($aClause['PARAMS'], ', ');
							}
						} break;
						case 'ORDERBY' :	{
							if ( count($aClause['PARAMS']) > 0 ) {
								$sStrQ .= ' ORDER BY '. join($aClause['PARAMS'], ', ');
							}
						} break;
						case 'LIMIT' :		{
							if ( count($aClause['PARAMS']) == 1 ) {
								$sStrQ .= ' LIMIT '. $aClause['PARAMS'][0];
							} else if ( count($aClause['PARAMS']) == 2 ) {
								$sStrQ .= ' LIMIT '. $aClause['PARAMS'][0].', '.$aClause['PARAMS'][1];
							} else {
								throw new Exception('Erreur sur la limit');
							}
						} break;
						case 'AND' :		{
							$sStrQ .= ' AND ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'OR' :			{
							$sStrQ .= ' OR ' .SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'XOR' :		{
							$sStrQ .= ' XOR ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						default : {
							echo'<pre>';print_r($aClause);echo'</pre>';
						}
					}
				}
			} break;
			case SQLComposer::$TYPE_REQ_INSERT		: {
				$aFieldsToUpdate	= array();
				$aValuesToUpdate	= array();
				
				for ( $i=0, $iMax=count($aRequest) ; $i<$iMax ; $i++ ) {
					$aClause = $aRequest[$i];
					
					switch ( $aClause['TYPE'] ) {
						case 'INSERT' :	{
							$sStrQ = 'INSERT INTO ' . $sTableName;
						} break;
						case 'SET' :	{
							foreach ( $aClause['PARAMS'] as $vField ) {
								if ( is_array($vField) ) {
									foreach ( $vField as $vFieldName ) {
										$aFieldsToUpdate[] = $vFieldName;
									}
								} else if ( is_string($vField) ) {
									$aFieldsToUpdate[] = $vField;
								}
							}
						} break;
						case 'VALUES' :	{
							if ( ( count($aClause['PARAMS'])==1) && (SQLComposer::is_SQLComposer($aClause['PARAMS'][0]) ) ) {
								$sStrQ .= ' ( '. join($aFieldsToUpdate, ', ') . ') (' . $aClause['PARAMS'][0]->get() . ' ) ';
							} else {
								foreach ( $aClause['PARAMS'] as $vField ) {
									if ( is_array($vField) ) {
										foreach ( $vField as $vFieldName ) {
											$aValuesToUpdate[] = $vFieldName;
										}
									} else {
										$aValuesToUpdate[] = $vField;
									}
								}
								$sStrQ .= $this->composeInsertClause($aFieldsToUpdate, $aValuesToUpdate);
							}
						} break;
						default : {
							echo'<pre>';print_r($aClause);echo'</pre>';
						}
					}
				}
				return $sStrQ;
			} break;
			case SQLComposer::$TYPE_REQ_UPDATE		: {
				$aFieldsToUpdate	= array();
				$aValuesToUpdate	= array();
				$bHasWhereClause	= false;
				
				for ( $i=0, $iMax=count($aRequest) ; $i<$iMax ; $i++ ) {
					$aClause = $aRequest[$i];
					
					switch ( $aClause['TYPE'] ) {
						case 'UPDATE' :	{
							$sStrQ = 'UPDATE ' . $sTableName;
						} break;
						case 'SET' :	{
							foreach ( $aClause['PARAMS'] as $vField ) {
								if ( is_array($vField) ) {
									foreach ( $vField as $vFieldName ) {
										$aFieldsToUpdate[] = $vFieldName;
									}
								} else if ( is_string($vField) ) {
									$aFieldsToUpdate[] = $vField;
								}
							}
						} break;
						case 'VALUES' :	{
							foreach ( $aClause['PARAMS'] as $vField ) {
								if ( is_array($vField) ) {
									foreach ( $vField as $vFieldName ) {
										$aValuesToUpdate[] = $vFieldName;
									}
								} else {
									$aValuesToUpdate[] = $vField;
								}
							}
							$sStrQ .= ' SET '.$this->composeUpdateClause($aFieldsToUpdate, $aValuesToUpdate);
						} break;
						case 'WHERE' :		{
							$bHasWhereClause = true;
							$sStrQ .= ' WHERE ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'LIMIT' :		{
							if ( count($aClause['PARAMS']) == 1 ) {
								$sStrQ .= ' LIMIT '. $aClause['PARAMS'][0];
							} else if ( count($aClause['PARAMS']) == 2 ) {
								$sStrQ .= ' LIMIT '. $aClause['PARAMS'][0].', '.$aClause['PARAMS'][1];
							} else {
								throw new Exception('Erreur sur la limit');
							}
						} break;
						case 'AND' :		{
							$sStrQ .= ' AND ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'OR' :			{
							$sStrQ .= ' OR ' .SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'XOR' :		{
							$sStrQ .= ' XOR ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						default : {
							echo'<pre>';print_r($aClause);echo'</pre>';
						}
					}
				}
				return $sStrQ;
			} break;
			case SQLComposer::$TYPE_REQ_DELETE		: {
				for ( $i=0, $iMax=count($aRequest) ; $i<$iMax ; $i++ ) {
					$aClause = $aRequest[$i];
					switch ( $aClause['TYPE'] ) {
						case 'DELETE' :	{
							$sStrQ = 'DELETE FROM ' . $sTableName;
						} break;
						case 'WHERE' :		{
							$bHasWhereClause = true;
							$sStrQ .= ' WHERE ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'LIMIT' :		{
							if ( count($aClause['PARAMS']) == 1 ) {
								$sStrQ .= ' LIMIT '. $aClause['PARAMS'][0];
							} else if ( count($aClause['PARAMS']) == 2 ) {
								$sStrQ .= ' LIMIT '. $aClause['PARAMS'][0].', '.$aClause['PARAMS'][1];
							} else {
								throw new Exception('Erreur sur la limit');
							}
						} break;
						case 'AND' :		{
							$sStrQ .= ' AND ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'OR' :			{
							$sStrQ .= ' OR ' .SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						case 'XOR' :		{
							$sStrQ .= ' XOR ' . SQLComposer::composeWhereClause($aClause['PARAMS']);
						} break;
						default : {
							echo'<pre>';print_r($aClause);echo'</pre>';
						}
					}
				}
				
			} break;
			case SQLComposer::$TYPE_REQ_REPLACE		: {
			} break;
			case SQLComposer::$TYPE_REQ_SHOW		: {
			} break;
		}
		
		return str_replace('  ', ' ', $sStrQ);
	}

	public function query ( $sSqlQuery ) {
		return mysql_query( $sSqlQuery );
	}
	
	public function getArray ( $rSQL ) {
		return mysql_fetch_array( $rSQL );
	}
	
	public function getRow ( $rSQL ) {
		return mysql_fetch_row( $rSQL );
	}
	
	public function getAssoc ( $rSQL ) {
		return mysql_fetch_assoc( $rSQL );
	}
	
	public function getLastInsertID () {
		return mysql_insert_id( $this->rDB );
	}
	
	
	
	public function free ( $rSQL ) {
		return mysql_free_result( $rSQL );
	}
	
	public static function getError () {
		return mysql_error();
	}
	
	public static function getErrno () {
		return mysql_errno();
	}
}
?>