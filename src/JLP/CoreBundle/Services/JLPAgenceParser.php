<?php

// src/JLP/CoreBundle/Services/JLPAgenceParser.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Services\JLPParser;
use JLP\CoreBundle\Entity\Agence;

/**
 * Description of JLPAgenceParser
 *
 * @author jciepka
 */
class JLPAgenceParser extends JLPParser {
  
  protected $aAgenceInfo = array();
  protected $oAgenceEntity = null;
  protected $aEntitiesFields = array();
  protected $aYmlAgenceMapping = array();
  
  public function execute($sXMLFileName){
    
    $this->oAgenceEntity = new Agence();
    $aClassProperties = $this->oEm->getClassMetadata('JLPCoreBundle:Agence')->getFieldNames();
    $this->aEntitiesFields = array_merge(
                  $aClassProperties, 
                  $this->oEm->getClassMetadata('JLPCoreBundle:Agence')->getAssociationNames()
    );
    $this->aYmlAgenceMapping = $this->oYmlMapping['passerelle']['parser']['mapping_agence'];
    
    unset($aClassProperties);
    
    $this->parsingXml($sXMLFileName);
    
  }
  
  protected function extractInformation($elementAnnonce){
    $this->oLogger->info('Demarrage du Parser Agence');
    
    $tmp = $elementAnnonce;
    if(strstr($elementAnnonce->getName(),"A") == "Agence") {
      $this->aAgenceInfo = array_merge($this->aAgenceInfo,array($elementAnnonce->getName()=>(string)$tmp));
    }
  }
  
  public function getRawAgenceInfo(){
    return $this->aAgenceInfo;
  }
  
  public function buildEntity(){
    $oAgence = $this->oEm->getRepository('JLPCoreBundle:Agence')->findOneBy(array("agenceId"=>$this->aAgenceInfo[$this->aYmlAgenceMapping["agenceId"]]));
    
    if(!empty($oAgence)){
      $this->oAgenceEntity = $oAgence;
    }
    //$this->oAgenceEntity->findBy(array("agenceId"=>$this->aAgenceInfo[$this->aYmlAgenceMapping["agenceId"]]));
    
    foreach($this->aEntitiesFields as $sEntityFieldName){
      if($sEntityFieldName != 'id')
      {
        $sFunc = 'set'.ucfirst($sEntityFieldName);
        $this->oAgenceEntity->{$sFunc}($this->aAgenceInfo[$this->aYmlAgenceMapping[$sEntityFieldName]]);
      }
      $this->oEm->persist($this->oAgenceEntity);
    }
    $this->oEm->flush();
  }
  
  public function getName(){
    return 'jlp_core.parser.agence';
  }
  
}
