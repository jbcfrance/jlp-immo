<?php
class passerelleBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'passerelleCollection';
	}
	public static function getRecordClassName ()
	{
		return 'passerelleRecord';
	}
	
	public static function getTableName ()
	{
		return 'passerelle';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'passerelle_id',
  1 => 'passerelle_date',
  2 => 'passerelle_log',
  3 => 'passerelle_nbannonceajouter',
  4 => 'passerelle_nbannoncesuppr',
  5 => 'passerelle_nbphotomaj',
  6 => 'passerelle_nbannoncetraite',
  7 => 'passerelle_statut',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'passerelle_id',
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
