<?php
/**
 * @desc        PDO-MySQL SELECT queries composition classes
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
 ***************************************************************************************************
 * @longdesc    parameters are not defined because they are not really fixed. For example, a where
 *              clause can have between 1 and 5 parameters. So this interface doesn't describe
 *              query-composition functions and you have to use func_get_args() to get arguments.
 */

class ComposerSelectMySQLPDO extends ComposerMySqlPDO implements ComposerSelect
{
	protected	$aTables			= array(),
				$bUseCache			= false,
				$iCacheTime			= 3600,
				$oConnexion			= null;
	
	/**
	 * Select clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function __construct ($sClass, $aParams, $oConnexion)
	{
		$aParams = array(
			'QUERY_CLASS'	=> $sClass,
			'PARAMS'		=> $aParams
		);
		$this->addQueryElement('SELECT', $aParams);
		$this->setConnexion( $oConnexion );
	}
	
	protected function setConnexion ( $oConnexion )
	{
		$this->oConnexion = $oConnexion;
	}
	
	protected function getConnexion ()
	{
		return $this->oConnexion;
	}
	
	/**
	 * Join clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function alias		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Alias clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function join		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * On clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function on			()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Where clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function where		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * And clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function _and_		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Or clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function _or_		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Xor clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function _xor_		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Group by clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function groupBy		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Having clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function having		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Order by clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function orderBy		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Limit clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function limit		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Alows you to use cache systeme for this query.
	 *
	 * @author               Tavm\@n
	 * @param      [Bool]    TRUE if you want to use cache systeme, else FALSE
	 * @param      [Int]     Cahe life time (in seconds)
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function useCache	($bUseCache=true, $iCacheTime=3600)
	{
		$this->bUseCache = $bUseCache;
		$this->iCacheTime = $iCacheTime;
		return $this;
	}
	
	/**
	 * Cache using getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function getCacheUse ()
	{
		return $this->bUseCache;
	}
	
	/**
	 * Cache life time getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function getCacheTime ()
	{
		return $this->iCacheTime;
	}
	
	/**
	 * Compose the query
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Query
	 */
	protected function composeQuery ()
	{
		/*	Array Desc :
				$aTables = array(
					array(
						'NAME'		=> 'T1',
						'ALIAS'		=> 't___0',
						'RENAME'	=> 't___0',
						'CLASS'		=> 'classT1',
						'FIELDS'	=> array([...]),
					),
				);
		*/
		
		// Auto-Joins Close
		$this->addQueryElement('', array());
		
		
		$aTables		= array();
		$aAlias			= array();	// Verifications
		$aRenames		= array();	// Verifications
		$sSelectClause	= '*';
		$sQuery			= '';
		$aQueryElements	= $this->getQueryelEments();
		$aGroupByClause	= array();
		$aHavingClause	= array();
		$aOrderByClause	= array();
		$aLimitClause	= array();
		
		$iStep = 0;	// query clause verificator
		
		for ( $i=0, $iMax=count($aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement	= $aQueryElements[ $i ];
			
			$sElementName	= $aQueryElement['NAME'];
			$aElementParams	= $aQueryElement['PARAMS'];
			
			switch ( $sElementName ) {
				case	'SELECT'	: {
					if ( $iStep!=0 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 1;
					
					$aTable = $this->getTableDatas( $aElementParams['QUERY_CLASS'], $aQueryElements, $i );
					if ( !empty($aElementParams['PARAMS']) ) {
						$sSelectClause = $aElementParams['PARAMS'];
					}
					$aTables[]	= $aTable;
					$aAlias[]	= $aTable['ALIAS'];
					$aRenames[]	= $aTable['RENAME'];
					
					$sQuery .= ' FROM `'. $aTable['NAME'] .'` AS '. $aTable['ALIAS'] .' ';
				} break;
				case	'ALIAS'		: {
					// Pris en charge dans le SELECT et le JOIN
				} break;
				case	'JOIN'		: {
					if ( $iStep<1 || $iStep>2 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 2;
					
					if ( !isset($aElementParams[0]) ) {
						throw new Exception();
					}
					$aTable = $this->getTableDatas( $aElementParams[0], $aQueryElements, $i );
					if ( in_array($aTable['ALIAS'], $aAlias, true) ) {
						throw new Exception('Alias tables problem : 2 tables haves the same alias.');
					}
					if ( in_array($aTable['RENAME'], $aRenames, true) ) {
						throw new Exception('Alias tables problem : 2 tables haves the same name.');
					}
					$aAlias[] = $aTable['ALIAS'];
					$aRenames[] = $aTable['RENAME'];
					$aTables[] = $aTable;
					
					if ( isset($aElementParams[1]) ) {
						$sQuery .= ' '. $aElementParams[1] .' JOIN `'. $aTable['NAME'] .'` AS '. $aTable['ALIAS'] .' ';
					} else {
						$sQuery .= ' JOIN `'. $aTable['NAME'] .'` AS '. $aTable['ALIAS'] .' ';
					}
				} break;
				case	'ON'		: {
					$sQuery .= ' ON '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'WHERE'		: {
					if ( $iStep<1 || $iStep>3 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 3;
					$sQuery .= ' WHERE '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_AND_'		: {
					if ( $iStep<3 || $iStep>4 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 4;
					$sQuery .= ' AND '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_OR_'		: {
					if ( $iStep<3 || $iStep>4 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 4;
					$sQuery .= ' OR '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_XOR_'		: {
					if ( $iStep<3 || $iStep>4 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 4;
					$sQuery .= ' XOR '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'GROUPBY'	: {
					if ( $iStep==0 || $iStep>5 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 5;
					$aGroupByClause[]	= $aQueryElement;
				} break;
				case	'HAVING'	: {
					if ( $iStep<5 || $iStep>6 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 6;
					$aHavingClause[]	= $aQueryElement;
				} break;
				case	'ORDERBY'	: {
					if ( $iStep<1 || $iStep>7 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 7;
					$aOrderByClause[]	= $aQueryElement;
				} break;
				case	'LIMIT'		: {
					if ( $iStep<1 || $iStep>8 ) {
						throw new Exception('Incorect query clause order');
					}
					$iStep = 8;
					$aLimitClause[]		= $aQueryElement;
				} break;
			}
		}
		
		/* GROUP BY */
		for ( $i=0, $iMax=count($aGroupByClause) ; $i<$iMax ; $i++ ) {
			if ( is_array($aGroupByClause[$i]['PARAMS']) ) {
				$aGroupByField = array();
				for ( $j=0, $jMax=count($aGroupByClause[$i]['PARAMS'][$j]) ; $j<$jMax ; $j++ ) {
					$aGroupByField[] = $this->composeListFields($aGroupByClause[$i]['PARAMS'][$j], $aTables);
				}
				$aGroupByClause[$i] = implode(', ', $aGroupByField);
			} else if ( is_string($aGroupByClause['PARAMS'][0]) ) {
				$aGroupByClause[$i] = $this->composeListFields($aGroupByClause[$i]['PARAMS'], $aTables);
			}
		}
		$sGroupBy = implode(', ', $aGroupByClause);
		
		/* HAVING */
		for ( $i=0, $iMax=count($aHavingClause) ; $i<$iMax ; $i++ ) {
			$aHavingClause[$i] = $this->composeWhereClause($aHavingClause[$i]['PARAMS'], $aTables);
		}
		$sHavingClause = implode(' AND ', $aHavingClause);
		
		
		/* ORDER BY */
		for ( $i=0, $iMax=count($aOrderByClause) ; $i<$iMax ; $i++ ) {
			if ( is_array($aOrderByClause[$i]['PARAMS']) ) {
				$aOrderByField = array();
				for ( $j=0, $jMax=count($aOrderByClause[$i]['PARAMS'][$j]) ; $j<$jMax ; $j++ ) {
					$aOrderByField[] = $this->composeListFields($aOrderByClause[$i]['PARAMS'][$j], $aTables);
				}
				$aOrderByClause[$i] = implode(', ', $aOrderByField);
			} else if ( is_string($aOrderByClause['PARAMS'][0]) ) {
				$aOrderByClause[$i] = $this->composeListFields($aOrderByClause[$i]['PARAMS'], $aTables);
			}
		}
		$sOrderBy = implode(', ', $aOrderByClause);
		
		/* LIMIT */
		$sLimitClause = '';
		if ( isset($aLimitClause[0]['PARAMS']) ) {
			if ( is_array($aLimitClause[0]['PARAMS']) ) {
				if ( count($aLimitClause[0]['PARAMS'])==1 || $aLimitClause[0]['PARAMS'][1]===null ) {
					$sLimitClause = $aLimitClause[0]['PARAMS'][0];
				} else {
					$sLimitClause = $aLimitClause[0]['PARAMS'][0] .', '. $aLimitClause[0]['PARAMS'][1];
				}
			} else if ( is_string($aLimitClause[0]['PARAMS']) ) {
				$sLimitClause = $aLimitClause[0]['PARAMS'];
			}
		}
		
		
		if ( $sGroupBy!='' ) {
			$sQuery .= ' GROUP BY '. $sGroupBy;
			if ( $sHavingClause!='' ) {
				$sQuery .= ' HAVING '. $sHavingClause;
			}
		}
		if ( $sOrderBy!='' ) {
			$sQuery .= ' ORDER BY '. $sOrderBy;
		}
		if ( $sLimitClause!='' ) {
			$sQuery .= ' LIMIT '. $sLimitClause;
		}
		
		/* SELECTED FIELDS */
		$aSelectClause = $this->getSelectedClause($sSelectClause, $aTables);
		$sQuery = 'SELECT '. implode(', ', $aSelectClause) . $sQuery;
		
		$this->aTables = $aTables;
		return $sQuery;
	}
	
	/**
	 * Query execution.
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposerConnexion object
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerCollection
	 */
	public function exec ()
	{
		$sQuery = $this->composeQuery();
		
		if ( $this->getCacheUse() ) {
			$sName = md5($sQuery);
			$sFName = dirname(dirname( __FILE__ )) .'/cache/'. $sName .'.php';
			if ( file_exists($sFName) && filemtime($sFName)>time()-$this->getCacheTime() ) {
				return unserialize( include($sFName) );
			}
		}
		
		$oDB = $this->getConnexion();
		$rQuery = $oDB->executeQuery($sQuery);
		if ( !$rQuery ) {
			echo'<pre>';print_r( $sQuery );echo'</pre>';
			echo'<pre>';print_r( $oDB->getError() );echo'</pre>';
			die;
		}
		
		$aTables = $this->aTables;
		// Initialisation du tableau contenant tous les Records crees
		$aCompleteRecords	= array();
		$aTablesRName		= array();
		for( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
			$aTable	= $aTables[$i];
			$sJoin							= $aTable['ALIAS'];
			$sTableClassName				= $aTable['NAME'];
			
			$aTablesRecord[ $sJoin ]		= call_user_func(array($sTableClassName, 'getRecordClassName'));
			$aTablesCollection[ $sJoin ]	= call_user_func(array($sTableClassName, 'getCollectionClassName'));
			$aCompleteRecords[ $sJoin ]		= array();
			
			$aTablesRName[ strtolower($sTableClassName) ]	= strtolower($aTable['RENAME']);
			$aTablesName[ strtolower($aTable['RENAME']) ]	= strtolower($sTableClassName);
		}
		
		$aAliasedJoin = array();
		$aValues = array();
		for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
			$aTable = $aTables[$i];
			$aAliasedJoin[ $aTable['ALIAS'] ] = $aTable['NAME'];
			$aValues[ $aTable['RENAME'] ] = array();
		}
		
		/*******************************************************************************************
		 * System core
		 */
		while ( $aDatas = $oDB->fetchAssoc($rQuery) ) {
			$aRecordElements = array();
			$aJoins = array();
			
			for( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
				$aTable	= $aTables[$i];
				$sRecordClass = $aTablesRecord[ $aTable['ALIAS'] ];
				$oRecord = new $sRecordClass();
				$oRecord->setFieldsFromQuery($aDatas, $aTable['ALIAS']);
				
				$sKey = (string)$oRecord;
				
				if ( isset( $aValues[ $aTable['RENAME'] ][ $sKey ] ) ) {
					$oRecord = $aValues[ $aTable['RENAME'] ][ $sKey ];
				} else {
					$aValues[ $aTable['RENAME'] ][ $sKey ] = $oRecord;
				}
				
				$aJoins[ $aTable['RENAME'] ] = $oRecord;
			}
			
			foreach ( $aJoins as $sRName => $oRecord ) {
				$oRecord->addJoins($sRName, $aJoins, $aTablesRName, $aTablesName);
			}
		}
		/*
		 * System core
		 ******************************************************************************************/
		 
		$oDB->freeResults($rQuery);
		$sTableUsed = $aTables[0]['NAME'];
		$sTableRename = $aTables[0]['RENAME'];
		if ( isset($aValues[$sTableRename]) ) {
			$aValues[$sTableRename] = array_values($aValues[$sTableRename]);
		}
		
		if ( $this->getCacheUse() ) {
			$sName = md5($sQuery);
			$rFile = fopen( dirname(dirname( __FILE__ )) .'/cache/'. $sName .'.php', 'w+');
			fwrite($rFile, '<?php return \''. str_replace('\'', '\\\'', serialize( $aValues[$sTableRename] ) ) .'\';' );
			fclose($rFile);
		}
		
		if ( isset($aValues[$sTableRename]) ) {
			$sCollectionName = $sTableUsed .'Collection';
			$oCollection = new $sCollectionName();
			
			for ( $i=0, $iMax=count($aValues[$sTableRename]) ; $i<$iMax ; $i++ ) {
				$aValue = $aValues[$sTableRename][$i];
				$oCollection[] = $aValue;
			}
			return $oCollection;
		} else {
			return array();
		}
	}
	
	/**
	 * Returns the query string.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Query string
	 */
	public function get ($oDB=null)
	{
		return $this->composeQuery();
	}
	
	/**
	 * Request for a single item.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	public function getOne ()
	{
		$this->setLimit(1);
		$sQuery = $this->composeQuery();
		$aCollection = $this->exec();
		if ( isset($aCollection[0]) ) {
			return $aCollection[0];
		} else {
			return null;
		}
	}
	
	/**
	 * Request with a row type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Row type set
	 */
	/*
	return array2(
		[0]	=> val0
		[field0]	=> val0
		[1]	=> val1
		[field1]	=> val1
		[2]	=> val2
		[field2]	=> val2
	);
	*/
	public function getRow ($oDB=null)
	{
		$sQuery = $this->composeQuery();
		if ( $oDB===null ) {
			$oDB = $this->getConnexion();
		}
		
		$rQuery = $oDB->executeQuery($sQuery);
		if ( !$rQuery ) {
			echo'<pre> ??? ';print_r($sQuery);echo'</pre>';
			die;
			
			die('Query problem');
		}
		$aValues = array();
		while ( $aDatas = $oDB->fetchRow($rQuery) ) {
			$aValues[] = $aDatas;
		}
		return $aValues;
	}
	
	/**
	 * Request with an associative type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Assocation type set
	 */
	/*
	return array2(
		[field0]	=> val0
		[field1]	=> val1
		[field2]	=> val2
	);
	*/
	public function getAssoc ($oDB=null)
	{
		$sQuery = $this->composeQuery();
		if ( $oDB===null ) {
			$oDB = $this->getConnexion();
		}
		
		$rQuery = $oDB->executeQuery($sQuery);
		if ( !$rQuery ) {
			echo'<pre> ??? ';print_r($sQuery);echo'</pre>';
			die;
			
			die('Query problem');
		}
		$aValues = array();
		while ( $aDatas = $oDB->fetchAssoc($rQuery) ) {
			$aValues[] = $aDatas;
		}
		return $aValues;
	}
	
	/**
	 * Request with a list type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     list type set
	 */
	/*
	return array2(
		[0]	=> val0
		[1]	=> val1
		[2]	=> val2
	);
	*/
	public function getList ($oDB=null)
	{
		$sQuery = $this->composeQuery();
		if ( $oDB===null ) {
			$oDB = $this->getConnexion();
		}
		
		$rQuery = $oDB->executeQuery($sQuery);
		if ( !$rQuery ) {
			echo'<pre> ??? ';print_r($sQuery);echo'</pre>';
			die;
			
			die('Query problem');
		}
		$aValues = array();
		while ( $aDatas = $oDB->fetchList($rQuery) ) {
			$aValues[] = $aDatas;
		}
		
		return $aValues;
	}
	
	/**
	 * Request with a list type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     list type set
	 */
	/*
	return array2(
		[0]	=> val0
		[1]	=> val1
		[2]	=> val2
	);
	*/
	public function getListBy ($sKeyField, $sValueField, $oDB=null)
	{
		$sKeyField		= strtolower($sKeyField);
		$sValueField	= strtolower($sValueField);
		
		$this->aQueryElements[0]['PARAMS']['PARAMS'] = array($sKeyField, $sValueField);
		
		$sQuery = $this->composeQuery();
		if ( $oDB===null ) {
			$oDB = $this->getConnexion();
		}
		
		$rQuery = $oDB->executeQuery($sQuery);
		if ( !$rQuery ) {
			echo'<pre> ??? ';print_r($sQuery);echo'</pre>';
			die;
			
			die('Query problem');
		}
		
		$sAlias = $this->aTables[0]['ALIAS'];
		$aValues = array();
		while ( $aDatas = $oDB->fetchAssoc($rQuery) ) {
			$aValues[ $aDatas[$sAlias .'.'. $sKeyField] ] = $aDatas[$sAlias .'.'. $sValueField];
		}
		
		return $aValues;
	}
	
	/**
	 * Allows you to modify the GROUP BY clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function setFields ($aFields)
	{
		$this->aQueryElements[0]['PARAMS']['PARAMS'] = $aFields;
		return $this;
	}
	
	/**
	 * Allows you to modify the GROUP BY clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function setGroupBy ($sGroupBy)
	{
		$aAfters = array('GROUPBY', 'HAVING', 'ORDERBY', 'LIMIT');
		for ( $i=0, $iMax=count($this->aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement = $this->aQueryElements[$i];
			if ( in_array($aQueryElement['NAME'], $aAfters, true) ) {
				array_splice($this->aQueryElements, $i, 0, array(array(
					'NAME'		=> 'GROUPBY',
					'PARAMS'	=> array($sGroupBy),
				)));
				return $this;
			}
		}
		$this->aQueryElements[] = array(
			'NAME'		=> 'GROUPBY',
			'PARAMS'	=> array($sGroupBy),
		);
		
		return $this;
	}
	
	/**
	 * Allows you to modify the ORDER BY clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function setOrderBy ($sOrderBy)
	{
		$aAfters = array('ORDERBY', 'LIMIT');
		for ( $i=0, $iMax=count($this->aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement = $this->aQueryElements[$i];
			if ( in_array($aQueryElement['NAME'], $aAfters, true) ) {
				array_splice($this->aQueryElements, $i, 0, array(array(
					'NAME'		=> 'ORDERBY',
					'PARAMS'	=> array($sOrderBy),
				)));
				return $this;
			}
		}
		$this->aQueryElements[] = array(
			'NAME'		=> 'ORDERBY',
			'PARAMS'	=> array($sOrderBy),
		);
		
		return $this;
	}
	
	/**
	 * Allows you to modify the LIMIT clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerSelectMySQLPDO ($this)
	 */
	public function setLimit ($iLimitBottom, $iNbElements=null)
	{
		$aAfters = array('LIMIT');
		for ( $i=0, $iMax=count($this->aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement = $this->aQueryElements[$i];
			if ( in_array($aQueryElement['NAME'], $aAfters, true) ) {
				array_splice($this->aQueryElements, $i, 1, array(array(
					'NAME'		=> 'LIMIT',
					'PARAMS'	=> array($iLimitBottom, $iNbElements),
				)));
				return $this;
			}
		}
		$this->aQueryElements[] = array(
			'NAME'		=> 'LIMIT',
			'PARAMS'	=> array($iLimitBottom, $iNbElements),
		);
		
		return $this;
	}
}
