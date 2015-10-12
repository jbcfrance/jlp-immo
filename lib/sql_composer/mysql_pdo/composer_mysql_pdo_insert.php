<?php
/**
 * @desc        PDO-MySQL INSERT queries composition classes
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

class ComposerInsertMySQLPDO extends ComposerMySqlPDO implements ComposerInsert
{
	/**
	 * Insert clause adding.
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
		$this->addQueryElement('INSERT', $aParams);
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
	 * Values clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    ComposerInsertMySQLPDO ($this)
	 */
	public function values		()
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
		$aQueryElements		= $this->getQueryelEments();
		$aFieldsToInsert	= array();
		$aFieldsValues		= array();
		$bHasValues			= true;
		$aTable				= array();
		
		for ( $i=0, $iMax=count($aQueryElements) ; $i<$iMax ; $i++ ) {
			$aQueryElement	= $aQueryElements[ $i ];
			$sElementName	= $aQueryElement['NAME'];
			$aElementParams	= $aQueryElement['PARAMS'];
			
			switch ( $sElementName ) {
				case 'INSERT'	: {
					$aTable = $this->getTableDatas( $aElementParams['QUERY_CLASS'], $aQueryElements, $i );
					if ( is_array($aElementParams['PARAMS'][0]) ) {
						$aFieldsToInsert = $aElementParams['PARAMS'][0];
					} else {
						$aFieldsToInsert = $aElementParams['PARAMS'];
					}
				} break;
				case 'VALUES'	: {
					if ( is_array($aElementParams[0]) ) {
						$aFieldsValues = $aElementParams[0];
					} else if ( is_a($aElementParams[0], 'ComposerSelect') ) {
						$aFieldsValues = $aElementParams[0];
						$bHasValues = false;
					} else {
						$aFieldsValues = $aElementParams;
					}
					
					for ( $i=0, $iMax=count($aFieldsValues) ; $i<$iMax ; $i++ ) {
						$mFieldsValue = $aFieldsValues[$i];
						$aFieldsValues[$i] = $this->protect($mFieldsValue);
					}
				} break;
			}
		}
		
		if ( $bHasValues ) {
			$aFields = $aTable['FIELDS'];
			$bModifier = $this->getModifier();
			
			if ( count($aFieldsToInsert)!=count($aFieldsValues) ) {
				throw new Exception('Incorrect count values for insert query.');
			}
			
			for ( $i=0, $iMax=count($aFieldsToInsert) ; $i<$iMax ; $i++ ) {
				$sField = $aFieldsToInsert[$i];
				if ( ! in_array($sField, $aFields, true) ) {
					throw new Exception('Unknow field &laquo;'. $sField .'&raquo; in table &laquo;'. $aTable['NAME'] .'&raquo;.');
				}
			}
			
			$sQuery = 'INSERT INTO `'. $aTable['NAME'] .'` ('. implode(', ', $aFieldsToInsert) .') VALUES ('. implode(', ', $aFieldsValues) .')';
		} else {
			$sQuery = 'INSERT INTO `'. $aTable['NAME'] .'` (`'. implode('`, `', $aFieldsToInsert) .'`) '. $aFieldsValues->get();
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
		$sQuery = $this->composeQuery();
	}
}
