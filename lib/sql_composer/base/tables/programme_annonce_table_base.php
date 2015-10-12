<?php
class programme_annonceBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'programme_annonceCollection';
	}
	public static function getRecordClassName ()
	{
		return 'programme_annonceRecord';
	}
	
	public static function getTableName ()
	{
		return 'programme_annonce';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'programme_id',
  1 => 'annonce_id',
);
	}
	
	public static function getKeys ()
	{
		return array (
);
	}
	
	public static function getAutoJoins ()
	{
		return array (
  0 => 
  array (
    'TABLE_FROM' => 'programme_annonce',
    'FIELD_FROM' => 'annonce_id',
    'TABLE_TO' => 'annonce',
    'FIELD_TO' => 'annonce_id',
  ),
  1 => 
  array (
    'TABLE_FROM' => 'programme_annonce',
    'FIELD_FROM' => 'programme_id',
    'TABLE_TO' => 'programme',
    'FIELD_TO' => 'programme_id',
  ),
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
