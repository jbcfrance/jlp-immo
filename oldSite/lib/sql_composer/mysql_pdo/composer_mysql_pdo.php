<?php
/**
 * @desc        MySQL PDO General queries
 *
 * PHP          5.3
 * @category    SGBD abstraction classes
 * @package     SQLComposer
 * @author      Tavm\@n <tavman\@gmail.com>
 * @license     http://creativecommons.org/licenses/by-nc/2.0/
 * @link        none
 * @see         All
 * @version     SQLComposer v1.0.0
 * @since       2010/12/22
 */

class ComposerMySqlPDO
{
	const	DEFAULT_ALIAS		= 't___';
	const	DEFAULT_MODIFIER	= false;
	
	private static	$bDefaultModifier = null;
	
	/**
	 * Sets the default modifier for all queries.
	 *
	 * @author               Tavm\@n
	 * @param      Bool      TRUE if modifiers are use, else FALSE
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public static function setDefaultModifier ( $bDefaultModifier )
	{
		self::$bDefaultModifier = $bDefaultModifier;
	}
	
	/**
	 * Default modifier for all queries getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if modifiers are use, else FALSE
	 */
	public static function getDefaultModifier ()
	{
		if ( self::$bDefaultModifier===null ) {
			return self::DEFAULT_MODIFIER;
		} else {
			return self::$bDefaultModifier;
		}
	}
	
	/**
	 * Joins types traduction
	 *
	 * @author               Tavm\@n
	 * @param      String    Join type compressed
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Join type
	 */
	public static function tradJoins ( $sJoins )
	{
		$sJoins = trim(strtolower(str_replace('_', '', $sJoins)));
		switch ( strtolower($sJoins) ) {
			case 'left' :				return ' LEFT ';
			case 'right' :				return ' RIGHT ';
			case 'cross' :				return ' CROSS ';
			case 'inner' :				return ' INNER ';
			case 'straight' :			return ' STRAIGHT_';
			case 'leftouter' :			return ' LEFT OUTER ';
			case 'rightouter' :			return ' RIGHT OUTER ';
			case 'natural' :			return ' NATURAL ';
			case 'naturalleft' :		return ' NATURAL LEFT ';
			case 'naturalright' :		return ' NATURAL RIGHT ';
			case 'naturalleftouter' :	return ' NATURAL LEFT OUTER ';
			case 'naturalrightouter' :	return ' NATURAL RIGHT OUTER ';
			default :					return ' ';
		}
	}
	
	/**
	 * Operators verification
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Test value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if the test value is an operator, else FALSE.
	 */
	public static function isOperator ( $mTryStr )
	{
		return in_array( strtolower(trim($mTryStr)), array('=', '>', '<', '>=', '<=', '<>', '!=', 'like', 'not like', 'in', 'not in', 'is', 'is not'), true );
	}
	
	/**
	 * Array Operators verification
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Test value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if the test value is an array of operator, else FALSE.
	 */
	public static function isArrayOperator ( $mTryArray )
	{
		if ( is_array($mTryArray) ) {
			foreach( $mTryArray as $kTryArray => $vTryArray ) {
				if ( ! self::isOperator( $vTryArray ) ) {
					return false;
				}
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Liaisons verification
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Test value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if the test value is a liaison, else FALSE.
	 */
	public static function isLiaison ( $mTryStr )
	{
		return in_array( trim($mTryStr), array('AND', 'OR', 'XOR', 'AND NOT', 'OR NOT', 'XOR NOT'), true );
	}
	
	/**
	 * Array depth calculator
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Test value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       Array max depth
	 */
	public static function getArrayDepth ( $mTryArray )
	{
		if ( !is_array($mTryArray) ) {
			return 0;
		} else {
			$iMaxDepth = 0;
			foreach ( $mTryArray as $kTryArray => $vTryArray ) {
				$iDepth = self::getArrayDepth( $vTryArray );
				if ( $iDepth > $iMaxDepth ) {
					$iMaxDepth = $iDepth;
				}
			}
			return $iMaxDepth+1;
		}
	}
	
	/**
	 * Digit verifications
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Test value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       TRUE if the test value is a digit, else FALSE.
	 */
	public static function isDigit ( $mV )
	{
		return (	( is_numeric($mV) )
				||	( is_float($mV) )
				||	( is_int($mV) )
			);
	}
	
	private 	$iTableAliasCnt		= 0,
				$bModifier			= null;
	
	protected	$aQueryElements 	= array(),
				$oComposer			= null,
				$aSelectedAlias		= array(),
				$aComposerTables	= array(),
				$aComposerAutoJoins	= array(),
				$bJoinIsOpen		= false,
				$sLastTableOpen		= null;
	
	
	protected function setConnexion ( $oConnexion )
	{
		$this->oConnexion = $oConnexion;
	}
	
	protected function getConnexion ()
	{
		return $this->oConnexion;
	}
	
	public function protect ($mField)
	{
		return $this->oConnexion->protect($mField);
	}
	
	/**
	 * Query elements getter
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Query elements.
	 */
	public function getQueryElements ()
	{
		return $this->aQueryElements;
	}
	
	/**
	 * List field composition
	 *
	 * @author               Tavm\@n
	 * @param      String    A unique field
	 * @param      Array     Query tables definitions
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    A field.
	 */
	protected function composeListFields ($sFieldTo, $aTables)
	{
		$sFieldTo = ' '. trim($sFieldTo) .' ';
		
		for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
			$aTable = $aTables[$i];
			$sFieldTo = preg_replace('#(?<!\w|_)[\'`"]?'. $aTable['NAME'] .'[\'`"]?\.#', ' '. $aTable['ALIAS'] .'.', $sFieldTo);
			$aFields = $aTable['FIELDS'];
			for ( $j=0, $jMax=count($aFields) ; $j<$jMax ; $j++ ) {
				$sField = $aFields[$j];
				$sFieldTo = preg_replace(
					'#(?<!\w|_|\.)[`"\']?('. $sField .')[\s`"\'^]#i',
					' '. $aTable['ALIAS'] .'.$1 ',
					$sFieldTo
				);
			}
		}
		
		return trim($sFieldTo);
	}
	
	/**
	 * Default tables alias getter
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Alias
	 */
	protected function getNextAlias ()
	{
		return self::DEFAULT_ALIAS . $this->iTableAliasCnt++;
	}
	
	/**
	 * Add a query element
	 *
	 * @author               Tavm\@n
	 * @param      String    Element type
	 * @param      Array     Element definition
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	protected function addQueryElement ($sElementName, $aElementParams)
	{
		/* preparation aux jointures automatiques */
		if ( $this->bJoinIsOpen && in_array($sElementName, array('JOIN', 'ALIAS', 'WHERE', '_AND_', '_OR_', '_XOR_', 'GROUPBY', 'HAVING', 'ORDERBY', 'LIMIT', ''), true) ) {
			$aAutoJoins = end($this->aComposerAutoJoins);
			
			$bContinue = true;
			for ( $i=0, $iMax=count($this->aComposerTables) ; $i<$iMax ; $i++ ) {
				$sTableName = $this->aComposerTables[$i];
				for ( $j=0, $jMax=count($aAutoJoins) ; $j<$jMax ; $j++ ) {
					$aAutoJoin = $aAutoJoins[$j];
					if ( strtolower($aAutoJoin['TABLE_TO'])==strtolower($sTableName) || strtolower($aAutoJoin['TABLE_FROM'])==strtolower($sTableName) ) {
						// Ajout clause ON
						$this->addQueryElement(
							'ON',
							array($aAutoJoin['TABLE_FROM'] .'.'. $aAutoJoin['FIELD_FROM'] .'='. $aAutoJoin['TABLE_TO'] .'.'. $aAutoJoin['FIELD_TO'])
						);
						$bContinue = false;
						break;
					}
				}
				if ( ! $bContinue ) break;
			}
		}
		
		if ( $sElementName=='SELECT' ) {
			$this->aComposerTables[]	= call_user_func( array($aElementParams['QUERY_CLASS'], 'getTableName') );
			$this->sLastTableOpen		= $aElementParams['QUERY_CLASS'];
			$this->aComposerAutoJoins[]	= call_user_func(array($aElementParams['QUERY_CLASS'], 'getAutoJoins'));
		} else
		if ( $sElementName=='JOIN' ) {
			$this->aComposerTables[]	= $aElementParams[0];
			$this->sLastTableOpen		= $aElementParams[0];
			$this->aComposerAutoJoins[]	= call_user_func(array($aElementParams[0], 'getAutoJoins'));
			$this->bJoinIsOpen			= true;
		} else
		if ( $sElementName=='ON' ) {
			$this->bJoinIsOpen = false;
		}
		
		$this->aQueryElements[] = array(
			'NAME'		=> $sElementName,
			'PARAMS'	=> $aElementParams,
		);
	}
	
	/**
	 * Query elements setter
	 *
	 * @author               Tavm\@n
	 * @param      Array     Elements
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	protected function setQueryElements ($aQueryElements)
	{
		$this->aQueryElements = $aQueryElements;
	}
	
	/**
	 * Compose a where, on, and, or, xor, having clause.
	 *
	 * @author               Tavm\@n
	 * @param      Array     Clause parametters
	 * @param      Array     Currecnt query tables definition
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    A where, on, and, or, xor, having clause.
	 */
	protected function composeWhereClause ($aParams, $aTables)
	{
		$aParams = $this->reorderWhereClauseParams($aParams);
		
		$sFields	= ' '. $aParams[0] .' ';
		$aValues	= $aParams[1];
		$aOperators	= $aParams[2];
		$sLinks		= $aParams[3];
		$bModifier	= $aParams[4];
		
		$sPattern	= '@(.+)\s(AND\s|OR\s|XOR\s|$)@isU';
		
		if ( $bModifier===true ) {
			if ( $aValues===null && $aOperators===null && $sLinks===null ) {
				return ' ( '. $this->composeListFields($sFields, $aTables) .' ) ';
			} else {
				preg_match_all($sPattern, $sFields, $aMatches);
				echo'<pre>';print_r( __FILE__ );echo'</pre>';
				echo'<pre>';print_r( __LINE__ );echo'</pre>';
				echo'<pre>';print_r( $aMatches );echo'</pre>';
				die;
			}
		} else {
			$aMatches	= array();
			preg_match_all($sPattern, ' '. $sFields .' ', $aMatches);
			
			$sResult = '';
			$iNbOp = 0;
			for ( $i=0, $iMax=count($aMatches[0]) ; $i<$iMax ; $i++ ) {
				if ( trim( $aMatches[1][$i] ) !== '' ) {
					if ( in_array($aMatches[1][$i], array('AND', 'OR', 'XOR'), true) ) {
						$sResult .= ' '. trim( $aMatches[1][$i] ) .' '. $aMatches[2][$i] . ' ';
					} else {
						$sResult .= ' '. trim( $aMatches[1][$i] ) .' '. $aOperators[$iNbOp] .' %s ' . $aMatches[2][$i] .' ';
						$iNbOp++;
					}
				} else {
					$sResult .= ' '. $aMatches[2][$i] .' ';
				}
			}
			$sResult = $this->composeListFields($sResult, $aTables);
			
			$aReturn = array();
			
			while ( ($sFields=str_replace('  ', ' ', $sFields, $iCnt)) && $iCnt>0 );
			for ( $i=0, $iMax=count($aValues) ; $i<$iMax ; $i++ ) {
				for ( $j=0, $jMax=count($aValues[$i]) ; $j<$jMax ; $j++ ) {
					if ( $this->isDigit($aValues[$i][$j]) ){
						$aValues[$i][$j] = $aValues[$i][$j];
					} else if ( is_string($aValues[$i][$j]) ) {
						$aValues[$i][$j] = $this->protect($aValues[$i][$j]);
					} else if ( is_array($aValues[$i][$j]) ) {
						if ( is_string( reset($aValues[$i][$j]) ) ) {
							$aValues[$i][$j] = '(\''. join($aValues[$i][$j], '\', \'') .'\') ';
						} else {
							$aValues[$i][$j] = '('. join($aValues[$i][$j], ', ') .') ';
						}
					} else if ( SQLComposer::is_SQLComposer($aValues[$i][$j]) ) {
						$aValues[$i][$j] = '(' . $aValues[$i][$j]. ') ';
					} else if ( is_null($aValues[$i][$j]) ) {
						$aValues[$i][$j] = ' NULL ';
					} else {
						throw new Exception('unknow parameter type :<br /><pre>'. $aValues[$i][$j] .'</pre>');
					}
				}
				$aReturn[] = ' (' . vsprintf($sResult, $aValues[$i]) . ') ';
			}
			
			$sReturn = implode($sLinks, $aReturn);
			return $sReturn;
		}
		
		return ' 1=1 ';
	}
	
	/**
	 * Reorder parametters for composeWhereClause understand them.
	 *
	 * @author               Tavm\@n
	 * @param      Array     Clause parametters
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Clause parametters reordered
	 */
	protected function reorderWhereClauseParams ($aParams)
	{
		/*
		Le but est de récupérer les 5 parametres suivants :
			String	QUERY			Requete (juste les champs et les connecteurs)
			Array	VALUES			Valeurs de comparaison (Array 2 dimensions)
			Array	OPERATEUR		Operateurs entre les champs et les valeurs (Array 1 dimensions)
			String	LIAISON			Type de liaison entre les repetitions
			Bool	MODIFIER		Savoir si il faut ou non modifier les QUERY avec les protect
		*/
		
		/* Light version */
		
		$iCntParams	= count($aParams);
		$bChanged = true;
		
		if ( ! is_string($aParams[0]) ) {
			throw new Exception('First parameter must be a string.');
		}
		
		if ( $iCntParams==5 ) {
			//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR, (String) LIAISON, (Bool) MODIFIER )
			if (	( self::isArrayOperator($aParams[2]) )
				&&	( self::isLiaison($aParams[3]) )
				&&	( is_bool($aParams[4]) )
			)	{
				if ( self::getArrayDepth($aParams[1])==2 ) {
				} else
				if ( self::getArrayDepth($aParams[1])==1 ) {
					$aParams[1] = array($aParams[1]);
				}else {
					$bChanged = false;
				}
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==4 ) {
			if ( self::getArrayDepth($aParams[1])==2 ) {
				if ( self::isArrayOperator($aParams[2]) ) {
					//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR, (String) LIAISON )
					if ( self::isLiaison($aParams[3]) ) {
						$aParams[4] = $this->getModifier();
					} else
					//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR, (Bool) MODIFIER )
					if ( is_bool($aParams[3]) ) {
						$aParams[4] = $aParams[3];
						$aParams[3] = 'AND';
					} else {
						$bChanged = false;
					}
				} else
				//	(	(String) QUERY, (Array2) VALUES, (String) LIAISON, (Bool) MODIFIER )
				if (	( self::isLiaison($aParams[2]) )
					&&	( is_bool($aParams[3]) )
				)	{
					$aParams[4] = $aParams[3];
					$aParams[3] = $aParams[2];
					$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				} else {
					$bChanged = false;
				}
			} else
			if ( self::getArrayDepth($aParams[1])==1 ) {
				if ( self::isArrayOperator($aParams[2]) ) {
					//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR, (String) LIAISON )
					if (	( self::isLiaison($aParams[3]) )
					)	{
						$aParams[1] = array($aParams[1]);
						$aParams[4] = $this->getModifier();
					} else
					//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR, (Bool) MODIFIER )
					if (	( is_bool($aParams[3]) )
					)	{
						$aParams[1] = array($aParams[1]);
						$aParams[4] = $aParams[3];
						$aParams[3] = 'AND';
					} else {
						$bChanged = false;
					}
				} else
				//	(	(String) QUERY, (Array) VALUES, (String) LIAISON, (Bool) MODIFIER )
				if (	( self::isLiaison($aParams[2]) )
					&&	( is_bool($aParams[3]) )
				)	{
					$aParams[1] = array($aParams[1]);
					$aParams[4] = $aParams[3];
					$aParams[3] = $aParams[2];
					$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				} else {
					$bChanged = false;
				}
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==3 ) {
			if ( self::getArrayDepth($aParams[1])==2 ) {
				//	(	(String) QUERY, (Array2) VALUES, (Bool) MODIFIER )
				if (	( is_bool($aParams[2]) )
				)	{
					$aParams[4] = $aParams[2];
					$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
					$aParams[3] = 'AND';
				} else
				//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR )
				if (	( self::isArrayOperator($aParams[2]) )
				)	{
					$aParams[3] = 'AND';
					$aParams[4] = $this->getModifier();
				} else
				//	(	(String) QUERY, (Array2) VALUES, (String) LIAISON)
				if (	( self::isLiaison($aParams[2]) )
				)	{
					$aParams[3] = $aParams[2];
					$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
					$aParams[4] = $this->getModifier();
				} else {
					$bChanged = false;
				}
			} else 
			if ( self::getArrayDepth($aParams[1])==1 ) {
				//	(	(String) QUERY, (Array) VALUES, (Bool) MODIFIER )
				if (	( is_bool($aParams[2]) )
				)	{
					$aParams[1] = array($aParams[1]);
					$aParams[4] = $aParams[2];
					$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
					$aParams[3] = 'AND';
				} else
				//	(	(String) QUERY, (Array) VALUES, (String) LIAISON)
				if (	( self::isLiaison($aParams[2]) )
				)	{
					$aParams[1] = array($aParams[1]);
					$aParams[3] = $aParams[2];
					$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
					$aParams[4] = $this->getModifier();
				} else
				//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR )
				if (	( self::isArrayOperator($aParams[2]) )
				)	{
					$aParams[1] = array($aParams[1]);
					$aParams[3] = 'AND';
					$aParams[4] = $this->getModifier();
				} else{
					$bChanged = false;
				}
			} else {
				//	(	(String) QUERY, (Array) OPERATEUR, (Array) VALUES )
				if (	( self::isArrayOperator($aParams[1]) )
					&&	( self::getArrayDepth($aParams[2])==1 )
				)	{
					$mTransi = $aParams[1];
					$aParams[1] = array($aParams[2]);
					$aParams[2] = $mTransi;
					$aParams[3] = 'AND';
					$aParams[4] = $this->getModifier();
				} else
				//	(	(String) QUERY, (String) OPERATEUR, (String) VALUES)
				if (	( self::isOperator($aParams[1]) )
				)	{
					$mTransi = $aParams[1];
					$aParams[1] = array(array($aParams[2]));
					$aParams[2] = array($mTransi);
					$aParams[3] = 'AND';
					$aParams[4] = $this->getModifier();
				} else {
					$bChanged = false;
				}
			}
		} else
		if ( $iCntParams==2 ) {
			//	(	(String) QUERY, (Bool) MODIFIER )
			if (	( is_bool($aParams[1]) )
			)	{
				if ( $aParams[1]!=true ) {
					throw new Exception('Clause where parameters not complete');
				}
				$aParams[1] = null;
				$aParams[2] = null;
				$aParams[3] = null;
				$aParams[4] = true;
			} else
			//	(	(String) QUERY, (Array) VALUES)
			if (	( self::getArrayDepth($aParams[1])==1 )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (String) VALUES)
			if (	( ! is_array($aParams[1]) )
			)	{
				$aParams[1] = array(array($aParams[1]));
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==1 ) {
			//	(	(String) QUERY)
			if (	( is_string($aParams[0]) )
			)	{
				$aParams[1] = null;
				$aParams[2] = null;
				$aParams[3] = null;
				$aParams[4] = true;
			} else {
				$bChanged = false;
			}
		}
		
		if ( ! $bChanged ) {
			//	(	(String) QUERY, (Array) OPERATEUR, (String) VALUES1, (String) VALUES2 )
			if (	( $iCntParams>2 )
				&&	( self::isArrayOperator($aParams[1]) )
			)	{
				$aTransi = array();
				$aTransi[0] = $aParams[0];
				$aTransi[2] = $aParams[1];
				$aTransi[1] = array(array_splice($aParams, 2));
				$aTransi[3] = 'AND';
				$aTransi[4] = $this->getModifier();
				$aParams = $aTransi;
			} else
			//	(	(String) QUERY, (String) VALUES1, (String) VALUES2 )
			if (	( $iCntParams>1 )
			)	{
				$aTransi = array();
				$aTransi[0] = $aParams[0];
				$aTransi[1] = array(array_splice($aParams, 1));
				$aTransi[2] = array_fill(0, count($aTransi[1][0]), '=');
				$aTransi[3] = 'AND';
				$aTransi[4] = $this->getModifier();
				$aParams = $aTransi;
			}
		}
		
		return $aParams;
		
		/*
		// Complet version
		$iCntParams	= count($aParams);
		$bChanged = true;
		
		if ( $iCntParams==5 ) {
			//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR, (String) LIAISON, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( self::isArrayOperator($aParams[2]) )
				&&	( self::isLiaison($aParams[3]) )
				&&	( is_bool($aParams[4]) )
			)	{
			} else
			//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR, (String) LIAISON, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( self::isArrayOperator($aParams[2]) )
				&&	( self::isLiaison($aParams[3]) )
				&&	( is_bool($aParams[4]) )
			)	{
				$aParams[1] = array($aParams[1]);
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==4 ) {
			//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR, (String) LIAISON )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( self::isArrayOperator($aParams[2]) )
				&&	( self::isLiaison($aParams[3]) )
			)	{
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( self::isArrayOperator($aParams[2]) )
				&&	( is_bool($aParams[3]) )
			)	{
				$aParams[4] = $aParams[3];
				$aParams[3] = 'AND';
			} else
			//	(	(String) QUERY, (Array2) VALUES, (String) LIAISON, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( self::isLiaison($aParams[2]) )
				&&	( is_bool($aParams[3]) )
			)	{
				$aParams[4] = $aParams[3];
				$aParams[3] = $aParams[2];
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
			} else
			//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR, (String) LIAISON )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( self::isArrayOperator($aParams[2]) )
				&&	( self::isLiaison($aParams[3]) )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( self::isArrayOperator($aParams[2]) )
				&&	( is_bool($aParams[3]) )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[4] = $aParams[3];
				$aParams[3] = 'AND';
			} else
			//	(	(String) QUERY, (Array) VALUES, (String) LIAISON, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( self::isLiaison($aParams[2]) )
				&&	( is_bool($aParams[3]) )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[4] = $aParams[3];
				$aParams[3] = $aParams[2];
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==3 ) {
			//	(	(String) QUERY, (Array2) VALUES, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( is_bool($aParams[2]) )
			)	{
				$aParams[4] = $aParams[2];
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[3] = 'AND';
			} else
			//	(	(String) QUERY, (Array2) VALUES, (Array) OPERATEUR )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( self::isArrayOperator($aParams[2]) )
			)	{
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (Array2) VALUES, (String) LIAISON)
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==2 )
				&&	( self::isLiaison($aParams[2]) )
			)	{
				$aParams[3] = $aParams[2];
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (Array) VALUES, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( is_bool($aParams[2]) )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[4] = $aParams[2];
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[3] = 'AND';
			} else
			//	(	(String) QUERY, (Array) VALUES, (String) LIAISON)
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( self::isLiaison($aParams[2]) )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[3] = $aParams[2];
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (Array) VALUES, (Array) OPERATEUR )
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
				&&	( self::isArrayOperator($aParams[2]) )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (Array) OPERATEUR, (Array) VALUES )
			if (	( is_string($aParams[0]) )
				&&	( self::isArrayOperator($aParams[1]) )
				&&	( self::getArrayDepth($aParams[2])==1 )
			)	{
				$mTransi = $aParams[1];
				$aParams[1] = array($aParams[2]);
				$aParams[2] = $mTransi;
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (String) OPERATEUR, (String) VALUES)
			if (	( is_string($aParams[0]) )
				&&	( self::isOperator($aParams[1]) )
			)	{
				$mTransi = $aParams[1];
				$aParams[1] = array(array($aParams[2]));
				$aParams[2] = $mTransi;
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==2 ) {
			//	(	(String) QUERY, (Bool) MODIFIER )
			if (	( is_string($aParams[0]) )
				&&	( is_bool($aParams[1]) )
			)	{
				if ( $aParams[1]!=true ) {
					throw new Exception('Clause where parameters not complete');
				}
				$aParams[1] = null;
				$aParams[2] = null;
				$aParams[3] = null;
				$aParams[4] = true;
			} else
			//	(	(String) QUERY, (Array) VALUES)
			if (	( is_string($aParams[0]) )
				&&	( self::getArrayDepth($aParams[1])==1 )
			)	{
				$aParams[1] = array($aParams[1]);
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else
			//	(	(String) QUERY, (String) VALUES)
			if (	( is_string($aParams[0]) )
				&&	( ! is_array($aParams[1]) )
			)	{
				$aParams[1] = array(array($aParams[1]));
				$aParams[2] = array_fill(0, count($aParams[1][0]), '=');
				$aParams[3] = 'AND';
				$aParams[4] = $this->getModifier();
			} else {
				$bChanged = false;
			}
		} else
		if ( $iCntParams==1 ) {
			//	(	(String) QUERY)
			if (	( is_string($aParams[0]) )
			)	{
				$aParams[1] = null;
				$aParams[2] = null;
				$aParams[3] = null;
				$aParams[4] = true;
			} else {
				$bChanged = false;
			}
		}
		
		if ( ! $bChanged ) {
			//	(	(String) QUERY, (Array) OPERATEUR, (String) VALUES1, (String) VALUES2 )
			if (	( is_string($aParams[0]) )
				&&	( $iCntParams>2 )
				&&	( self::isArrayOperator($aParams[1]) )
			)	{
				$aTransi = array();
				$aTransi[0] = $aParams[0];
				$aTransi[2] = $aParams[1];
				$aTransi[1] = array(array_splice($aParams, 2));
				$aTransi[3] = 'AND';
				$aTransi[4] = $this->getModifier();
				$aParams = $aTransi;
			} else
			//	(	(String) QUERY, (String) VALUES1, (String) VALUES2 )
			if (	( is_string($aParams[0]) )
				&&	( $iCntParams>1 )
			)	{
				$aTransi = array();
				$aTransi[0] = $aParams[0];
				$aTransi[1] = array(array_splice($aParams, 1));
				$aTransi[2] = array_fill(0, count($aTransi[1][0]), '=');
				$aTransi[3] = 'AND';
				$aTransi[4] = $this->getModifier();
				$aParams = $aTransi;
			}
		}
		
		return $aParams;
		*/
	}
	
	/**
	 * General table datas accessor.
	 *
	 * @author               Tavm\@n
	 * @param      String    Table class-name
	 * @param      Array     All query elements
	 * @param      Int       Actual query element
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Table datas : array( 'NAME'=>[...], 'CLASS'=>[...], 'ALIAS'=>[...],
	 *                                            'RENAME'=>[...], 'FIELDS'=>[...] )
	 */
	protected function getTableDatas ($sClassName, $aQueryElements, $i)
	{
		$sGetTable	= call_user_func(array($sClassName, 'getTableName'));
		$aGetFields	= call_user_func(array($sClassName, 'getFields'));
		
		if ( isset($aQueryElements[$i+1]) && $aQueryElements[$i+1]['NAME']=='ALIAS' ) {
			$sAlias		= $aQueryElements[$i+1]['PARAMS'][0];
			$sRename	= $aQueryElements[$i+1]['PARAMS'][0];
		} else if (		isset($aQueryElements[$i+1]) && $aQueryElements[$i+1]['NAME']=='ON' 
					&&	isset($aQueryElements[$i+2]) && $aQueryElements[$i+2]['NAME']=='ALIAS' ) {
			$sAlias		= $aQueryElements[$i+2]['PARAMS'][0];
			$sRename	= $aQueryElements[$i+2]['PARAMS'][0];
		} else {
			$sAlias		= $this->getNextAlias();
			$sRename	= $sGetTable;
		}
		
		return array(
			'NAME'		=> $sGetTable,
			'CLASS'		=> $sClassName,
			'ALIAS'		=> $sAlias,
			'RENAME'	=> $sRename,
			'FIELDS'	=> $aGetFields,
		);
	}
	
	/**
	 * Compose the list field like in the SELECT clause.
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Clause parametters
	 * @param      Array     Currecnt query tables definition
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     selected fields list (protected)
	 */
	protected function getSelectedClause ($mClause, $aTables)
	{
		$aSelectedFields = array();
		if ( $mClause=='*' ) {
			$aSelectedFields = $this->getSelectedJocker($aTables);
		} else if ( is_string($mClause) ) {
			$mClause = $this->getSelectedField($mClause, $aTables);
			if ( in_array($mClause[1], $this->aSelectedAlias, true) ) {
				$iPref = 0;
				while ( in_array($mClause[1] .'_'. $iPref, $this->aSelectedAlias, true) ) {
					$iPref++;
				}
				$mClause[1] = $mClause[1] .'_'. $iPref;
			}
			$this->aSelectedAlias[] = $mClause[1];
			
			$mClause = $mClause[0] .' AS `'. $mClause[1] .'`';
			$aSelectedFields[] = $mClause;
		} else if ( is_array($mClause) ) {
			for ( $i=0, $iMax=count($mClause) ; $i<$iMax ; $i++ ) {
				$sSelectField = $mClause[$i];
				$aSelectedFields = array_merge($aSelectedFields, $this->getSelectedClause($sSelectField, $aTables));
			}
		}
		
		return $aSelectedFields;
	}
	
	/**
	 * Compose the list field like in the SELECT clause with all fields.
	 *
	 * @author               Tavm\@n
	 * @param      Array     Currecnt query tables definition
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     selected fields list (protected)
	 */
	protected function getSelectedJocker ($aTables)
	{
		$aSelectedFields = array();
		for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
			$aTable		= $aTables[$i];
			$aFields	= $aTables[$i]['FIELDS'];
			for ( $j=0, $jMax=count($aFields) ; $j<$jMax ; $j++ ) {
				$sField = $aFields[$j];
				$aSelectedFields[] = $aTable['ALIAS'] .'.'. $sField . ' AS `'. $aTable['ALIAS'] .'.'. $sField .'`';
			}
		}
		
		return $aSelectedFields;
	}
	
	/**
	 * Compose a field like in the SELECT clause with all fields.
	 *
	 * @author               Tavm\@n
	 * @param      String    Field
	 * @param      Array     Currecnt query tables definition
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    field (protected)
	 */
	protected function getSelectedField ($sFieldTo, $aTables)
	{
		/*******************************************************************************************
		 * Change :
		 * - field_1						=> t_alias.field_1 AS `t_alias.field_1`
		 * - t_alias.field_1				=> t_alias.field_1 AS `t_alias.field_1`
		 * - t_alias.field_1 AS f_alias		=> t_alias.field_1 AS `t_alias.f_alias`
		 * - t_name.field_1					=> t_alias.field_1 AS `t_alias.field_1`
		 * - t_name.field_1 AS f_alias		=> t_alias.field_1 AS `t_alias.f_alias`
		 * 
		 * - `t_name`.`field_1` AS f_alias	=> t_alias.field_1 AS `t_alias.f_alias`
		 * - "t_name"."field_1" AS f_alias	=> t_alias.field_1 AS `t_alias.f_alias`
		 * - 't_name'.'field_1' AS f_alias	=> t_alias.field_1 AS `t_alias.f_alias`
		 * 
		 * - MAX(t_alias.field_1)			=> MAX(t_alias.field_1) AS `t_alias.max_field_1`
		 * - MAX(field_1)					=> MAX(t_alias.field_1) AS `t_alias.max_field_1`
		 * - MAX(t_name.field_1)			=> MAX(t_alias.field_1) AS `t_alias.max_field_1`
		 * 
		 * Problems :
		 * - CONCAT(field, '[...] AS [...]')
		 * - '[...]\'[...]'
		 * - '[...]table[...]'
		 * - '[...]field[...]'
		 * - '[...]FUNC([...])[...]'
		 * 
		 */
		
		$sFieldTo = trim($sFieldTo);
		/*
		Etape 1 : regarder si il s'agit d'un champ "simple"
			-> trouver la table associée (la premiere)
			-> sortir
		*/
		// echo '#1 : '. $sFieldTo .'<br />';
		$sPattern = '#[^\w\'`"]#';
		if ( !preg_match($sPattern, $sFieldTo) ) {
			// echo'<pre>#1 : $sFieldTo = ';print_r( $sFieldTo );echo'</pre>';
			
			$sFieldTo = str_replace(array('\'','`','"'), '', $sFieldTo);
			for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
				$aTable = $aTables[$i];
				$aFields = $aTable['FIELDS'];
				for ( $j=0, $jMax=count($aFields) ; $j<$jMax ; $j++ ) {
					$sField = $aFields[$j];
					if ( $sField==$sFieldTo ) {
						return array($aTable['ALIAS'] .'.'. $sField,  strtolower($aTable['ALIAS'] .'.'. $sField));
					}
				}
			}
		}
		
		/*
		Etape 2 : regarder si il s'agit d'un champ "sans fonction" (sachant qu'une operation est une fonction) et sans alias
			-> ce sont des champs types :
					- table.field
					- 'table'.field
					- 'table'.'field'
					- table.'field'
					(avec differentes quotes)
			-> trouver l'alias
			-> sortir
		*/
		// echo '#2 : '. $sFieldTo .'<br />';
		$sPattern = '#^[\w\'`"]+\.[\w\'`"]+$#';
		if ( preg_match($sPattern, $sFieldTo) ) {
			// echo'<pre>#2 : $sFieldTo = ';print_r( $sFieldTo );echo'</pre>';
			$sField = $sFieldTo;
			
			for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
				$aTable = $aTables[$i];
				$sField = preg_replace('#(?<![\.\w])[\'`"]?'. $aTable['NAME'] .'[\'`"]?.#', $aTable['ALIAS'] .'.', $sField);
			}
			$sAlias = $sField;
			$sAlias = str_replace(array('"','`','\''), '', $sAlias);
			return array($sField, strtolower($sAlias));
		}
		
		/*
		Etape 3 : regarder si il s'agit d'un champ avec alias (avec ou sans appel a une fonction d'ailleur)
			-> ce sont des champs types :
					- table.field AS f_alias	=> \s+AS\s+
					- table.field f_alias			=> \s+
					- table.field t_alias.f_alias
					- table.field t_name.f_alias
			-> trouver l'alias
			-> sortir
		*/
		// echo '#3 : '. $sFieldTo .'<br />';
		$sPattern = '#^(.*)(\s+AS\s+|\s+)[\'`"]?([a-zA-Z][\w\.]*)[\'`"]?$#i';
		if ( preg_match($sPattern, $sFieldTo, $aMatch) ) {
			// echo'<pre>#3 : $sFieldTo = ';print_r( $sFieldTo );echo'</pre>';
			
			$sField = preg_replace('#\s+AS$#i', '', trim($aMatch[1]));
			$sAlias = trim($aMatch[3]); // Alias prefixe OU NON du nom de la table
			
			/* modifications sur $sField : changer les tables en alias */
			for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
				$aTable = $aTables[$i];
				$sField = preg_replace('#\s*[\'`"]?'. $aTable['NAME'] .'[\'`"]?\.#', $aTable['ALIAS'] .'.', $sField);
			}
			
			// Trouver la meilleure table a associer au champs
			
			// table precisee dans l'alias (NE PAS FAIRE CA EN SQL !!!!)
			if ( strstr($sAlias, '.') ) {
				// normalement, on a un nom de table dans l'alias :
				$aAlias = explode('.', $sAlias);
				$sTable = $aAlias[0];
				$sRAlias = $aAlias[1];
				
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					$aTable = $aTables[$i];
					if ( $aTable['NAME'] == $sTable ) {
						return array($sField, strtolower($aTable['ALIAS'] .'.'. $sRAlias));
					}
					if ( $aTable['ALIAS'] == $sTable ) {
						return array($sField, strtolower($aTable['ALIAS'] .'.'. $sRAlias));
					}
				}
			}
			// $sAlias = preg_replace('#[^\w]#', '_', $sAlias);
			$sAlias = preg_replace('#\W#', '_', $sAlias);
			
			// on prend la premiere table qui apparait dans $sField (dans l'ordre d'apparition des tables dans la clause FROM)
			for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
				$aTable = $aTables[$i];
				if ( preg_match('#[\'`"\s\(]'. $aTable['ALIAS'] .'[\'`"\s\.]#i', $sField) ) {
					echo'<pre>';print_r($sField);echo'</pre>';
					echo'<pre>';print_r( strtolower($aTable['ALIAS'] .'.'. $sAlias) );echo'</pre><hr />';
					return array($sField, strtolower($aTable['ALIAS'] .'.'. $sAlias));
				}
				
				$aFields = $aTable['FIELDS'];
				for ( $i=0, $iMax=count($aFields) ; $i<$iMax ; $i++ ) {
					$sVField = $aFields[$i];
					if ( preg_match('#[\'`"\s\(]'. $sVField .'[\'`"\s\)]#i', $sField) ) {
						return array($sField, strtolower($aTable['ALIAS'] .'.'. $sAlias));
					}
				}
			}
			
			// si on arrive ici, c'est qu'on n'a pas trouvé de table... pas super logique mais bon
			// donc on prend la premiere table :
			return array($sField, strtolower($aTables[0]['ALIAS'] .'.'. $sAlias));
		}
		
		/*
		Etape 4 : champs avec fonction ou une operation quelconque SANS alias
			-> remplacer les tables par leur alias
			-> composer un alias
			-> sortir
		*/
		// $sAlias = preg_replace(array('#[^\w]#','#_+#','#_$#','#^_#'),array('_','_','',''),$sFieldTo);
		$sAlias = preg_replace(array('#[^\w]#','#__+#','#_$#','#^_#'),array('_','_','',''),$sFieldTo);
		
		// echo '#4 : '. $sFieldTo .'<br />';
		for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
			$aTable = $aTables[$i];
			if ( preg_match('#[\'`"\s\(]'. $aTable['NAME'] .'[\'`"\s\.]#i', $sFieldTo) ) {
				return array($sFieldTo, strtolower($aTable['ALIAS'] .'.'. $sAlias));
			}
			if ( preg_match('#[\'`"\s\(]'. $aTable['ALIAS'] .'[\'`"\s\.]#i', $sFieldTo) ) {
				return array($sFieldTo, strtolower($aTable['ALIAS'] .'.'. $sAlias));
			}
			
			$aFields = $aTable['FIELDS'];
			for ( $j=0, $jMax=count($aFields) ; $j<$jMax ; $j++ ) {
				$sVField = $aFields[$j];
				if ( preg_match('#[\'`"\s\(]'. $sVField .'[\'`"\s\)]#i', $sFieldTo) ) {
					return array($sFieldTo, strtolower($aTable['ALIAS'] .'.'. $sAlias));
				}
			}
		}
		return array($sFieldTo, strtolower($aTables[0]['ALIAS'] .'.'. $sAlias));
	}
	
	/* Query options */
	/**
	 * Sets the current query default modifier.
	 *
	 * @author               Tavm\@n
	 * @param      Bool      TRUE if modifiers are use, else FALSE
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerMySqlPDO ($this)
	 */
	public function setModifier ($bModifier)
	{
		$this->bModifier = $bModifier;
		return $this;
	}
	
	/**
	 * Sets the current query default modifier.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if modifiers are use, else FALSE
	 */
	public function getModifier ()
	{
		if ( $this->bModifier===null ) {
			return self::getDefaultModifier();
		} else {
			return $this->bModifier;
		}
	}
	
	/**
	 * Make joins.
	 *
	 * @author               Tavm\@n
	 * @param      String    Method called.
	 * @param      Array     Parametters
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerMySqlPDO ($this)
	 */
	public function __call ($sMeth, $aParams)
	{
		preg_match_all('@^(.*)(join_|join)(.*)$@isU', $sMeth, $aMatchesJoin);
		
		if ( count($aMatchesJoin[0])>0 ) {
			// Demande d'une jointure
			$sTypeJoin	= $aMatchesJoin[1][0];
			$sTableJoin	= $aMatchesJoin[3][0];
			if ( $sTypeJoin != '' ) {
				// Traduction de la jointure
				$sTypeJoin = $this->TradJoins( $sTypeJoin );
			}
			
			/*
			On n'insere pas immediatement la jointure pour pouvoir lui rajouter un alias.
			*/
			if ( count($aParams)==0 ) {
				$this->addQueryElement('JOIN', array($sTableJoin, $sTypeJoin,));
			} else {
				$this->addQueryElement('JOIN', array($sTableJoin, $sTypeJoin));
				$this->addQueryElement('ON', $aParams);
			}
			$this->aJoinedTables[] = $sTableJoin;
		}
		return $this;
	}
}
