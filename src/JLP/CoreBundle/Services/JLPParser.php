<?php

// src/JLP/CoreBundle/Services/JLPParser.php

namespace JLP\CoreBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use \Symfony\Component\Yaml\Yaml;

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
   * @var Kernel
   */
  protected $oKernel;
  
  /**
   * @var SimpleXMLElement
   */
  protected $oXml;
  
  /**
   * @var ConsoleLogger
   */
  protected $oLogger;
  
  /**
   * @var YamlConfig
   */
  protected $oYmlMapping;
  
  // On injecte l'EntityManager
  public function __construct($oKernel, EntityManagerInterface $oEm , LoggerInterface $oLogger, $oYmlMapping)
  {
    $this->oEm = $oEm;
    $this->oKernel = $oKernel;
    $this->oLogger = $oLogger;
    $this->oYmlMapping = Yaml::parse($oYmlMapping);
  }
  
  protected function parsingXml ($sXMLFileName)
  {
    $this->oXml = simplexml_load_file($sXMLFileName);
    
    $sMainNodeName = $this->oYmlMapping['passerelle']['xml_annonce_node'];
    
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
