<?php
class callFunc extends ChangeEvent {
	private $sFuncToCall = null;
	public function __construct ($sFunc, $aParams=array()) {
		$this->sFuncToCall = $sFunc;
		parent::__construct ('inputCallFuncEvent', $aParams);
	}
	public function call () {
		header('Content-Type: text/xml');
		call_user_func($this->sFuncToCall, $this->getParams() );
	}
	
	public function getJSFuncToCall () {
	}
}
?>