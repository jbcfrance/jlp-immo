<?php
	/**
	 * Auteur			: Jean-Baptiste CIEPKA
	 * Date				: Decembre 2009
	 *
	 * Name				: Fonctions de l'index
	 * Description		: Cette class effectue les traitements php permettant l'intégration des donnés aux templates
	 * @templates dir 	: templates/layout.htm
	 *
	*/
if(!isset($_SESSION["lang"])) {
	$_SESSION["lang"]="fr";
}
if(isset($_GET['lang'])){
	$_SESSION["lang"] = $_GET['lang'];
}
Xtremplate::$vars['tImg'] = time();
/*DETECTION MOBILE*/

$isiPad = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPad');
$isiPhone = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPhone');
$isAndroid = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'Android');
$isiPod = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'iPod');
$isBlackBerry = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'BlackBerry');
$iswebOS = (bool) strpos($_SERVER['HTTP_USER_AGENT'],'webOS');
$sStyleAlt = "layout_pc.css";
if($isiPad){
	$sStyleAlt = "mobile/layout_iPad.css"; 
}
if($isiPhone){
	$sStyleAlt = "mobile/layout_iPhone.css"; 
}
if($isAndroid){
	$sStyleAlt = "mobile/layout_Android.css"; 
}
if($isiPod){
	$sStyleAlt = "mobile/layout_iPod.css"; 
}
if($isBlackBerry){
	$sStyleAlt = "mobile/layout_BlackBerry.css"; 
}
if($iswebOS){
	$sStyleAlt = "mobile/layout_webOS.css"; 
}
Xtremplate::$vars['sStyleAlt'] = $sStyleAlt; 

if (stripos($_SERVER['HTTP_USER_AGENT'],'msie')) 
{ 
	Xtremplate::$vars['sIe'] = 'IE';
	if (stripos($_SERVER['HTTP_USER_AGENT'],'msie 7')) 
	{ 
		Xtremplate::$vars['sIeV'] = 7; 
	} 
	elseif (intval(substr($_SERVER['HTTP_USER_AGENT'], stripos($_SERVER['HTTP_USER_AGENT'], "msie")+5)) <= 6) {	
		Xtremplate::$vars['sIeV'] = 6; 
	} elseif(strstr($_SERVER["HTTP_USER_AGENT"], "MSIE 9.")) {
		Xtremplate::$vars['sIeV'] = 9;
	}else{ 
		Xtremplate::$vars['sIeV'] = 8; 
	} 
	
}else{
	Xtremplate::$vars['sIe'] = 'notIE'; 	
}



$oDescFR = SITE::SELECT()->WHERE('site_variable','site_desc')->exec();
$oCleFR = SITE::SELECT()->WHERE('site_variable','site_cle')->exec();
foreach($oDescFR as $vDesc) {
	Xtremplate::$vars['Site_desc'] = stripslashes($vDesc->getsite_valeur());
}
foreach($oCleFR as $vCle) {
	Xtremplate::$vars['Site_cle'] = stripslashes($vCle->getsite_valeur());
}

switch(Links::getActualAction()){
	case 'accueil':
		Xtremplate::$vars['menu_accueil_active'] = 'active';
		Xtremplate::$vars['menu_acheter_active'] = '';
		Xtremplate::$vars['menu_programmeneuf_active'] = '';
		Xtremplate::$vars['menu_contact_active'] = '';
	break;
	case 'acheter':
		Xtremplate::$vars['menu_accueil_active'] = '';
		Xtremplate::$vars['menu_acheter_active'] = 'active';
		Xtremplate::$vars['menu_programmeneuf_active'] = '';
		Xtremplate::$vars['menu_contact_active'] = '';
	break;
	case 'programmeneuf':
		Xtremplate::$vars['menu_accueil_active'] = '';
		Xtremplate::$vars['menu_acheter_active'] = '';
		Xtremplate::$vars['menu_programmeneuf_active'] = 'active';
		Xtremplate::$vars['menu_contact_active'] = '';
	break;
	case 'annonce':
		Xtremplate::$vars['menu_accueil_active'] = '';
		Xtremplate::$vars['menu_acheter_active'] = 'active';
		Xtremplate::$vars['menu_programmeneuf_active'] = '';
		Xtremplate::$vars['menu_contact_active'] = '';
	break;
	case 'contact':
		Xtremplate::$vars['menu_accueil_active'] = '';
		Xtremplate::$vars['menu_acheter_active'] = '';
		Xtremplate::$vars['menu_programmeneuf_active'] = '';
		Xtremplate::$vars['menu_contact_active'] = 'active';
	break;
	default:
		Xtremplate::$vars['menu_accueil_active'] = 'active';
		Xtremplate::$vars['menu_acheter_active'] = '';
		Xtremplate::$vars['menu_programmeneuf_active'] = '';
		Xtremplate::$vars['menu_contact_active'] = '';
}

	
	
// Traitement pour le slider

$sXmlPath = 'web/flash/frSlider.xml';
$oXml = simplexml_load_file($sXmlPath) or die('XML ERROR');
$aSlider = array();
$aBloc = array();
foreach($oXml->slide as $k=>$v){
	$aSlider[] = array(
		"image"=>(string)$v->attributes()->image,
		"type"=>(string)$v->attributes()->type,
		"lieu"=>(string)$v->attributes()->lieu,
		"ref"=>(string)$v->attributes()->ref,
		"prix"=>(string)$v->attributes()->prix,
		"url"=>(string)$v->attributes()->url,
		"content"=>(string)$v->attributes()->content,
		"dpe"=>(string)$v->attributes()->dpe,
		"ges"=>(string)$v->attributes()->ges
	);	
}


//echo"<pre>";print_r($aSlider);echo"</pre>";die();
Xtremplate::$vars['Slider'] = $aSlider;

?>