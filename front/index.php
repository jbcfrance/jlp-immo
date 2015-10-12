<?php
session_start();
/*header("Cache-Control: no-cache, no-store"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0"); 
header("Cache-Control: private",false);*/
error_reporting(E_ALL & E_NOTICE & E_STRICT);
define('SITE_DOMAIN_PATH','http://www.jlp-immo.com/');
define('HTML_ROOT_PATH', 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'],0,strrpos($_SERVER['SCRIPT_NAME'],'/')+1));
$sBase=substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'],'/index.php')+1);
if((strpos($_SERVER['REQUEST_URI'],$sBase.'dev.php')===0)&&(!defined('DEV_VUE_LOADED'))){
	define('DEV_VUE_LOADED', true);
	require_once('dev.php'); 
	exit;
}
if(!defined('DIRECTORY_SEPARATOR')){
	define('DIRECTORY_SEPARATOR', '\\');
}
require(realpath(dirname(__FILE__)).DIRECTORY_SEPARATOR.'config'.DIRECTORY_SEPARATOR.'includer.php');

if(		(file_exists(realpath($_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL'])))
	&&	(!in_array(realpath($_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL']),get_included_files()))
	&&	(is_file(realpath($_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL'])))
){
	include(realpath($_SERVER['DOCUMENT_ROOT'].$_SERVER['REDIRECT_URL']));
	exit;
}
$sPageClass=Links::getActualAction().'action';
$sMethod=Links::getActualMethod();
$oPage=new $sPageClass();
$oPage->start($sMethod,Links::getActualParams());
session_write_close();
?>