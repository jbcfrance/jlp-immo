<?php

// src/JLP/CoreBundle/Services/JLPNegociateurParser.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Services\JLPParser;

/**
 * Description of JLPAgenceParser
 *
 * @author jciepka
 */
class JLPNegociateurParser extends JLPParser {
  
  protected $aNegociateurInfo = array();
    
  public function execute(){
    $this->parsingXml($sXMLFileName);
  }
  
  protected function extractInformation($elementAnnonce){
    $tmp = $elementAnnonce;
    if(strstr($elementAnnonce->getName(),"N") == self::Negociateur) {
      $this->aNegociateurInfo = array_merge($this->aNegociateurInfo,array($elementAnnonce->getName()=>(string)$tmp));
    }
  }
  
  public function getRawNegociateurInfo(){
    return $this->aNegociateurInfo;
  }
  
  public function getName(){
    return 'jlp_core.parser.negociateur';
  }
  
}
