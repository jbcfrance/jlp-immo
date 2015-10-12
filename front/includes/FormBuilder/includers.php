<?php
require_once('class_form.php');

require_once('inputs/class_changeevent.php');
require_once('inputs/class_callfunc.php');

require_once('inputs/class_input.php');
require_once('inputs/class_input_text.php');
require_once('inputs/class_input_button.php');

require_once('inputs/class_input_select.php');
require_once('inputs/class_input_option.php');
require_once('inputs/class_input_optgroup.php');

require_once('inputs/class_input_checkbox.php');
require_once('inputs/class_input_file.php');
require_once('inputs/class_input_hidden.php');
require_once('inputs/class_input_image.php');
require_once('inputs/class_input_password.php');
require_once('inputs/class_input_reset.php');
require_once('inputs/class_input_submit.php');
require_once('inputs/class_input_textarea.php');
require_once('inputs/class_input_radio.php');

// Composed :
require_once('inputs/class_input_calendar.php');
require_once('inputs/class_input_multi_selector.php');
require_once('inputs/class_input_color_picker.php');
require_once('inputs/class_input_filecheckbox.php');
require_once('inputs/class_input_filethumb.php');
require_once('inputs/class_input_filethumbcheckbox.php');
require_once('inputs/class_input_validAndreset.php');

// Messages :
require_once('inputs/message.php');
require_once('inputs/error.php');



if ( isset($_POST['formRecall']) ) {
	if ( session_id() == '' ) {
		session_start();
	}
	foreach ( $_SESSION['FORMS'][ $_POST['form'] ][ $_POST['input'] ]['ONCHANGE'] as $oEvent ) {
		$oEvent->call();
	}
	exit;
}
?>
