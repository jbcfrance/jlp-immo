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
  
  /*Agence Variables*/
  protected $aAgenceInfo = array();
  protected $oAgenceEntity = null;
  protected $aYmlAgenceMapping = array();
  
  /*Negociateur Variables*/
  protected $aNegociateurInfo = array();
  protected $oNegociateurEntity = null;
  protected $aYmlNegociateurMapping = array();
  
  /*Annonce Variables*/
  protected $aAnnonceInfo = array();
  protected $oAnnonceEntity = null;
  protected $aYmlAnnonceMapping = array();
  
  /*TypeBien Variables*/
  protected $oTypeBienEntity = null;
  
  /*TypeMandat Variables*/
  protected $oTypeMandatEntity = null;
  
  // On injecte l'EntityManager
  public function __construct($oKernel, EntityManagerInterface $oEm , LoggerInterface $oLogger, $sYmlMapping)
  {
    $this->oEm = $oEm;
    $this->oKernel = $oKernel;
    $this->oLogger = $oLogger;
    $this->oYmlMapping = Yaml::parse($sYmlMapping);
       
  }
    
  public function execute ($sXMLFileName,$logger)
  {
    $this->oLogger = $logger;
    $this->oXml = simplexml_load_file($sXMLFileName);
    $this->oLogger->info("Execute JLPParser");
    $sMainNodeName = $this->oYmlMapping['passerelle']['xml_annonce_node'];

    foreach($this->oXml->{$sMainNodeName} as $oNode)
    {
      //$this->oLogger->info(" oNode : ".print_r($oNode,true));
      /*Traitement préliminaire du XML*/
      
      $this->prepareMappedKey($oNode);
      
      $this->prepareTypeField($oNode);
      
      $this->prepareMappedFields($oNode);

      /* Persisting the entities*/
      //$this->oAnnonceEntity->setStatusAnnonce("active");
      $this->persistAndFlushEntitites();
    }

    return true;   
  } 
  
  public function prepareMappedKey($oNode)
  {
    $aXmlMappedKey = $this->oYmlMapping['passerelle']['keys_parser'];
    foreach ($aXmlMappedKey as $sKeyName => $aKeyInfos)  
    {
      $sEntityObjectName = 'o'.$aKeyInfos['entity']."Entity";

      $oObjectName = $this->oEm->getRepository('JLPCoreBundle:'.$aKeyInfos['entity'])
                               ->findOneBy(array($aKeyInfos['field']=>$oNode->$sKeyName->__toString()));
      if(!empty($oObjectName)){ 
        $this->$sEntityObjectName = $oObjectName; 
      }else{
        $sEntityClassName = "JLP\CoreBundle\Entity\\".$aKeyInfos['entity'];
        $this->$sEntityObjectName = new $sEntityClassName;
        $sSetFunc = 'set'.ucfirst($aKeyInfos['field']);

        $this->$sEntityObjectName->$sSetFunc($oNode->{$sKeyName}->__toString());
      }
      unset($sEntityObjectName,$sSetFunc,$oObjectName);
    }
  }
  public function prepareTypeField($oNode)
  {
    $aXmlTypeFields = $this->oYmlMapping['passerelle']['type_fields'];
    foreach ($aXmlTypeFields as $sFieldName => $aFieldInfos)  
    { 
      $sEntityObjectName = 'o'.$aFieldInfos['parent_entity']."Entity";
      $oTypeEntity = $this->oEm->getRepository('JLPCoreBundle:'.$aFieldInfos['entity'])
                               ->findOneBy(array("type"=>strtolower($oNode->{$sFieldName}->__toString())));
      if(!empty($oTypeEntity))
      {
        $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
        $this->$sEntityObjectName->$sSetFunc($oTypeEntity);
      }else{
        $sEntityTypeClassName = "JLP\CoreBundle\Entity\\".$aFieldInfos['entity'];
        $oTypeEntity = new $sEntityTypeClassName;
        $oTypeEntity->setType(strtolower($oNode->{$sFieldName}->__toString()));
        $this->oEm->persist($oTypeEntity);
        $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
        $this->$sEntityObjectName->$sSetFunc($oTypeEntity);
      }
      unset($sEntityObjectName,$sSetFunc,$oTypeEntity);
    }
  }
  
  public function prepareMappedFields($oNode)
  {
    $aXmlMappedFields = $this->oYmlMapping['passerelle']['parser'];
    foreach ($aXmlMappedFields as $sFieldName => $aFieldInfos)  
    { 
      $sEntityObjectName = 'o'.$aFieldInfos['entity']."Entity";
      $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
      if(true === isset($aFieldInfos['date']) && true === $aFieldInfos['date'])
      {

        $sDateFormat = isset($aFieldInfos['date_format']) ? $aFieldInfos['date_format'] : 'j/m/Y';

        $oDate = $this->cleanDateFormat($sDateFormat,$oNode->{$sFieldName}->__toString());   
        $this->$sEntityObjectName->$sSetFunc($oDate);
      }else{
        $this->$sEntityObjectName->$sSetFunc($oNode->{$sFieldName}->__toString());
      }
      unset($sEntityObjectName,$sSetFunc,$sDateFormat,$oDate);
    }
  }
  
  public function cleanDateFormat($sDateFormat,$date)
  {
    $oDate = \DateTime::createFromFormat($sDateFormat,$date);
    
    return $oDate;
  }
  
  public function persistAndFlushEntitites()
  {
    $this->oEm->getClassMetaData(get_class($this->oAgenceEntity))->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
    $this->oEm->getClassMetaData(get_class($this->oNegociateurEntity))->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);  
    $this->oEm->getClassMetaData(get_class($this->oAnnonceEntity))->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
    
    $this->oEm->persist($this->oAgenceEntity);
    $this->oNegociateurEntity->setAgence($this->oAgenceEntity);
    $this->oEm->persist($this->oNegociateurEntity);
    $this->oAnnonceEntity->setAgence($this->oAgenceEntity);
    $this->oAnnonceEntity->setNegociateur($this->oNegociateurEntity);

    $this->oEm->persist($this->oAnnonceEntity);

    $this->oEm->flush();
  } 
  
  public function getName(){
    return 'jlp_core.parser';
  }
  
}
