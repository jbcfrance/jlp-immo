<?php
/* System */
require_once('sqlcomposer.php');
require_once('system/sqlcomposer_connexion.php');
require_once('system/interface_composer_delete.php');
require_once('system/interface_composer_insert.php');
require_once('system/interface_composer_select.php');
require_once('system/interface_composer_update.php');

require_once('system/sql_composer_table.php');
require_once('system/sql_composer_collection.php');
require_once('system/sql_composer_record.php');

/* SGBD */
require_once('mysql_pdo/composer_mysql_pdo.php');
require_once('mysql_pdo/composer_mysql_pdo_connexion.php');
require_once('mysql_pdo/composer_mysql_pdo_delete.php');
require_once('mysql_pdo/composer_mysql_pdo_insert.php');
require_once('mysql_pdo/composer_mysql_pdo_select.php');
require_once('mysql_pdo/composer_mysql_pdo_update.php');

$aTables = array (
  0 => 'admin',
  1 => 'agence',
  2 => 'annonce',
  3 => 'negociateur',
  4 => 'passerelle',
  5 => 'programme',
  6 => 'programme_annonce',
  7 => 'site',
);

$oConnexion = SQLComposer::initDB(array(
	'type'		=> 'mysql_pdo',
	'user'		=> 'root',
	'pass'		=> 'c0gn!t00',
	'connexion'	=> 'mysql:host=localhost;dbname=site_jlp',
));

$sBaseDir	= __DIR__ . DIRECTORY_SEPARATOR . 'base' . DIRECTORY_SEPARATOR;
$sIncPath	= dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'lib'
						. DIRECTORY_SEPARATOR .'sql_composer'. DIRECTORY_SEPARATOR.'includes'. DIRECTORY_SEPARATOR;
		
for ( $i=0, $iMax=count($aTables) ; $i<$iMax ; $i++ ) {
	$sTable = $aTables[$i];
	
	/* Bases */
	require_once($sBaseDir . 'tables'. DIRECTORY_SEPARATOR . $sTable .'_table_base.php');
	require_once($sBaseDir . 'collections'. DIRECTORY_SEPARATOR . $sTable .'_collection_base.php');
	require_once($sBaseDir . 'records'. DIRECTORY_SEPARATOR . $sTable .'_record_base.php');
	
	/* Files */
	require_once($sIncPath .'tables'. DIRECTORY_SEPARATOR . $sTable .'_table.php');
	require_once($sIncPath .'collections'. DIRECTORY_SEPARATOR . $sTable .'_collection.php');
	require_once($sIncPath .'records'. DIRECTORY_SEPARATOR . $sTable .'_records.php');
	
	/* Initialisation de la connexion par rapport a la table : */
	$sTableClass = $sTable .'Base';
	$sTableClass::setConnexion( $oConnexion );
}
