<?php
/**
 * @desc        Collections elements definition 
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

class SQLComposerCollection implements ArrayAccess, Iterator, Countable
{
	protected	$aRecords		= array();
	
	/**
	 * Records array getter
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Collection's records
	 */
	public function getRecords ()
	{
		return $this->aRecords;
	}
	
	/**
	 * Set a parametter to all Records in the collection
	 *
	 * @author               Tavm\@n
	 * @param      String    Parametter name
	 * @param      Mixed     Parametter value
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    Current collection
	 */
	public function set($sParam, $mValue)
	{
		$aRecords = $this->getRecords();
		for ( $i=0, $iMax=count($aRecords) ; $i<$iMax ; $i++ ) {
			$aRecords[$i]->set($sParam, $mValue);
		}
		return $this;
	}
	
	public function select()
	{
		// WHAT ?
	}
	
	/**
	 * Update a collection
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    Current collection
	 */
	public function update ()
	{
		$aRecords = $this->getRecords();
		for ( $i=0, $iMax=count($aRecords) ; $i<$iMax ; $i++ ) {
			$aRecords[$i]->update();
		}
		return $this;
	}
	
	public function insert ()
	{
		// WHAT ?
	}
	
	/**
	 * Delete a collection
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    Current collection
	 * @note                 Elements are deleted in the database, but not in currect code. So you
	 *                       can continu to use them.
	 */
	public function delete ()
	{
		$sTableClassName = $this->getTableClassName();
		$aPKs = call_user_func(array($sTableClassName, 'getKeys'));
		
		$a2Keys = array();
		
		$aRecords = $this->getRecords();
		for ( $i=0, $iMax=count($aRecords) ; $i<$iMax ; $i++ ) {
			$oRecord = $aRecords[$i];
			$aKeys = array();
			for ( $j=0, $jMax=count($aPKs) ; $j<$jMax ; $j++ ) {
				$sPK = $aPKs[$j];
				$aKeys[] = $oRecord->get($sPK);
			}
			$a2Keys[] = $aKeys;
		}
		
		$oQuery = call_user_func(array($sTableClassName, 'delete'))
				->where(implode(' AND ', $aPKs), $a2Keys, 'OR')->exec();
		return $this;
	}
	
	/* implemented functions */
	public function offsetExists($k)
	{
		return isset($this->aRecords[$k]);
	}
	
	public function offsetGet($k)
	{
		if ( isset($this->aRecords[$k]) ) {
			return $this->aRecords[$k];
		} else {
			return null;
		}
	}
	
	public function offsetSet($k,$v)
	{
		if ( in_array($v,$this->aRecords,true) ) return;
		
		if ( $k==null ) {
			$this->aRecords[] = $v;
		} else {
			$this->aRecords[$k] = $v;
		}
	}
	
	public function offsetUnset($k)
	{
		unset($this->aRecords[$k]);
	}
	
	public function valid()
	{
		return array_key_exists(key($this->aRecords),$this->aRecords);
	}
	
	public function next()
	{
		next($this->aRecords);
		return $this->aRecords;
	}
	
	public function rewind()
	{
		reset($this->aRecords);
		return $this;
	}
	
	public function key()
	{
		return key($this->aRecords);
	}
	
	public function current()
	{
		return current($this->aRecords);
	}
	
	public function count()
	{
		return count($this->aRecords);
	}
}
