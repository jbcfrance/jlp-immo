<?php
interface SQLComposerSGBD {
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: public function __construct
	 * Description	: Initialise la connexion
	 * @params		: - $aParams	: array(HOST, USER, PASS, BASE)
	 * @return		: void
	*/
	function __construct ( $aParams );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isOperator
	 * Description	: Verifie si une variable est un operateur pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur, sinon false
	*/
	function isOperator ( $mTryStr );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isLiaison
	 * Description	: Verifie si une variable est un operateur de liaison pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur de liaison, sinon false
	*/
	function isLiaison ( $mTryStr );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final protected static function isJointure
	 * Description	: Verifie si une variable est un operateur de jointure pour une requete SQL ou non
	 * @params		: - La variable a tester
	 * @return		: - true si la variable est un operateur de jointure, sinon false
	*/
	function isJointure ( $sTryStr );
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final public function useBind
	 * Description	: permet d'utilise un bind
	 * Params		: - $sBindName	: nom du bind a utiliser
	 * Params		: - $aVars		: liste des valeurs a remplacer
	 * Return		: true si la requete a bien ete executee, sinon false
	*/
	function useBind ( $sBindName, $aVars );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function getLastInsertID
	 * Description	: Retourne le dernier id insere
	 * @return		: - dernier id inserer
	*/
	function getLastInsertID ();
	
	/**
	 * Autor		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: private function createBind
	 * Description	: ajoutte un bind
	 * Params		: - $sBindName	: nom de la requete
	 * Params		: - $aParams	: parametres de la requete
	 * Return		: void
	*/
	function createBind ( $sBindName, $aParams );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeWhereClause
	 * Description	: compose une clause WHERE
	 * @params		: - $aParams	: liste des parametres de composition de la clause
	 * @return		: - String correspondant a la clause WHERE
	*/
	public function composeWhereClause ( $sFields, $aValues, $aOperators, $aLinks );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeUpdateClause
	 * Description	: Compose les clauses SET et VALUE d'une requete UPDATE
	 * @params		: - $aFields	: contenue des champs SET
	 * @params		: - $aValues	: contenue des champs VALUE
	 * @return		: - String correspondant a la clause
	*/
	function composeUpdateClause ( $aFields, $aValues );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function composeInsertClause
	 * Description	: Compose les clauses SET et VALUE d'une requete INSERT
	 * @params		: - $aFields	: contenue des champs SET
	 * @params		: - $aValues	: contenue des champs VALUE
	 * @return		: - String correspondant a la clause
	*/
	function composeInsertClause ( $aFields, $aValues );
	
	/*
	 * Auteur		: Romain SENECHAL
	 * Date			: 22/04/2008
	 *
	 * Name			: final private function compose
	 * Description	: Compose une requete SQL
	 * @return		: - retourne une requete SQL
	*/
	function compose ( $oComposer );
}
?>