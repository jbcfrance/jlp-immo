<?php
class adminBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'adminCollection';
	}
	public static function getRecordClassName ()
	{
		return 'adminRecord';
	}
	
	public static function getTableName ()
	{
		return 'admin';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'admin_id',
  1 => 'admin_login',
  2 => 'admin_password',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'admin_id',
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
