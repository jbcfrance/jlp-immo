<?php
class sitePage extends Page {
	
	public function traitementPage() {
		if(isset($_POST) and !empty($_POST)){
			$aPost = array();
			foreach($_POST as $kPost=>$vPost){
				$aPost[$kPost] = $vPost;	
			}
		}
		
		$this->aVars[$this->aVars['sAction']] = array();
		
		if(isset($_SESSION['isLogged']) && $_SESSION['isLogged'] == 1){
			if(method_exists(&$this, $this->aVars['sAction'])){
				$this->{$this->aVars['sAction']}(); 
			}else{
				$this->{'site'}();
			}
		}else{
			$this->redirect('dashboard','accueil');
		}
		
		
		
	}

	public function site($sMessage = '',$sStatus = null,$iBloc = 1){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/site.htm');	
		$this->setSubTitle('Configuration du site');
		
		$oSite = SITE::SELECT()->exec();
		foreach($oSite->getRecords() as $kSite=>$vSite){
			$aSite[$vSite->getSite_variable()] = $vSite->getSite_valeur();
		}
		$this->aVars[$this->sRequestPage]['aSite'] = $aSite;
		if(!empty($sMessage) && isset($sStatus)){
			$this->aVars[$this->sRequestPage][$iBloc]['sMessage'] = $sMessage;
			$this->aVars[$this->sRequestPage][$iBloc]['sStatus'] = $sStatus;
		}
	}
	
	public function edit_site(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/edit_site.htm');	
		if(isset($_POST['site_desc'])){
			$oSite = new SiteRecord();
			$oSite->setSite_variable('site_desc');
			$oSite->setSite_valeur($_POST['site_desc']);
			if($oSite->update()) $sRes = true; else $sRes = false;
		}else{
			$sRes = false;
		}
		if(isset($_POST['site_cle'])){
			$oSite = new SiteRecord();
			$oSite->setSite_variable('site_cle');
			$oSite->setSite_valeur($_POST['site_cle']);
			if($oSite->update()) $sRes = true; else $sRes = false;
		}else{
			$sRes = false;
		}
		if($sRes){
			$this->site('Modification enregistrées','success',1);	
		}else{
			$this->site('Erreur lors de la modification','error',1);
		}
	}
	
	public function edit_actu(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/edit_actu.htm');	
		if(isset($_POST['actu_texte_fr'])){
			$oSite = new SiteRecord();
			$oSite->setSite_variable('actu_texte_fr');
			$oSite->setSite_valeur($_POST['actu_texte_fr']);
			if($oSite->update()) $sRes = true; else $sRes = false;
		}else{
			$sRes = false;
		}
		if(isset($_POST['actu_texte_en'])){
			$oSite = new SiteRecord();
			$oSite->setSite_variable('actu_texte_en');
			$oSite->setSite_valeur($_POST['actu_texte_en']);
			if($oSite->update()) $sRes = true; else $sRes = false;
		}else{
			$sRes = false;
		}
		if($sRes){
			$this->site('Modification enregistrées','success',2);	
		}else{
			$this->site('Erreur lors de la modification','error',2);
		}
	}
			
}
?>