<?php
class Page {

	const 	DEFAULT_TEMPLATE_PATH 		=	'./templates/',
			DEFAULT_SITE_TITLE			=	'Administration JLP Immo',
			TPL_EXT						=	'.htm';
	
	public $sRequestPage = null,
			$sPageType = null,
			$oCurrentPage = null,
			$aVars = array(),
			$sTplURL = null,
			$oIdPage = null,
			$sFileToLoad = null,
			$sLayoutName = 'layout.htm';
	
	public function __construct(){
			
	}	
	public function definePage($sRequestPage,$aLinkVars){
		$this->sRequestPage = $sRequestPage;
		$this->setActivePage($sRequestPage);
		$this->sTplURL = $this->sRequestPage.self::TPL_EXT;
		$this->defineGetVars($aLinkVars);
		$this->setPageTitle();
		$this->traitementPage();
	}
	
	public function setLayout ($sLayout) {
		$this->sLayoutName = $sLayout;	
	}
	public function setHeadLayout ($sHeadLayout) {
		$this->sHeadLayoutName = $sHeadLayout;	
	}
	public function setSideLayout ($sSideLayout) {
		$this->sSideLayoutName = $sSideLayout;	
	}
	
	public function getPageTitle(){
		return $this->aVars['PageTitle'];
	}
	
	public function setPageTitle(){
		$this->aVars['PageTitle'] = self::DEFAULT_SITE_TITLE;
	}
	public function getActivePage(){
		return $this->aVars['sActivePage'];
	}
	
	public function setActivePage($activePage){
		if(empty($activePage))
		$this->aVars['sActivePage'] = 'dashboard';	
		else
		$this->aVars['sActivePage'] = $activePage;
	}
	public function defineGetVars($aLinkVars) {
			if(!empty($aLinkVars)){
				foreach ($aLinkVars as $k=>$v){
					$this->aVars[$k] = $v;
				}
			}
	}
	
	public function setTpl($sTpl) {
		$this->sTplURL = 	self::DEFAULT_TEMPLATE_PATH.$sTpl;
	}
	
	public function setSubTitle($sSubTitle){
		$this->aVars['SubTitle'] = $sSubTitle;
	}
		
	public function traitementPage() {
		$this->aVars[$this->sRequestPage] = array();
		$this->setTpl($this->sRequestPage.self::TPL_EXT);
	}
	
	public function redirect($sPage,$sAction){
		if(isset($sPage) && isset($sAction))
			header("Location:".HTML_ROOT_PATH.$sPage."/".$sAction);
		elseif(isset($sPage))
			header("Location:".HTML_ROOT_PATH.$sPage);
		else	
			header("Location:".HTML_ROOT_PATH);
	}
	
	public function display ()
	{
		
		$sTemplate = $this->sTplURL; 
		if ( file_exists($sTemplate) ) {
			ob_start();				
			include($sTemplate);
			$this->aVars['CONTENT'] =  ob_get_contents();
			ob_end_clean();
		}
		ob_start();
		include(self::DEFAULT_TEMPLATE_PATH.$this->sLayoutName);	
		$sTempReturn = ob_get_contents();
		ob_end_clean();
		return $sTempReturn;
	}
	
	public function __toString(){
		return $this->display();
	}	
	
	private function stripAccents($string){
		return strtr($string,'àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ', 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');
	}
}



?>