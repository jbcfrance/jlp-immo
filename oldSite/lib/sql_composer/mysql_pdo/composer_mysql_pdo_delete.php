<?php
/**
 * @desc        PDO-MySQL DELETE queries composition classes
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
 *              clause can have between 1 and 5 parameters. So this class doesn't describe
 *              query-composition functions and you have to use func_get_args() to get arguments.
 */

class ComposerDeleteMySQLPDO extends ComposerMySqlPDO implements ComposerDelete
{
	/**
	 * Delete clause adding.
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
		$this->addQueryElement('DELETE', $aParams);
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
	 * Alias clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
	 */
	public function alias		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Join clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
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
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
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
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
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
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
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
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
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
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
	 */
	public function _xor_		()
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
	 * @return     Object    ComposerDeleteMySQLPDO ($this)
	 */
	public function limit		()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
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
		$aTables			= array();
		$aQueryElements		= $this->getQueryelEments();
		$aTables			= array();
		$aTablesToDelete	= array();
		$aAlias				= array();
		$aRenames			= array();
		$aOrderByClause		= array();
		$aLimitClause		= array();
		$sQuery				= '';
		
		for ( $i=0, $iMax=count($aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement	= $aQueryElements[ $i ];
			$sElementName	= $aQueryElement['NAME'];
			$aElementParams	= $aQueryElement['PARAMS'];
			
			switch ( $sElementName ) {
				case 'DELETE'	: {
					$aTable = $this->getTableDatas( $aElementParams['QUERY_CLASS'], $aQueryElements, $i );
					if ( !empty($aElementParams['PARAMS']) ) {
						$aTablesToDelete = $aElementParams['PARAMS'];
					}
					$aTables[]	= $aTable;
					$aAlias[]	= $aTable['ALIAS'];
					$aRenames[]	= $aTable['RENAME'];
					
				} break;
				case 'ALIAS'	: {
					// Pris en charge dans le SELECT et le JOIN
				} break;
				case	'JOIN'		: {
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
				case 'ON'		: {
					$sQuery .= ' ON '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'WHERE'		: {
					if ( count($aTables)==1 ) {
						$aTables[0]['ALIAS'] = $aTables[0]['NAME'];
					}
					$sQuery .= ' WHERE '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_AND_'		: {
					$sQuery .= ' AND '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_OR_'		: {
					$sQuery .= ' OR '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_XOR_'		: {
					$sQuery .= ' XOR '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case 'ORDERBY'	: {
					$aOrderByClause[]	= $aQueryElement;
				} break;
				case 'LIMIT'	: {
					$aLimitClause[]		= $aQueryElement;
				} break;
			}
		}
		
		if ( count($aTables)==1 ) {
			$aTables[0]['ALIAS'] = $aTables[0]['NAME'];
		}
		
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
				if ( count($aLimitClause[0]['PARAMS'])==1 ) {
					$sLimitClause = $aLimitClause[0]['PARAMS'][0];
				} else {
					$sLimitClause = $aLimitClause[0]['PARAMS'][0] .', '. $aLimitClause[0]['PARAMS'][1];
				}
			} else if ( is_string($aLimitClause[0]['PARAMS']) ) {
				$sLimitClause = $aLimitClause[0]['PARAMS'];
			}
		}
		
		
		/* Selected Tables */
		if ( count($aTables) == 1 ) {
			$sQuery = 'DELETE FROM `'. $aTables[0]['NAME'] .'` '. $sQuery;
		} else {
			if ( $sOrderBy!='' ) {
				throw new Exception('MySQL syntax unsuported (multi-table "DELETE" with an "ORDER BY" clause)');
			}
			if ( $sLimitClause!='' ) {
				throw new Exception('MySQL syntax unsuported (multi-table "DELETE" with a "LIMIT" clause)');
			}
			
			$aQueryTables = array();
			if ( empty($aTablesToDelete) ) {
				for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
					$aTable = $aTables[$i];
					$aQueryTables[] = ' '. $aTable['ALIAS'] .'.*';
				}
			} else {
				for ( $i=0, $iMax=count($aTablesToDelete) ; $i<$iMax ; $i++ ) {
					$aTableToDel = $aTablesToDelete[$i];
					for ( $j=0, $jMax=count($aTables) ; $j<$jMax ; $j++ ) {
						$aTable = $aTables[$j];
						if ( strtolower($aTable['RENAME']) == strtolower($aTableToDel) ) {
							$aQueryTables[] = ' '. $aTable['ALIAS'] .'.*';
							break;
						}
					}
				}
			}
			$sQuery = 'DELETE '. implode(', ', $aQueryTables) .' FROM '. $aTables[0]['NAME'] .' AS '. $aTables[0]['ALIAS'] .' '. $sQuery;
		}
		
		if ( $sOrderBy!='' ) {
			$sQuery .= ' ORDER BY '. $sOrderBy;
		}
		if ( $sLimitClause!='' ) {
			$sQuery .= ' LIMIT '. $sLimitClause;
		}
		
		return $sQuery;
	}
	
	/**
	 * Query execution.
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposerConnexion object
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Ress      Query result
	 */
	public function exec ()
	{
		$sQuery = $this->composeQuery();
		$oDB = $this->getConnexion(SQLComposer::DB_TYPE_MYSQL);
		
		$rQuery = $oDB->executeQuery($sQuery);
		if ( !$rQuery ) {
			echo'<pre> ??? ';print_r($sQuery);echo'</pre>';
			die('Query problem');
		}
		return $rQuery;
	}
	
	/**
	 * Returns the query string.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Query string
	 */
	public function get ()
	{
		return $this->composeQuery();
	}
}
