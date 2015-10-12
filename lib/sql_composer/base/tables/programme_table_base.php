<?php
class programmeBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'programmeCollection';
	}
	public static function getRecordClassName ()
	{
		return 'programmeRecord';
	}
	
	public static function getTableName ()
	{
		return 'programme';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'programme_id',
  1 => 'programme_titre',
  2 => 'programme_titre_color',
  3 => 'programme_description_fr',
  4 => 'programme_description_en',
  5 => 'programme_partenaire',
  6 => 'programme_identifiant',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'programme_id',
);
	}
	
	public static function getAutoJoins ()
	{
		return array (
  0 => 
  array (
    'TABLE_FROM' => 'programme',
    'FIELD_FROM' => 'programme_id',
    'TABLE_TO' => 'programme_annonce',
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
