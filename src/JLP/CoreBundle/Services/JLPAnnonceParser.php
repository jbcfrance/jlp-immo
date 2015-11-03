<?php

// src/JLP/CoreBundle/Services/JLPAnnonceParser.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Services\JLPParser;

/**
 * Description of JLPAgenceParser
 *
 * @author jciepka
 */
class JLPAnnonceParser extends JLPParser {
  
  protected $aAnnonceInfo = array();
    
  public function execute(){
    
    $this->parsingXml($sXMLFileName);
  }
  
  protected function extractInformation($elementAnnonce){
    $tmp = $elementAnnonce;
    if((strstr($elementAnnonce->getName(),"A")!=self::Agence) && (strstr($elementAnnonce->getName(),"N")!=self::Negociateur)) {
      $this->aAnnonceInfo = array_merge($this->aAnnonceInfo,array($elementAnnonce->getName()=>(string)$tmp));
    }
  }
  
  public function getRawAnnonceInfo(){
    return $this->aAnnonceInfo;
  }
  
}
