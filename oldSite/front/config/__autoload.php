<?php
$GLOBALS['AUTO_LOAD'] = array();
function __autoload( $sClass ) {
	/*echo"<pre>";print_r($sClass);echo"</pre>";*/
	$GLOBALS['AUTO_LOAD'] = array_change_key_case($GLOBALS['AUTO_LOAD'], CASE_LOWER);
	require_once($GLOBALS['AUTO_LOAD'][strtolower($sClass)]);
}

// FORM :
define('INPUTS_CLASSES_PATH', FORM_PATH.'inputs'.DIRECTORY_SEPARATOR);
$GLOBALS['AUTO_LOAD']['form']			= FORM_PATH.'class_form.php';
$GLOBALS['AUTO_LOAD']['changeevent']	= INPUTS_CLASSES_PATH.'class_changeevent.php';
$GLOBALS['AUTO_LOAD']['callfunc']		= INPUTS_CLASSES_PATH.'class_callfunc.php';
$GLOBALS['AUTO_LOAD']['input']			= INPUTS_CLASSES_PATH.'class_input.php';
$GLOBALS['AUTO_LOAD']['text']			= INPUTS_CLASSES_PATH.'class_input_text.php';
$GLOBALS['AUTO_LOAD']['button']			= INPUTS_CLASSES_PATH.'class_input_button.php';
$GLOBALS['AUTO_LOAD']['select']			= INPUTS_CLASSES_PATH.'class_input_select.php';
$GLOBALS['AUTO_LOAD']['option']			= INPUTS_CLASSES_PATH.'class_input_option.php';
$GLOBALS['AUTO_LOAD']['optgroup']		= INPUTS_CLASSES_PATH.'class_input_optgroup.php';
$GLOBALS['AUTO_LOAD']['checkbox']		= INPUTS_CLASSES_PATH.'class_input_checkbox.php';
$GLOBALS['AUTO_LOAD']['editimage']				= INPUTS_CLASSES_PATH.'class_input_editimage.php';
$GLOBALS['AUTO_LOAD']['file']					= INPUTS_CLASSES_PATH.'class_input_file.php';
$GLOBALS['AUTO_LOAD']['filecheckbox']			= INPUTS_CLASSES_PATH.'class_input_filecheckbox.php';
$GLOBALS['AUTO_LOAD']['filethumbcheckbox']		= INPUTS_CLASSES_PATH.'class_input_filethumbcheckbox.php';
$GLOBALS['AUTO_LOAD']['filethumb']				= INPUTS_CLASSES_PATH.'class_input_filethumb.php';
$GLOBALS['AUTO_LOAD']['hidden']			= INPUTS_CLASSES_PATH.'class_input_hidden.php';
$GLOBALS['AUTO_LOAD']['image']			= INPUTS_CLASSES_PATH.'class_input_image.php';
$GLOBALS['AUTO_LOAD']['password']		= INPUTS_CLASSES_PATH.'class_input_password.php';
$GLOBALS['AUTO_LOAD']['reset']			= INPUTS_CLASSES_PATH.'class_input_reset.php';
$GLOBALS['AUTO_LOAD']['submit']			= INPUTS_CLASSES_PATH.'class_input_submit.php';
$GLOBALS['AUTO_LOAD']['textarea']		= INPUTS_CLASSES_PATH.'class_input_textarea.php';
$GLOBALS['AUTO_LOAD']['radio']			= INPUTS_CLASSES_PATH.'class_input_radio.php';
$GLOBALS['AUTO_LOAD']['calendar']		= INPUTS_CLASSES_PATH.'class_input_calendar.php';
$GLOBALS['AUTO_LOAD']['multiselect']	= INPUTS_CLASSES_PATH.'class_input_multi_selector.php';
$GLOBALS['AUTO_LOAD']['message']		= INPUTS_CLASSES_PATH.'message.php';
$GLOBALS['AUTO_LOAD']['error']			= INPUTS_CLASSES_PATH.'error.php';
$GLOBALS['AUTO_LOAD']['colorpicker']	= INPUTS_CLASSES_PATH.'class_input_color_picker.php';
$GLOBALS['AUTO_LOAD']['validAndReset']	= INPUTS_CLASSES_PATH.'class_input_validAndreset.php';
$GLOBALS['AUTO_LOAD']['captcha']	= INPUTS_CLASSES_PATH.'class_input_captcha.php';

// Sepcial Forms
$GLOBALS['AUTO_LOAD']['PersonalForm']	= FORM_PATH.'formValidators.php';
// ACTIONS :
$GLOBALS['AUTO_LOAD']['action']			= INCLUDE_PATH.'/actions/actions.class.php';

?>