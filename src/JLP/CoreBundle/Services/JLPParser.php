<?php

// src/JLP/CoreBundle/Services/JLPParser.php

namespace JLP\CoreBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use \Symfony\Component\Yaml\Yaml;
use JLP\CoreBundle\Entity\Agence;
use JLP\CoreBundle\Entity\Negociateur;
use JLP\CoreBundle\Entity\Annonce;


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
    $this->oLogger->info(" execute ");
    $sMainNodeName = $this->oYmlMapping['passerelle']['xml_annonce_node'];
    
    
    $aXmlMappedKey = $this->oYmlMapping['passerelle']['keys_parser'];
    $aXmlTypeFields = $this->oYmlMapping['passerelle']['type_fields'];
    $aXmlMappedFields = $this->oYmlMapping['passerelle']['parser'];
    
    foreach($this->oXml->{$sMainNodeName} as $oNode)
    {
      //$this->oLogger->info(" oNode : ".print_r($oNode,true));
      /*Traitement préliminaire du XML*/
      foreach ($aXmlMappedKey as $sKeyName => $aKeyInfos)
      {
        $sObjectName = "o".$aKeyInfos['entity'];
        $sEntityObjectName = 'o'.$aKeyInfos['entity']."Entity";
        
        $oObjectName = $this->oEm->getRepository('JLPCoreBundle:'.$aKeyInfos['entity'])->findOneBy(array($aKeyInfos['field']=>$oNode->$sKeyName->__toString()));
        $this->oLogger->info(" Search Entities ");
        if(!empty($oObjectName)){
          $this->oLogger->info(" Entity found 'JLPCoreBundle:'".$aKeyInfos['entity']); 
          $this->$sEntityObjectName = $oObjectName; 
        }else{
          $sEntityClassName = "JLP\CoreBundle\Entity\\".$aKeyInfos['entity'];
          $this->oLogger->info(" New JLP\CoreBundle\Entity\\".$aKeyInfos['entity']);
          $this->$sEntityObjectName = new $sEntityClassName;
          $sSetFunc = 'set'.ucfirst($aKeyInfos['field']);
        
          $this->$sEntityObjectName->$sSetFunc($oNode->{$sKeyName}->__toString());
        }
      }
      
      foreach($aXmlTypeFields as $sTypeName => $aTypeInfos)
      {
        $this->prepareTypeField($sTypeName,$aTypeInfos,$oNode);
      }
      
      foreach ($aXmlMappedFields as $sFieldName => $aFieldInfos)  
      { 
        
        $sEntityObjectName = 'o'.$aFieldInfos['entity']."Entity";
        $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
        if(true === isset($aFieldInfos['date']) && true === $aFieldInfos['date'])
        {
          $oDate = $this->cleanDateFormat($oNode->{$sFieldName}->__toString());   
          $this->$sEntityObjectName->$sSetFunc($oDate);
        }else{
          $this->$sEntityObjectName->$sSetFunc($oNode->{$sFieldName}->__toString());
        }
        

      }
      /* Persisting the entities*/
      $this->persistAndFlushEntitites();
    }
    
    
    
    return true;   
  } 
  
  public function prepareTypeField($sFieldName,$aFieldInfos,$oNode)
  {
    $sEntityObjectName = 'o'.$aFieldInfos['parent_entity']."Entity";
    $oTypeEntity = $this->oEm->getRepository('JLPCoreBundle:'.$aFieldInfos['entity'])->findOneBy(array("type"=>strtolower($oNode->{$sFieldName}->__toString())));
    $this->oLogger->info($aFieldInfos['entity']." = ".$oNode->{$sFieldName}->__toString());
    if(!empty($oTypeEntity))
    {
      $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
      $this->$sEntityObjectName->$sSetFunc($oTypeEntity);
    }else{
      $sEntityTypeClassName = "JLP\CoreBundle\Entity\\".$aFieldInfos['entity'];
      $this->oLogger->info(" New JLP\CoreBundle\Entity\\".$aFieldInfos['entity']);
      $oTypeEntity = new $sEntityTypeClassName;
      $oTypeEntity->setType(strtolower($oNode->{$sFieldName}->__toString()));
      $this->oEm->persist($oTypeEntity);
      $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
      $this->$sEntityObjectName->$sSetFunc($oTypeEntity);
    }
  }
  
  public function cleanDateFormat($date)
  {
    $oDate = \DateTime::createFromFormat('j/m/Y',$date);
    
    return $oDate;
  }
  
  public function persistAndFlushEntitites()
  {
    $this->oLogger->info(" persistAndFlushEntitites ");
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
  
  
  
  public function prepMapping($oElementAnnonce)
  {
    $aParserMapping = $this->oYmlMapping['passerelle']['parser'];
    
    $aFieldMapping = $aParserMapping[$oElementAnnonce->getName()];
    
    /*switch($aFieldMapping['entity'])
    {
      case 'agence':
        $this->findOrCreateAgence($oElementAnnonce);
      break;
      case 'negociateur':
        $this->findOrCreateNegociateur($oElementAnnonce);
      break;
      case 'annonce':
        $this->findOrCreateAgence($oElementAnnonce);
      break;
      default:
        throw new Exception ('Unkown mapping for this element : '.$oElementAnnonce->getName());
    }*/
    
    var_dump($aFieldMapping);die();
  }
  
  
  public function buildEntities()
  {
    foreach($this->aEntitiesFields as $sEntityFieldName){

      $sFunc = 'set'.ucfirst($sEntityFieldName);
      $this->oNegociateurEntity->{$sFunc}($this->aNegociateurInfo[$this->aYmlNegociateurMapping[$sEntityFieldName]]);

      $this->oEm->persist($this->oNegociateurEntity);
    }
    $this->oEm->flush();
  }
  
  public function prepAgence()
  {
    $aClassProperties = $this->oEm->getClassMetadata('JLPCoreBundle:Agence')->getFieldNames();
    $aEntitiesFields = array_merge(
                  $aClassProperties, 
                  $this->oEm->getClassMetadata('JLPCoreBundle:Agence')->getAssociationNames()
    );
      
    $oAgence = $this->oEm->getRepository('JLPCoreBundle:Agence')->findOneBy(array("agenceId"=>$this->aAgenceInfo[$this->aYmlAgenceMapping["agenceId"]])); 
    if(!empty($oAgence)){
      $this->oAgenceEntity = $oAgence;
    }
    foreach($aEntitiesFields as $sEntityFieldName){
      if($sEntityFieldName != 'id')
      {
        $sFunc = 'set'.ucfirst($sEntityFieldName);
        $this->oAgenceEntity->{$sFunc}($this->aAgenceInfo[$this->aYmlAgenceMapping[$sEntityFieldName]]);
        unset($sFunc);
      }
      $this->oEm->persist($this->oAgenceEntity);
    }
    $this->oEm->flush();
    unset($aClassProperties,$aEntitiesFields);
    
  }
  
  public function prepNegociateur()
  {
    
  }

  
  public function getName(){
    return 'jlp_core.parser';
  }
  
}
