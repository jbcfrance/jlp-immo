<?php
// src/JLP/CoreBundle/Services/JLPImageTool.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Entity\Annonce;
use JLP\CoreBundle\Entity\Images;
use Symfony\Component\Finder\Finder;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use \Symfony\Component\Yaml\Yaml;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Gd\Imagine;


/*
 * Objectif : Traiter les images des chaque annonces et les sauvegarder dans le system de fichier du site. 
 */

class JLPImageTool
{
  const   BUNDLE_IMAGE_DIR = "web/bundles/jlpcore/images/";

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
    
    $iIdAnnonce = $oNode->{'identifiant'}->__toString();
    
    foreach($aAnnonceImages as $oImageName)
    {
      $this->createImageByType($iIdAnnonce,$oImageName->{'photo'});
    }
    
    $this->oEm->flush();
    
  }
  
  public function createImageByType($iIdAnnonce,$oImageName)
  {
    $aTypeImage = $this->oEm->getRepository('JLPCoreBundle:TypeImage')->findAll();
    $sImageName = $oImageName->__toString();
    
    $oAnnonceEntity = $this->oEm->getRepository('JLPCoreBundle:Annonce')->findOneBy(array('id'=>$iIdAnnonce));
    
    
    foreach($aTypeImage as $oTypeImage){
      $oImagine = new Imagine();
      $oImagine->open(self::BUNDLE_IMAGE_DIR."source/".$sImageName)
              ->resize(new Box($oTypeImage->getWidth(),$oTypeImage->getHeight()))
              ->save(self::BUNDLE_IMAGE_DIR.$oTypeImage->getDir().'/'.$sImageName, array('jpeg_quality' => 100));
      $oImageEntity = new Images();
      $oImageEntity->setFileName($sImageName);
      $oImageEntity->setTypeImage($oTypeImage);
      $this->oEm->persist($oImageEntity);
      $oAnnonceEntity->addImage($oImageEntity);
      $this->oEm->persist($oAnnonceEntity);
    }
    
  }
  
  public function getName(){
    return 'jlp_core.image_tool';
  }
}
