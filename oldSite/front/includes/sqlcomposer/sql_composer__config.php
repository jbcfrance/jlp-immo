<?php
// Type du SGBD :
define('SQL_COMPOSER_SGBD', 'mysql');

// Connexion :
SQLComposer::connect( array(
	'HOST'	=> 'localhost',
	'USER'	=> 'root',
	'PASS'	=> 'c0gn!t00',
	'BASE'	=> 'site_jlp'
	)
);

// Includes :
$GLOBALS['AUTO_LOAD']['ADMIN_BASE'] = LIB_PATH.'bases/admin_base.class.php';
$GLOBALS['AUTO_LOAD']['AGENCE_BASE'] = LIB_PATH.'bases/agence_base.class.php';
$GLOBALS['AUTO_LOAD']['ANNONCE_BASE'] = LIB_PATH.'bases/annonce_base.class.php';
$GLOBALS['AUTO_LOAD']['NEGOCIATEUR_BASE'] = LIB_PATH.'bases/negociateur_base.class.php';
$GLOBALS['AUTO_LOAD']['PROGRAMME_BASE'] = LIB_PATH.'bases/programme_base.class.php';
$GLOBALS['AUTO_LOAD']['PROGRAMME_ANNONCE_BASE'] = LIB_PATH.'bases/programme_annonce_base.class.php';
$GLOBALS['AUTO_LOAD']['SITE_BASE'] = LIB_PATH.'bases/site_base.class.php';

// Extentions :
$GLOBALS['AUTO_LOAD']['ADMIN'] = LIB_PATH.'admin.class.php';
$GLOBALS['AUTO_LOAD']['AGENCE'] = LIB_PATH.'agence.class.php';
$GLOBALS['AUTO_LOAD']['ANNONCE'] = LIB_PATH.'annonce.class.php';
$GLOBALS['AUTO_LOAD']['NEGOCIATEUR'] = LIB_PATH.'negociateur.class.php';
$GLOBALS['AUTO_LOAD']['PROGRAMME'] = LIB_PATH.'programme.class.php';
$GLOBALS['AUTO_LOAD']['PROGRAMME_ANNONCE'] = LIB_PATH.'programme_annonce.class.php';
$GLOBALS['AUTO_LOAD']['SITE'] = LIB_PATH.'site.class.php';

SQLComposer::createExtendedObject('ADMIN_BASE', 'ADMIN');
SQLComposer::createExtendedObject('AGENCE_BASE', 'AGENCE');
SQLComposer::createExtendedObject('ANNONCE_BASE', 'ANNONCE');
SQLComposer::createExtendedObject('NEGOCIATEUR_BASE', 'NEGOCIATEUR');
SQLComposer::createExtendedObject('PROGRAMME_BASE', 'PROGRAMME');
SQLComposer::createExtendedObject('PROGRAMME_ANNONCE_BASE', 'PROGRAMME_ANNONCE');
SQLComposer::createExtendedObject('SITE_BASE', 'SITE');
