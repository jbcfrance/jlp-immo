<?php
// src/JLP/CoreBundle/Services/JLPImageTool.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Entity\Annonce;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use \Symfony\Component\Yaml\Yaml;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Imagick\Imagine;


/*
 * Objectif : Traiter les images des chaque annonces et les sauvegarder dans le system de fichier du site. 
 */

class JLPImageTool
{
  
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
  
  public function __construct($oKernel, EntityManagerInterface $oEm , LoggerInterface $oLogger, $sYmlMapping)
  {
    $this->oEm = $oEm;
    $this->oKernel = $oKernel;
    $this->oLogger = $oLogger;
    $this->oYmlMapping = Yaml::parse($sYmlMapping);
       
  }
    
  public function execute($sXMLFileName,$logger)
  {
    $this->oLogger = $logger;
    $this->oXml = simplexml_load_file($sXMLFileName);
    $this->oLogger->info("Execute JLPImageTool");
    $sMainNodeName = $this->oYmlMapping['passerelle']['xml_annonce_node'];
    
    
    foreach($this->oXml->{$sMainNodeName} as $oNode)
    {
      $this->extractImageFromAnnonce($oNode);
      
      
      
    }
  }
  
  public function extractImageFromAnnonce($oNode)
  {
    $aAnnonceImages = $oNode->{$this->oYmlMapping['passerelle']['xml_images_node']};
    
    foreach($aAnnonceImages as $oImageName)
    {
      $this->createImageByType($oImageName->{'photo'});
    }
    
    
  }
  
  public function createImageByType($oImageName)
  {
    $aTypeImage = $this->oEm->getRepository('JLPCoreBundle:TypeImage')->findAll();
    $sImageName = $oImageName->__toString();
    
    
    
    foreach($aTypeImage as $oTypeImage){
      
    }
  }
  
  public function getName(){
    return 'jlp_core.image_tool';
  }
}
