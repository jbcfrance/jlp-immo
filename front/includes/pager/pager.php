<?php
// Verification pour savoir si un pager existe :
$aVerifPager = Links::getActualParams();
foreach ( $aVerifPager as $sParam => $sValue ) {
	if ( $sValue === 'pager' ) {
		// Creation d'un nouveau type de lien correspondant au pager :
		
		if ( $sParam !== 0 ) {
			// On a choppe des parametres qu'on aurait pas du et ca fout la merdasse :
			// Du coup, faut creer un lien comme il faut :
			
			// On recupere le nombres de params qu'on doit rajoutter avant les params du pager et on compose l'array des params :
			$aNewParams = array();
			foreach ( $aVerifPager as $sParam2 => $sValue2 ) {
				if ( $sValue2 === 'pager' ) {
					break;
				}
				$aNewParams[] = $sParam2;
			}
			$aNewParams[] = 'pager';
			$aNewParams[] = 'page';
			$aNewParams[] = 'field';
			$aNewParams[] = 'sense';
			
			if ( Links::DEFAULT_ACTION===Links::getActualAction() ) {
				if ( Links::DEFAULT_METHOD===Links::getActualMethod() ) {
					$sTemplate = HTML_ROOT_PATH . str_repeat('/%s', count($aNewParams) );
					Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
				} else {
					$sTemplate = HTML_ROOT_PATH . Links::getActualMethod().str_repeat('/%s', count($aNewParams) );
					Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
				}
			} else if ( Links::DEFAULT_METHOD===Links::getActualMethod() ) {
				$sTemplate = HTML_ROOT_PATH . Links::getActualAction().str_repeat('/%s', count($aNewParams) );
				Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
			}
			
			$sTemplate = HTML_ROOT_PATH . Links::getActualAction().'/'.Links::getActualMethod().str_repeat('/%s', count($aNewParams));
			Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
			Links::reInit();
			break;
		} else if ( $sParam === 0 ) {
			$aNewParams = array();
			foreach ( $aVerifPager as $sParam2 => $sValue2 ) {
				if ( $sValue2 === 'pager' ) {
					break;
				}
				$aNewParams[] = $sParam2;
			}
			$aNewParams[] = 'pager';
			$aNewParams[] = 'page';
			$aNewParams[] = 'field';
			$aNewParams[] = 'sense';
			
			if ( Links::DEFAULT_ACTION===Links::getActualAction() ) {
				if ( Links::DEFAULT_METHOD===Links::getActualMethod() ) {
					$sTemplate = HTML_ROOT_PATH . str_repeat('/%s', count($aNewParams) );
					Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
				} else {
					$sTemplate = HTML_ROOT_PATH . Links::getActualMethod().str_repeat('/%s', count($aNewParams) );
					Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
				}
			} else if ( Links::DEFAULT_METHOD===Links::getActualMethod() ) {
				$sTemplate = HTML_ROOT_PATH . Links::getActualAction().str_repeat('/%s', count($aNewParams) );
				Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
			}
			
			$sTemplate = HTML_ROOT_PATH . Links::getActualAction().'/'.Links::getActualMethod().str_repeat('/%s', count($aNewParams) );
			Links::createShortLink($sTemplate, Links::getActualAction(), Links::getActualMethod(), $aNewParams);
			Links::reInit();
			break;
		}
	}
}

class Pager extends XTremplate {
	const AUTO_MARGINS = 5;
	
	private	$oQuery,			// Requete
			$iPage,				// numero de la page
			$iMax,				// Nombre d'element par page
			$bIsInit,			// true si le pager est initialise, sinon false
			$iCount,			// Nombre d'element dans la page
			$iTotalCount,		// Nombre total d'element
			$iActualElement,	// ID de l'eement actuel
			$aTitles,			// Titres des colonnes (avec en cle les nom dans la DB)
			$sDefaultOrder,		// Ordre des colonnes par defaut
			$iMargin=null		// Nombres de pages affichées dans le footer du pager
			;
	
	public function __construct( $oRequest=null, $iPage=null, $iMax=null ){
		parent::__construct( dirname(__FILE__).DIRECTORY_SEPARATOR.'pager_temp.htm' );
		if ( $oRequest!==null ) {
			$this->setQuery( $oRequest );
		}
		if ( $iPage!==null ) {
			$this->setPage( $iPage );
		}
		if ( $iMax!==null ) {
			$this->setMax( $iMax );
		}
		
		
	}
	
	public function setQuery($oRequest){
		$this->oQuery = $oRequest;
	}
	
	public function getQuery(){
		return $this->oQuery;
	}
	
	public function setPage($iPage){
		$this->iPage = $iPage;
	}
	
	public function getPage(){
		return $this->iPage;
	}
	
	public function setMax($iMax){
		$this->iMax = $iMax;
	}
	
	public function getMax(){
		return $this->iMax;
	}
	
	public function setTitles($aTitles){
		$this->aTitles = $aTitles;
	}
	
	public function getTitles(){
		return $this->aTitles;
	}
	
	public function setDefaultOrder($sDefaultOrder){
		$this->sDefaultOrder = $sDefaultOrder;
	}
	
	public function getDefaultOrder(){
		return $this->sDefaultOrder;
	}
	
	public function setCount($iCount){
		$this->iCount = $iCount;
	}
	
	public function getCount(){
		return $this->iCount;
	}
	
	public function setTotalCount($iTotalCount){
		$this->iTotalCount = $iTotalCount;
	}
	
	public function getTotalCount(){
		return $this->iTotalCount;
	}
	
	public function setValues($aValues){
		$this->aValues = $aValues;
	}
	
	public function getValues(){
		return $this->aValues;
	}
	
	public function getValue($iPos, $sField=null){
		if ( $sField===null ) {
			return isset($this->aValues[$iPos])?$this->aValues[$iPos]:null;
		} else {
			return isset($this->aValues[$iPos])? call_user_func(array( $this->aValues[$iPos], 'get'.$sField)) :null;
		}
	}
	
	public function setMargins($iMargin){
		$this->iMargin = $iMargin;
	}
	
	public function getMargins(){
		return ($this->iMargin===null?self::AUTO_MARGINS:$this->iMargin);
	}
	
	
	public function getSense($sField){
		$aVerifPager = Links::getActualParams();
		if ( isset($aVerifPager['pager']) && $aVerifPager['pager'] === 'pager' ) {
			if($aVerifPager['field'] == $sField) {
				if($aVerifPager['sense'] == 'DESC') {
					return 'DESC';
				} else {
					return 'ASC';
				}
			}
		}
		return 'ASC';
	}
	
	public function getNewSense($sField){
		if($sField == $this->getActualOrder() ){
			return $this->getSense($sField)==='ASC'?'DESC':'ASC';
		} else {
			return $this->getSense($sField);
		}
	}
	
	public function getActualOrder(){
		$aVerifPager = Links::getActualParams();
		if ( isset($aVerifPager['pager']) && $aVerifPager['pager'] === 'pager' ) {
			return $aVerifPager['field'];
		} else {
			return null;
		}
	}
	
	public function init(){
		if ( ! $this->bIsInit ) {
			$oQuery = $this->getQuery();
			
			if ($this->getDefaultOrder() != null) {
				$oQuery->setOrder( $this->getDefaultOrder().' ASC' );
			}
			
			$aVerifPager = Links::getActualParams();
			if ( isset($aVerifPager['pager']) ){
				if ( isset($aVerifPager['page']) ){
					$this->setPage( $aVerifPager['page'] );
				} else {
					$this->setPage( 1 );
				}
				
				if ( isset($aVerifPager['field'], $aVerifPager['sense']) && $aVerifPager['field']!='' && $aVerifPager['sense']!=''){
					$oQuery->setOrder( $aVerifPager['field'].' '.$aVerifPager['sense'] );
				} else if (isset($aVerifPager['field']) && $aVerifPager['field']!='') {
					$oQuery->setOrder( $aVerifPager['field'].' ASC' );
				} else if ($this->getDefaultOrder() != null) {
					if (isset($aVerifPager['sense']) && $aVerifPager['sense']!='') {
						$oQuery->setOrder( $this->getDefaultOrder().' '.$aVerifPager['sense'] );
					} else {
						$oQuery->setOrder( $this->getDefaultOrder().' ASC' );
					}
				}
			}
			
			$oQueryCount = clone $oQuery;
			$oQueryCount->setSelectedFields(array( new FIELD_COUNT() ));
			$aResults = $oQueryCount->getOne( SQLComposer::ARRAY_MODE );
			$this->setTotalCount( $aResults[0] );
			
			$oQueryValues = clone $oQuery;
			$oQueryValues->setLimit( $this->getMax()*($this->getPage()-1), $this->getMax() );
			
			$aResults = $oQueryValues->exec();
			$this->setValues( $aResults );
			
			if ( count($aResults) != $this->getMax() ) {
				$this->setCount( count($aResults) );
			} else {
				$this->setCount( $this->getMax() );
			}
			
			$this->iActualElement = -1;
			$this->bIsInit = true;
		}
	}
	
	
	public function firstPage(){
		$this->init();
		return 1;
	}
	
	public function lastPage(){
		$this->init();
		return ceil( $this->getTotalCount() / $this->getMax() );
	}
	
	public function previousPage(){
		$this->init();
		if ( $this->getPage() > 1 ) {
			return $this->getPage() - 1;
		} else {
			return 1;
		}
	}
	
	public function nextPage(){
		$this->init();
		if ( $this->getPage() < ceil( $this->getTotalCount() / $this->getMax() ) ) {
			return $this->getPage() + 1;
		} else {
			return $this->lastPage();
		}
	}
	
	
	public function next(){
		$this->init();
		$this->iActualElement++;
		return $this->getValue( $this->iActualElement );
	}
	
	public function previous(){
		$this->init();
		$this->iActualElement--;
		return $this->getValue( $this->iActualElement );
	}
	
	public function first(){
		$this->init();
		$this->iActualElement = 0;
		return $this->getValue( $this->iActualElement );
	}
	
	public function last(){
		$this->init();
		$this->iActualElement = $this->getMax() - 1;
		return $this->getValue( $this->iActualElement );
	}
	
	
	public function needToPaginate(){
		return $this->getTotalCount() > $this->getMax();
	}
	
	public function __toString(){
		// Conservation de donnees importantes en session pour la pagination :
		$_SESSION['pager'] = array(
			'ACTION'	=>	Links::getActualAction(),
			'METHOD'	=>	Links::getActualMethod(),
			'PARAMS'	=>	Links::getActualParams(),
		);
		
		return $this->display(true);
	}
	
	public function PagerLink($sContainer='', $sPage, $sField, $sSense){
		$aParams = Links::getActualParams();
		$aParams['pager']	= 'pager';
		$aParams['page']	= $sPage;
		$aParams['field']	= $sField;
		$aParams['sense']	= $sSense;
		
		return linkTo(	$sContainer,
						Links::getActualAction(),
						Links::getActualMethod(),
						$aParams);
	}
}
?>