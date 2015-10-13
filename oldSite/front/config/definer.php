<?php
/*
Ce fichier contient toutes les constantes de l'appli
*/

define('BASE_PATH',		realpath(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR );

define('CACHE_PATH',			BASE_PATH.'cache'.DIRECTORY_SEPARATOR );
define('CONFIG_PATH',			BASE_PATH.'config'.DIRECTORY_SEPARATOR );
define('INCLUDE_PATH',			BASE_PATH.'includes'.DIRECTORY_SEPARATOR );
define('ACTION_PATH',			BASE_PATH.'actions'.DIRECTORY_SEPARATOR );
define('FORM_PATH',				INCLUDE_PATH.'FormBuilder'.DIRECTORY_SEPARATOR );
define('LIB_PATH',				BASE_PATH.'lib'.DIRECTORY_SEPARATOR );

define('TEMPLATE_PATH',			BASE_PATH.'templates'.DIRECTORY_SEPARATOR );

define('WEB',					HTML_ROOT_PATH.'web/' );
define('SCRIPTS',				WEB.'js/' );
define('CSS',					WEB.'css/' );


define('TEMPLATE_EXTENSION',	'htm');



define('PARAM_GET_CONTENT', 'js_act');
?>