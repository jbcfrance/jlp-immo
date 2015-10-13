<?php
/**
 * @desc        DB connexion class 
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

abstract class SQLComposerConnexion
{
	private static	$oLastSQLComposerConnexion	= null,
					$aSQLComposerConnexion		= array();
	
	/**
	 * Set the last opened connexion
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposerConnexion object
	 * @param      Int       SGBD type
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public static function setLastConnexion ( SQLComposerConnexion $oSQLComposerConnexion, $iType )
	{
		self::$oLastSQLComposerConnexion[$iType] = $oSQLComposerConnexion;
	}
	
	/**
	 * Last opened connexion getter
	 *
	 * @author               Tavm\@n
	 * @param      Int       SGBD type
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposerConnexion object
	 */
	public static function getLastConnexion ($iType)
	{
		return self::$oLastSQLComposerConnexion[$iType];
	}

	/**
	 * Add a database
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposerConnexion object
	 * @param      Int       SGBD type
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public static function addDBRessource ( SQLComposerConnexion $oSQLComposerConnexion, $iType )
	{
		self::$aSQLComposerConnexion[$iType][] = $oSQLComposerConnexion;
	}
	
	private $rDB = null;
	
	/**
	 * Set the ressource connexion for DB access.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      DB access
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setDBRessource ($rDB)
	{
		$this->rDB = $rDB;
	}
	
	/**
	 * Ressource connexion for DB access getter
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Ress      DB access
	 */
	public function getDBRessource ()
	{
		return $this->rDB;
	}
	
	/**
	 * Execute a query
	 *
	 * @author               Tavm\@n
	 * @param      String    Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Ress      Query
	 */
	public abstract function executeQuery ($sQuery);
	
	/**
	 * Query result row type getter.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Result row type
	 */
	public abstract function fetchRow ($rQueryResult);
	
	/**
	 * Query result association type getter.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Result association type
	 */
	public abstract function fetchAssoc ($rQueryResult);
	
	/**
	 * Query result list type getter.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Result list type
	 */
	public abstract function fetchList ($rQueryResult);
	
	/**
	 * 
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       Records count
	 */
	public abstract function fetchLengths ($rQueryResult);
	
	/**
	 * Error getter
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Mixed     Error
	 */
	public abstract function getError ();
	
	/**
	 * Error id getter
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       Error
	 */
	public abstract function getErrNo ();
	
	/**
	 * Make the connexion to the SGBD
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposer object
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if the connexion is OK, else FALSE
	 */
	public abstract function connect ();
	
	/**
	 * Free result set
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public abstract function freeResults ($rQueryResult);
	
	/**
	 * Get the last insert id.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       Last ID
	 */
	public abstract function getLastInsertId ();
}
