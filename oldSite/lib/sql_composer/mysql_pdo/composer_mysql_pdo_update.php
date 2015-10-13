<?php
/**
 * @desc        PDO-MySQL UPDATE queries composition classes
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

class ComposerUpdateMySQLPDO extends ComposerMySqlPDO implements ComposerUpdate
{
	/**
	 * Update clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function __construct ($sClass, $aParams, $oConnexion)
	{
		$aParams = array(
			'QUERY_CLASS'	=> $sClass,
			'PARAMS'		=> $aParams
		);
		$this->addQueryElement('UPDATE', $aParams);
		$this->setConnexion( $oConnexion );
	}
	
	/**
	 * Join clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
	 */
	public function join		()
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
	 */
	public function alias		()
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
	 */
	public function on			()
	{
		$aParams = func_get_args();
		$this->addQueryElement( strtoupper(__FUNCTION__), $aParams);
		return $this;
	}
	
	/**
	 * Set clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
	 */
	public function set			()
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
	 */
	public function _xor_		()
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
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
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
		
		$aTables		= array();
		$aAlias			= array();	// Verifications
		$aRenames		= array();	// Verifications
		$sSelectClause	= '*';
		$sQuery			= '';
		$sQueryAfter	= '';
		$aQueryElements	= $this->getQueryelEments();
		$aLimitClause	= array();
		$aValues		= array();
		
		$iStep = 0;	// query clause verificator
		
		for ( $i=0, $iMax=count($aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement	= $aQueryElements[ $i ];
			$sElementName	= $aQueryElement['NAME'];
			$aElementParams	= $aQueryElement['PARAMS'];
			
			switch ( $sElementName ) {
				case	'UPDATE'	: {
					$aTable = $this->getTableDatas( $aElementParams['QUERY_CLASS'], $aQueryElements, $i );
					$aTables[] = $aTable;
					$aAlias[] = $aTable['ALIAS'];
					$aRenames[] = $aTable['RENAME'];
					
					$sQuery .= 'UPDATE `'. $aTable['NAME'] .'` AS '. $aTable['ALIAS'] .' ';
				} break;
				case	'ALIAS'		: {
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
				case	'ON'		: {
					$sQuery .= ' ON '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				
				case	'SET'		: {
					//echo"<pre>";var_dump($aElementParams);echo"</pre><hr/>";
					if ( is_array($aElementParams[0]) ) {
						$aValues	= $aElementParams[0];
					} else if ( count($aElementParams)==2 ) {
						if ( is_string($aElementParams[1]) ) {
							$aValues[ $aElementParams[0] ] = $this->protect($aElementParams[1]);
						} else {
							$aValues[ $aElementParams[0] ] = $this->protect($aElementParams[1]);
						}
					} else if ( count($aElementParams)==3 ) {
						if ( $aElementParams[2]===true ) {
							$aValues[ $aElementParams[0] ] = $aElementParams[1];
						} else {
							if ( is_string($aElementParams[1]) ) {
								$aValues[ $aElementParams[0] ] = $this->protect($aElementParams[1]);
							} else {
								$aValues[ $aElementParams[0] ] = $this->protect($aElementParams[1]);
							}
						}
					}
				} break;
				case	'WHERE'		: {
					$sQueryAfter .= ' WHERE '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_AND_'		: {
					$sQueryAfter .= ' AND '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_OR_'		: {
					$sQueryAfter .= ' OR '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'_XOR_'		: {
					$sQueryAfter .= ' XOR '. $this->composeWhereClause($aElementParams, $aTables);
				} break;
				case	'LIMIT'		: {
					$aLimitClause[]		= $aQueryElement;
				} break;
			}
		}
		//var_dump($aValues);
		/* SET */
		$aRValues = array();
		foreach ( $aValues as $sKey => $sValue ) {
			$sKey = $this->getSelectedField($sKey, $aTables);
			$aRValues[] = $sKey[1] .' = '. $sValue;
		}
		$sSetClause = implode(', ', $aRValues);
		//echo $sSetClause . '<hr />';
		
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
		
		if ( $sLimitClause!='' ) {
			$sQueryAfter .= ' LIMIT '. $sLimitClause;
		}
		
		$sQuery = $sQuery . ' SET '. $sSetClause .' '. $sQueryAfter;
		
		$this->aTables = $aTables;
		//echo $sQuery . '<hr />';
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
			die;
			
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
	 * @return     Ress      Query result
	 */
	public function getOne($oDB=null)
	{
		$this->setLimit(1);
		$this->exec($oDB);
	}
	
	/**
	 * Allows you to modify the LIMIT clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerUpdateMySQLPDO ($this)
	 */
	public function setLimit($iLimit)
	{
		
	}
}
