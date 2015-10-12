<?php
class negociateurBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'negociateurCollection';
	}
	public static function getRecordClassName ()
	{
		return 'negociateurRecord';
	}
	
	public static function getTableName ()
	{
		return 'negociateur';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'negociateur_id',
  1 => 'negociateur_agence_id',
  2 => 'prenomNegociateur',
  3 => 'nomNegociateur',
  4 => 'telephoneNegociateur',
  5 => 'emailNegociateur',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'negociateur_id',
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
