<?php
/**
 * @desc        MySQL PDO General queries
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

class composerConnexionMySQLPDO extends SQLComposerConnexion
{
	private $sConnexion			= null,
			$sUser				= null,
			$sPass				= null,
			$bConnexionIsSecure	= false,
			$sCharset			= null,
			$bIsBuffered		= false,
			$rQuery				= null;
	
	/**
	 * Protect a field for a query using PDO-MySQL
	 *
	 * @author               Tavm\@n
	 * @param      String    Value to protect
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    Protected value
	 */
	public function protect ($sField)
	{
		return $this->getDBRessource()->quote($sField);
	}
	
	/**
	 * PDO-MySQL connexion initialisation
	 *
	 * @author               Tavm\@n
	 * @param      Array     Connexion parametters
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function __construct($aInits)
	{
		$aInits = array_change_key_case($aInits, CASE_LOWER);
		if ( isset($aInits['connexion'], $aInits['user'], $aInits['pass']) ) {
			$this->setConnexion($aInits['connexion']);
			$this->setUser($aInits['user']);
			$this->setPass($aInits['pass']);
			$this->connect();
		}
	}
	
	/**
	 * Sets the connexion string.
	 *
	 * @author               Tavm\@n
	 * @param      String    connexion string
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setConnexion($sConnexion)
	{
		$this->sConnexion = $sConnexion;
	}
	
	/**
	 * Connexion string getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    connexion string
	 */
	public function getConnexion()
	{
		return $this->sConnexion;
	}
	
	/**
	 * Sets the user.
	 *
	 * @author               Tavm\@n
	 * @param      String    connexion user
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setUser($sUser)
	{
		$this->sUser = $sUser;
	}
	
	/**
	 * Connexion user getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    connexion user
	 */
	public function getUser()
	{
		return $this->sUser;
	}
	
	/**
	 * Sets the password.
	 *
	 * @author               Tavm\@n
	 * @param      String    connexion password
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setPass($sPass)
	{
		$this->sPass = $sPass;
	}
	
	/**
	 * Connexion password getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     String    connexion password
	 */
	public function getPass()
	{
		return $this->sPass;
	}
	
	/**
	 * Set a securised connexion.
	 *
	 * @author               Tavm\@n
	 * @param      Bool      TRUE if the connexion is secure, else FALSE
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function connexionIsSecure($bConnexionIsSecure)
	{
		$this->bConnexionIsSecure = $bConnexionIsSecure;
	}
	
	/**
	 * Set the connexion charset.
	 *
	 * @author               Tavm\@n
	 * @param      String    Charset
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setCharset($sCharset)
	{
		$this->sCharset = $sCharset;
	}
	
	/**********************************************************************************************/
	/***************************** NOT IN CONNEXION BUT IN QUERIES!!! *****************************/
	/**********************************************************************************************/
	/**
	 * Set a query result.
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query ressource
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function setQueryResult($rQuery)
	{
		$this->rQuery = $rQuery;
	}
	
	/**********************************************************************************************/
	/***************************** NOT IN CONNEXION BUT IN QUERIES!!! *****************************/
	/**********************************************************************************************/
	/**
	 * Query result getter.
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Ress      Query ressource
	 */
	public function getQueryResult()
	{
		return $this->rQuery;
	}
	
	/**
	 * Execute a query.
	 *
	 * @author               Tavm\@n
	 * @param      String    Query string
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Ress      Query ressource
	 */
	public function executeQuery($sQuery)
	{
		$rQuery = $this->getDBRessource()->query($sQuery);
		// $this->setQueryResult($rQuery);
		return $rQuery;
	}
	
	/**
	 * Get the next results in row type
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query ressource
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Row set
	 */
	/*
	return array(
		[0]	=> val0
		[field0]	=> val0
		[1]	=> val1
		[field1]	=> val1
		[2]	=> val2
		[field2]	=> val2
	);
	*/
	public function fetchRow($rQueryResult)
	{
		return $rQueryResult->fetch(PDO::FETCH_BOTH);
	}
	
	/**
	 * Get the next results in association type
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query ressource
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     Association set
	 */
	/*
	return array(
		[field0]	=> val0
		[field1]	=> val1
		[field2]	=> val2
	);
	*/
	public function fetchAssoc($rQueryResult)
	{
		return $rQueryResult->fetch(PDO::FETCH_ASSOC);
	}
	
	/**
	 * Get the next results in list type
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query ressource
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Array     List set
	 */
	/*
	return array(
		[0]	=> val0
		[1]	=> val1
		[2]	=> val2
	);
	*/
	public function fetchList($rQueryResult)
	{
		return $rQueryResult->fetch(PDO::FETCH_NUM);
	}
	
	/**
	 * Get the query count result elements
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query ressource
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Int       Query count result elements
	 */
	public function fetchLengths($rQueryResult)
	{
	}
	
	/**
	 * Get the last error
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Mixed     Last error
	 */
	public function getError()
	{
		return $this->getDBRessource()->errorInfo();
	}
	
	/**
	 * Get the last error number
	 *
	 * @author               Tavm\@n
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Mixed     Last error number
	 */
	public function getErrNo()
	{
	}
	
	/**
	 * Make the connexion
	 *
	 * @author               Tavm\@n
	 * @param      Object    SQLComposer object
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     Bool      TRUE if the connexion is OK, else FALSE
	 */
	public function connect()
	{
		try {
			$rDB = new PDO($this->getConnexion(), $this->getUser(), $this->getPass());
			$this->setDBRessource($rDB);
			self::addDBRessource($this, SQLComposer::DB_TYPE_MYSQL);
			self::setLastConnexion($this, SQLComposer::DB_TYPE_MYSQL);
			return true;
		} catch (PDOException $e) {
			return $e;
		}
	}
	
	/**
	 * Free result set
	 *
	 * @author               Tavm\@n
	 * @param      Ress      Query
	 * @version              SQLComposer v1.0.0
	 * @since                2010/12/22
	 * @return     void
	 */
	public function freeResults($rResults)
	{
		$rResults->closeCursor();
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
	public function getLastInsertId ()
	{
		return $this->getDBRessource()->lastInsertId();
	}
}
