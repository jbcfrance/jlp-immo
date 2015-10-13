<?php
/**
 * @desc        Interface for SELECT queries composition classes
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

interface ComposerSelect
{
	/**
	 * Select clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function __construct ($sClass, $aParams, $oConnexion);
	
	/**
	 * Join clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function join		();
	
	/**
	 * Alias clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function alias		();
	
	/**
	 * On clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function on			();
	
	/**
	 * Where clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function where		();
	
	/**
	 * And clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function _and_		();
	
	/**
	 * Or clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function _or_		();
	
	/**
	 * Xor clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function _xor_		();
	
	/**
	 * Group by clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function groupBy		();
	
	/**
	 * Having clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function having		();
	
	/**
	 * Order by clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function orderBy		();
	
	/**
	 * Limit clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function limit		();
	
	/**
	 * Alows you to use cache systeme for this query.
	 *
	 * @author               Tavm\@n
	 * @param      [Bool]    TRUE if you want to use cache systeme, else FALSE
	 * @param      [Int]     Cahe life time (in seconds)
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function useCache	($bUseCache=true, $iCacheTime=3600);
	
	/**
	 * Cache using getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function getCacheUse ();
	
	/**
	 * Cache life time getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function getCacheTime ();
	
	/**
	 * Query execution.
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposerConnexion object
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function exec ();
	
	/**
	 * Returns the query string.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function get ($oDB=null);
	
	/**
	 * Request for a single item.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function getOne ();
	
	
	/**
	 * Request with a row type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
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
	public function getRow ($oDB=null);
	
	/**
	 * Request with an associative type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	/*
	return array2(
		[field0]	=> val0
		[field1]	=> val1
		[field2]	=> val2
	);
	*/
	public function getAssoc ($oDB=null);
	
	/**
	 * Request with a list type returns.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	/*
	return array2(
		[0]	=> val0
		[1]	=> val1
		[2]	=> val2
	);
	*/
	public function getList ($oDB=null);
	
	/**
	 * Allows you to modify the GROUP BY clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function setGroupBy ($sGroupBy);
	
	/**
	 * Allows you to modify the ORDER BY clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function setOrderBy ($sOrderBy);
	
	/**
	 * Allows you to modify the LIMIT clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function setLimit ($iLimitBottom, $iNbElements=null);
}
