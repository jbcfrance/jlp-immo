<?php
require_once('definer.php');

// AUTOLOAD :
require_once(CONFIG_PATH.'__autoload.php');

require_once(CONFIG_PATH.'actions_file_includer.php');

// SQLCOMPOSER :
require_once(INCLUDE_PATH.DIRECTORY_SEPARATOR.'sqlcomposer'.DIRECTORY_SEPARATOR.'sql_composer.php');

// XTREMPLATE :
require_once(INCLUDE_PATH.DIRECTORY_SEPARATOR.'XTremplate'.DIRECTORY_SEPARATOR.'xtremplate.php');
require_once(CONFIG_PATH.'Xtremplate_conf.php');
 
// UTILITAIRES :
require_once(INCLUDE_PATH.'utils.php');
require_once(INCLUDE_PATH.'LightBox'.DIRECTORY_SEPARATOR.'light_box.php');
require_once(INCLUDE_PATH.'utils'.DIRECTORY_SEPARATOR.'files.php');
require_once(INCLUDE_PATH.'utils'.DIRECTORY_SEPARATOR.'images.php');
require_once(INCLUDE_PATH.'utils/trad.php');
require_once(INCLUDE_PATH.'utils/links.php');
require_once(INCLUDE_PATH.'utils/outilAgence.php');
require_once(INCLUDE_PATH.'utils/outilNegociateur.php');
require_once(INCLUDE_PATH.'utils/outilAnnonce.php');
require_once(INCLUDE_PATH.'utils/outilPhoto.php');
require_once(INCLUDE_PATH.'utils/modules.php');
require_once(INCLUDE_PATH.'utils/graph.php'); 

Links::initFromTextFile(CONFIG_PATH.'links_definer.txt');

// PAGER :
require_once(INCLUDE_PATH.DIRECTORY_SEPARATOR.'pager'.DIRECTORY_SEPARATOR.'pager.php');

XTremplate::setPHPCacheMode_Global(XTremplate::CACHE_ALWAYS);
//XTremplate::setHTMLCacheMode_Global(XTremplate::CACHE_ALWAYS);
XTremplate::setCachePath(CACHE_PATH);

?>