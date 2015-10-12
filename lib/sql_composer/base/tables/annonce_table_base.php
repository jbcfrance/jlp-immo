<?php
class annonceBase extends SQLComposerTable
{
	protected static $oConnexion = null;
	
	public static function getCollectionClassName ()
	{
		return 'annonceCollection';
	}
	public static function getRecordClassName ()
	{
		return 'annonceRecord';
	}
	
	public static function getTableName ()
	{
		return 'annonce';
	}
	
	public static function getFields ()
	{
		return array (
  0 => 'annonce_id',
  1 => 'annonce_agence_id',
  2 => 'annonce_negociateur_id',
  3 => 'status_annonce',
  4 => 'reference',
  5 => 'nummandat',
  6 => 'typemandat',
  7 => 'categorieoffre',
  8 => 'typebien',
  9 => 'categorie',
  10 => 'datecreation',
  11 => 'datemodification',
  12 => 'datedebutmandat',
  13 => 'dateecheancemandat',
  14 => 'datedisponibiliteouliberation',
  15 => 'adresse',
  16 => 'codepostalpublic',
  17 => 'villepublique',
  18 => 'villeaafficher',
  19 => 'pays',
  20 => 'quartier',
  21 => 'environnement',
  22 => 'proximite',
  23 => 'transports',
  24 => 'montant',
  25 => 'charges',
  26 => 'loyer',
  27 => 'depotgarantie',
  28 => 'fraisdivers',
  29 => 'loyergarage',
  30 => 'agetete',
  31 => 'typerente',
  32 => 'taxehabitation',
  33 => 'taxefonciere',
  34 => 'fraisdenotairereduits',
  35 => 'pieces',
  36 => 'chambres',
  37 => 'sdb',
  38 => 'nbsallesdeau',
  39 => 'nbwc',
  40 => 'nbparking',
  41 => 'nbgarages',
  42 => 'niveaux',
  43 => 'nbetages',
  44 => 'etage',
  45 => 'surface',
  46 => 'surfacecarrezouhabitable',
  47 => 'surfaceterrain',
  48 => 'surfacesejour',
  49 => 'surfaceterrasse',
  50 => 'surfacebalcon',
  51 => 'acceshandicape',
  52 => 'alarme',
  53 => 'ascenseur',
  54 => 'balcon',
  55 => 'bureau',
  56 => 'cave',
  57 => 'cellier',
  58 => 'dependances',
  59 => 'dressing',
  60 => 'gardien',
  61 => 'interphone',
  62 => 'lotissement',
  63 => 'meuble',
  64 => 'mitoyenne',
  65 => 'piscine',
  66 => 'terrasse',
  67 => 'anciennete',
  68 => 'anneeconstruction',
  69 => 'exposition',
  70 => 'typechauffage',
  71 => 'naturechauffage',
  72 => 'modechauffage',
  73 => 'typecuisine',
  74 => 'coupdecoeur',
  75 => 'texte',
  76 => 'textanglais',
  77 => 'urlvisitevirtuelle',
  78 => 'photocoeur',
  79 => 'photomedium',
  80 => 'listephotoorig',
  81 => 'photothumb',
  82 => 'photoorigmd5',
  83 => 'consommationenergie',
  84 => 'emissionges',
);
	}
	
	public static function getKeys ()
	{
		return array (
  0 => 'annonce_id',
);
	}
	
	public static function getAutoJoins ()
	{
		return array (
  0 => 
  array (
    'TABLE_FROM' => 'annonce',
    'FIELD_FROM' => 'annonce_id',
    'TABLE_TO' => 'programme_annonce',
    'FIELD_TO' => 'annonce_id',
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
