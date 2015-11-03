<?php

// src/JLP/CoreBundle/Services/JLPAgenceParser.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Services\JLPParser;

/**
 * Description of JLPAgenceParser
 *
 * @author jciepka
 */
class JLPAgenceParser extends JLPParser {
  
  protected $aAgenceInfo = array();
  
  public function execute($sXMLFileName){
    
    $this->parsingXml($sXMLFileName);
    
  }
  
  protected function extractInformation($elementAnnonce){
    $tmp = $elementAnnonce;
    if(strstr($elementAnnonce->getName(),"A") == self::Agence) {
      $this->aAgenceInfo = array_merge($this->aAgenceInfo,array($elementAnnonce->getName()=>(string)$tmp));
    }
  }
  
  public function getRawAgenceInfo(){
    return $this->aAgenceInfo;
  }
  
}
