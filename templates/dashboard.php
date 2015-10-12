<?php
class dashboardPage extends Page {
	
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
				$this->{'dashboard'}();
			}
		}else{
			$this->{'accueil'}();
		}
		
		
		
	}
	
	public function accueil(){
		$this->setLayout('loginLayout.htm');
		$this->setTpl($this->sRequestPage.'/accueil.htm');	
		$this->setSubTitle('Login');	
	}
	
	public function dashboard(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/dashboard.htm');	
		$this->setSubTitle('Dashboard');
		$oSite = SITE::SELECT()->exec();
		foreach($oSite->getRecords() as $kSite=>$vSite){
			$aSite[$vSite->getSite_variable()] = $vSite->getSite_valeur();
		}
		$this->aVars[$this->sRequestPage]['aSite'] = $aSite;
		
		$oPasserelle = PASSERELLE::SELECT()->ORDERBY('passerelle_date DESC')->getOne();
		$this->aVars[$this->sRequestPage]['oPasserelle'] = $oPasserelle;
		
		$oStatAnnonce = ANNONCE::SELECT('count(annonce_id)')->getOne();
		$this->aVars[$this->sRequestPage]['iNbAnnonce'] = $oStatAnnonce->getcount_annonce_id();
		
		$oStatCdc = ANNONCE::SELECT('count(annonce_id)')->WHERE('coupDeCoeur','oui')->getOne();
		$this->aVars[$this->sRequestPage]['iNbCdc'] = $oStatCdc->getcount_annonce_id();
		
		$oStatProg = PROGRAMME::SELECT('count(programme_id)')->getOne();
		$this->aVars[$this->sRequestPage]['iNbProg'] = $oStatProg->getcount_programme_id();
		require_once 'lib/gapi.class.php';
		$ga = new gapi('9x64ever@gmail.com','C0gn!t00GOOGLE');
		$iToday = time();
		$sStartDate = strtotime('1 month ago',$iToday);
		$ga->requestReportData(24554546,array('day','month','year'),array('visits'),array('year','month','day'),null,date('Y-m-d',$sStartDate),date('Y-m-d'));
		$aStats = array();
		foreach($ga->getResults() as $result)
		{
			$aStats[] = array(
				'date' => $result->getDay().'-'.$result->getMonth().'-'.$result->getYear(),
				'visits' => $result->getVisits()
			);
		}
		$this->aVars[$this->sRequestPage]['aStats'] = $aStats;
	}	
	
	
	public function trafic(){
		$this->setLayout('adminLayout.htm');
		$this->setTpl($this->sRequestPage.'/trafic.htm');	
		$this->setSubTitle('Statistiques');
		require_once 'lib/gapi.class.php';
		$ga = new gapi('alex.perroud@jlp-immo.com','c0gn!t00');
		$iToday = time();
		$sStartDate = strtotime('1 month ago',$iToday);
		$ga->requestReportData(24554546,array('day','month','year'),array('visits'),array('year','month','day'),null,date('Y-m-d',$sStartDate),date('Y-m-d'));
		$aStats = array(
			'cols' => array(
					array('id'=>'date','label'=>'Date','type'=>'string'),
					array('id'=>'visits','label'=>'Visites','type'=>'string')
				),
			'rows' => array()
		);
		foreach($ga->getResults() as $result)
		{
			$aStats['rows'][] = array(
				'c'=>array(
					array('v'=>$result->getDay().'-'.$result->getMonth().'-'.$result->getYear()),
					array('v'=>$result->getVisits())
				)
			);
		}
		echo json_encode($aStats);die();
		//echo '<p>Total pageviews: ' . $ga->getVisitLength() . ' total visits: ' . $ga->getVisitCount() . '</p>';
			}
		
}
?>