<?php
class siteBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'siteCollection';
	}
	public static function getRecordClassName ()
	{
		return 'siteRecord';
	}
	
	public static function getTableName ()
	{
		return 'site';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'site_variable',
  1 => 'site_valeur',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'site_variable',
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
