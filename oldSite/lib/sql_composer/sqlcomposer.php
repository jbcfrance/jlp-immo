<?php
/**
 * @desc        DB Abstraction initialisation class 
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

class SQLComposer
{
	const	DB_TYPE_MYSQL	= 1;
	
	private static	$aDbLinks			= array(),
					$aQueriesTypeCaller	= array(),
					$oLastConnexion		= null;
	
	/**
	 * Verify if an object is an SQLComposer
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     Value to verify
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if the parameter is an SQLComposer, else FALSE
	 */
	public static function is_SQLComposer ($oDB)
	{
		return ! is_object($oDB)
				&&	is_a($oDB, 'ComposerDelete')
				&&	is_a($oDB, 'ComposerInsert')
				&&	is_a($oDB, 'ComposerUpdate')
				&&	is_a($oDB, 'ComposerSelect');
	}
	
	/**
	 * Add a database
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposer object
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public static function addDbLink ($oDB)
	{
		self::$aDbLinks[] = $oDB;
		self::$oLastConnexion = $oDB;
	}
	
	/**
	 * Allows you to create connexion object
	 *
	 * @author               Tavm\@n
	 * @param      Array     Initilisation datas for a database
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public static function initDB ($aInits)
	{
		if ( isset($aInits['type']) ) {
			switch ( strtolower($aInits['type']) ) {
				case 'mysql'	:
				case 'my_sql'	:
				case 'my sql'	: {
					require_once('mysql/composer_mysql.php');
					$oConnexion = new composerConnexionMySQL($aInits);
				} break;
				case 'mysql_pdo':{
					require_once('mysql_pdo/composer_mysql_pdo.php');
					$oConnexion = new composerConnexionMySqlPDO($aInits);
				} break;
			}
		}
		self::addDbLink($oConnexion);
		return $oConnexion;
	}
	
	/**
	 * Last created connexion accessor
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Object    SQLComposer object
	 */
	public static function getLastConnexion ()
	{
		return self::$oLastConnexion;
	}
	
	/**
	 * Protect a field using the last connexion added
	 *
	 * @author               Tavm\@n
	 * @param      Mixed     A value to protect
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Mixed     Protected value for query on THE LAST OPENED database
	 */
	public static function protect ($sField)
	{
		return self::getLastConnexion()->protect($sField);
	}
	
	/**
	 * Get the last insert id.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       Last ID
	 */
 	public static function getLastInsertId ()
	{
		return self::getLastConnexion()->getLastInsertId();
	}
}
