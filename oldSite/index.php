<?php
session_start();
//error_reporting(E_ALL & E_NOTICE & E_STRICT);
define('HTML_ROOT_PATH', 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')+1));
define('CURRENT_PAGE', $_SERVER['REQUEST_URI']);
define('BASE_PATH',		realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR );
include('lib/sql_composer/includes.php');
include('page.php');
include('lib/utilFile.classe.php');
include('lib/imgFile.classe.php');
require_once('lib/outilAgence.php');
require_once('lib/outilNegociateur.php');
require_once('lib/outilAnnonce.php');
require_once('lib/outilPhoto.php');
require_once('front/includes/utils/trad.php');


if ( ! isset($_SERVER['REDIRECT_URL']) ) {
	// On est passe directement par l'index.php
	$sRequestURI = substr($_SERVER['PATH_INFO'], 1);
} else {
	// On a mis un lien "complexe" :
	$sRequestURI = substr(urldecode($_SERVER['REQUEST_URI']),strrpos($_SERVER['SCRIPT_NAME'],'/')+1);
}

// Effacer les parametres en GET :
if ( strchr($sRequestURI, '?') ) {
	$sRequestURI = substr($sRequestURI, 0, strpos($sRequestURI, '?') );
}
// explode de l'URL :
$aRequestURI = explode('/', $sRequestURI);
while ( count($aRequestURI)>0 && end($aRequestURI) == '' ) {
			array_pop($aRequestURI);
		}
$aGet = array();
if(isset($aRequestURI[0]) && !empty($aRequestURI[0])){
	$sRequestPage = $aRequestURI[0];
	array_shift($aRequestURI);
} else {
	$sRequestPage = 'dashboard';
}

if(isset($aRequestURI[0]) && !empty($aRequestURI[0])){
	$aGet['sAction'] = $aRequestURI[0];
	array_shift($aRequestURI);
}else{
	$aGet['sAction'] = 'dashboard';
}
$aGet['aParams'] = $aRequestURI;

/*$aGet['action'] = $aRequestURI[1];

if(isset($_GET)){	
	if(isset($_GET['page'])){
		$sRequestPage = $_GET['page'];	
	}
	$aGet = array();
	foreach($_GET as $kGet=>$vGet){
		if($kGet!='page'){
			$aTmpGet[$kGet] = $vGet;
			$aGet = array_merge($aTmpGet,$aGet);
		}
	}
}*/
include('templates/'.$sRequestPage.'.php');
$oTmp =$sRequestPage."Page";
$oPage = new $oTmp();
$oPage->definePage($sRequestPage,$aGet);
echo $oPage;

?>
