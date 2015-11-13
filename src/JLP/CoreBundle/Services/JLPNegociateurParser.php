<?php

// src/JLP/CoreBundle/Services/JLPNegociateurParser.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Services\JLPParser;
use JLP\CoreBundle\Entity\Negociateur;

/**
 * Description of JLPNegociateurParser
 *
 * @author jciepka
 */
class JLPNegociateurParser extends JLPParser {
  
  protected $aNegociateurInfo = array();
  protected $oNegociateurEntity = null;
  protected $aEntitiesFields = array();
  protected $aYmlNegociateurMapping = array();
  
  public function execute($sXMLFileName){
    
    $this->oNegociateurEntity = new Negociateur();
    $aClassProperties = $this->oEm->getClassMetadata('JLPCoreBundle:Negociateur')->getFieldNames();
    $this->aEntitiesFields = array_merge(
                  $aClassProperties, 
                  $this->oEm->getClassMetadata('JLPCoreBundle:Negociateur')->getAssociationNames()
    );
    $this->aYmlNegociateurMapping = $this->oYmlMapping['passerelle']['parser']['mapping_negociateur'];
    
    unset($aClassProperties);
    
    $this->parsingXml($sXMLFileName);
    
  }
  
  protected function extractInformation($elementAnnonce){
    $this->oLogger->info('Demarrage du Parser Negociateur');
    
    $tmp = $elementAnnonce;
    if(strstr($elementAnnonce->getName(),"N") == "Negociateur") {
      $this->aNegociateurInfo = array_merge($this->aNegociateurInfo,array($elementAnnonce->getName()=>(string)$tmp));
    }
  }
  
  public function getRawNegociateurInfo(){
    return $this->aNegociateurInfo;
  }
  
  public function buildEntity(){
    
    $oNegociateur = $this->oEm->getRepository('JLPCoreBundle:Negociateur')->findOneBy(array("negociateurId"=>$this->aNegociateurInfo[$this->aYmlNegociateurMapping["negociateurId"]]));
    
    if(!empty($oNegociateur)){
      $this->oNegociateurEntity = $oNegociateur;
    }
    
    parent::buildEntity();
  }
  
  public function getName(){
    return 'jlp_core.parser.negociateur';
  }
  
}
