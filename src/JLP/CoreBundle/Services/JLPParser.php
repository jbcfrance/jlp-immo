<?php

// src/JLP/CoreBundle/Services/JLPParser.php

namespace JLP\CoreBundle\Services;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of JLPParser
 *
 * @author jciepka
 */
class JLPParser {
  /**
   * @var EntityManagerInterface
   */
  protected $oEm;
  
  /**
   * @var SimpleXMLElement
   */
  protected $oXml;

  // On injecte l'EntityManager
  public function __construct($oKernel, EntityManagerInterface $oEm, \SimpleXMLElement $oXml)
  {
    $this->oEm = $oEm;
    $this->oXml = $oXml;
    $this->oKernel = $oKernel;
  }
  
  protected function parsingXml ($sXMLFileName)
  {
    $this->oXml = simplexml_load_file($sXMLFileName);
    
    $sMainNodeName = $this->oKernel->getContainer()->getParameter('jlp_core.passerelle.xml_annonce_node');
    
    foreach($this->oXml->{$sMainNodeName} as $oNode) {
      /*Traitement préliminaire du XML*/
      foreach ($oNode->children() as $oElementAnnonce) {
          $this->extractInformation($oElementAnnonce);
      }
    }
    return true;   
  }
  
  public function getName(){
    return 'jlp_core.parser';
  }
  
}
