<?php
/**
 * @desc        Records elements definition
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

class SQLComposerRecord
{
	protected	$aValues	= array(),
				$aJoins		= array();
	
	/**
	 * Record initialisation
	 *
	 * @author               Tavm\@n
	 * @param      Array     Fields result
	 * @param      String    Record table alias
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setFieldsFromQuery ($aFields, $sAlias)
	{
		$sAlias = $sAlias;
		foreach ( $aFields as $sField => $mValue ) {
			$sField = $sField;
			if ( $sField!=($sKey=str_ireplace($sAlias .'.', '', $sField)) ) {
				$this->aValues[ $sKey ] = $mValue;
			}
		}
	}
	
	/**
	 * Return a unique string representing the record
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Unique string representing the record
	 */
	public function __toString ()
	{
		/* retourne une String unique representative du Record */
		return serialize( $this->getValues() );
	}
	
	/**
	 * Joins setter
	 *
	 * @author               Tavm\@n
	 * @param      String    Currect table name
	 * @param      Array     All records
	 * @param      Array     Current query tables definitions
	 * @param      Array     Current query tables name
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function addJoins ($sTableName, $aRecords, $aTablesDef, $aTablesName)
	{
		foreach ( $aRecords as $sTable => $oRecord ) {
			if ( $sTableName!=$sTable ) {
				$sClass = $aTablesName[$sTable];
				if ( ! isset($this->aJoins[ $sTable ]) ) {
					$sClass = $sClass .'Collection';
					$this->aJoins[ $sTable ] = new $sClass();
				}
				$this->aJoins[ $sTable ][] = $oRecord;
			}
		}
	}
	
	/**
	 * Record variables and joins getter and setter.
	 *
	 * @author               Tavm\@n
	 * @param      String    Method name
	 * @param      Array     Parametters
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Mixed     SQLComposerCollection when asking for a join,
	 *                       Mixed value when asking for a variable
	 *                       void when setting a variable or a join
	 */
	public function __call ($sMeth, $aParams)
	{
		$sMeth = strtolower(trim($sMeth));
		
		$sPatternGet = '#^(get|get_)(.*)$#iU';
		$sPatternSet = '#^(set|set_)(.*)$#iU';
		$aMatchs = array();
		
		if ( preg_match($sPatternGet, $sMeth, $aMatchs) ) {
			$sKey = strtolower($aMatchs[2]);
			if ( isset( $this->aValues[ $sKey ] ) ) {
				return $this->aValues[ $sKey ];
			} else if ( isset( $this->aJoins[ $sKey ] ) ) {
				if ( isset($aParams[0]) && is_int($aParams[0]) ) {
					return $this->aJoins[ $sKey ][ $aParams[0] ];
				} else {
					return $this->aJoins[ $sKey ];
				}
			}
			return null;
		} else if ( preg_match($sPatternSet, $sMeth, $aMatchs) && count($aParams)>=1 ) {
			$this->aValues[ strtolower($aMatchs[2]) ] = $aParams[0];
			return $this;
		}
	}
	
	/**
	 * Record variables setter.
	 *
	 * @author               Tavm\@n
	 * @param      String    Parametter name
	 * @param      Mixed     Parametter value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	public function set ($sParam, $mValue)
	{
		$this->aValues[ $sParam ] = $mValue;
		return $this;
	}
	
	/**
	 * Record variables setter.
	 *
	 * @author               Tavm\@n
	 * @param      Array     Parametters
	 * @param      Mixed     Parametter value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	protected function setValues ($aParams)
	{
		foreach ($aParams as $sKey => $sValue) {
			$this->set($sKey, $sValue);
		}
		return $this;
	}
	
	/**
	 * Record variables getter.
	 *
	 * @author               Tavm\@n
	 * @param      String    Parametter name
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Mixed     Parametter value
	 */
	public function get ($sParam)
	{
		$sParam = strtolower($sParam);
		if ( isset( $this->aValues[ $sParam ] ) ) {
			return $this->aValues[ $sParam ];
		} else if ( isset( $this->aJoins[ $sParam ] ) ) {
			return $this->aJoins[ $sParam ];
		}
		return null;
	}
	
	/**
	 * Record variables list getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Parametters list
	 */
	public function getValues ()
	{
		return $this->aValues;
	}
	
	/**
	 * Send a query for select the current record. Only work when keys are defined.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	public function select()
	{
		$sTableClassName = $this->getTableClassName();
		$aPKs = call_user_func(array($sTableClassName, 'getKeys'));
		$oQuery = call_user_func(array($sTableClassName, 'select'))
							->alias($sTableClassName)
							->where('1', 1);
		for ( $i=0, $iMax=count($aPKs) ; $i<$iMax ; $i++ ) {
			$sPK = $aPKs[$i];
			$mValue = $this->get( $sPK );
			if ( $mValue===null ) {
				throw new Exception('Record select need all PRIMARY KEYS defined. &laquo;'. $sPK .'&raquo; undefined.');
			} else {
				$oQuery->_and_($sPK, $mValue);
			}
		}
		
		$aResults = $oQuery->limit(1)->getOne();
		if ( $aResults!==null ) {
			$this->setValues($aResults->getValues());
			return $this;
		} else {
			return false;
		}
	}
	
	/**
	 * Send a query for update the current record. Only work when keys are defined.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	public function update ()
	{
		$sTableClassName = $this->getTableClassName();
		$aPKs = call_user_func(array($sTableClassName, 'getKeys'));
		$oQuery = call_user_func(array($sTableClassName, 'update'));
		
		$aValues = $this->getValues();
		foreach ( $aValues as $sKey => $mValue ) {
			$oQuery->set($sKey, $mValue);
		}
		
		$oQuery->where('1', 1);
		for ( $i=0, $iMax=count($aPKs) ; $i<$iMax ; $i++ ) {
			$sPK = $aPKs[$i];
			$mValue = $this->get( $sPK );
			if ( $mValue===null ) {
				throw new Exception('Record select need all PRIMARY KEYS defined. &laquo;'. $sPK .'&raquo; undefined.');
			} else {
				$oQuery->_and_($sPK, $mValue);
			}
		}
		
		$oQuery->limit(1)->exec();
		return $this;
	}
	
	/**
	 * Send a query for insert the current record.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	public function insert ()
	{
		$sTableClassName = $this->getTableClassName();
		$aPKs = call_user_func(array($sTableClassName, 'getKeys'));
		
		$aValues = $this->getValues();
		$aValuesToInsert = array();
		foreach ( $aValues as $sKey => $mValue ) {
			if ( ! $mValue!==null ) {
				$aValuesToInsert[ $sKey ] = $mValue;
			}
		}
		$oQuery = call_user_func(array($sTableClassName, 'insert'), array_keys($aValuesToInsert))
							->values(array_values($aValuesToInsert))
							->exec();
		
		return $this;
	}
	
	/**
	 * Send a query for delete the current record. Only work when keys are defined.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerRecord
	 */
	public function delete ()
	{
		$sTableClassName = $this->getTableClassName();
		$aPKs = call_user_func(array($sTableClassName, 'getKeys'));
		$oQuery = call_user_func(array($sTableClassName, 'delete'))
							->alias($sTableClassName)
							->where('1', 1);
		for ( $i=0, $iMax=count($aPKs) ; $i<$iMax ; $i++ ) {
			$sPK = $aPKs[$i];
			$mValue = $this->get( $sPK );
			if ( $mValue===null ) {
				throw new Exception('Record select need all PRIMARY KEYS defined. &laquo;'. $sPK .'&raquo; undefined.');
			} else {
				$oQuery->_and_($sPK, $mValue);
			}
		}
		
		$aResults = $oQuery->limit(1)->exec();
		return $this;
	}
}
