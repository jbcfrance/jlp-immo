<?php
class agenceBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'agenceCollection';
	}
	public static function getRecordClassName ()
	{
		return 'agenceRecord';
	}
	
	public static function getTableName ()
	{
		return 'agence';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'agence_id',
  1 => 'raisonSocialeAgence',
  2 => 'enseigneAgence',
  3 => 'adresseAgence',
  4 => 'codePostalAgence',
  5 => 'villeAgence',
  6 => 'paysAgence',
  7 => 'telephoneAgence',
  8 => 'faxAgence',
  9 => 'emailAgence',
  10 => 'siteWebAgence',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'agence_id',
);
	}
	
	public static function getAutoJoins ()
	{
		return array (
);
	}
	
	public static function setConnexion ($oConnexion)
	{
		self::$oConnexion = $oConnexion;
	}
	
	public static function getConnexion ()
	{
		return self::$oConnexion;
	}
	
	public static function select ()
	{
		$aParams = func_get_args();
		return new ComposerSelectMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
	
	public static function insert ()
	{
		$aParams = func_get_args();
		return new ComposerInsertMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
	
	public static function update ()
	{
		$aParams = func_get_args();
		return new ComposerUpdateMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
	
	public static function delete ()
	{
		$aParams = func_get_args();
		return new ComposerDeleteMySqlPDO(__CLASS__, $aParams, self::getConnexion());
	}
}
