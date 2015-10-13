<?php
/**
 * @desc        Interface for UPDATE queries composition classes
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

interface ComposerUpdate
{
	/**
	 * Update clause adding.
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
	 * Set clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function set			();
	
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
	 * Limit clause adding.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function limit		();
	
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
	public function getOne($oDB=null);
	
	/**
	 * Allows you to modify the LIMIT clause.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 */
	public function setLimit($iLimit);
}
