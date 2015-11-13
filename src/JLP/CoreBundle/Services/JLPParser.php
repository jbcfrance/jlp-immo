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
  
  // On injecte l'EntityManager
  public function __construct($oKernel, EntityManagerInterface $oEm , LoggerInterface $oLogger, $sYmlMapping)
  {
    $this->oEm = $oEm;
    $this->oKernel = $oKernel;
    $this->oLogger = $oLogger;
    $this->oYmlMapping = Yaml::parse($sYmlMapping);
       
  }
    
  public function execute ($sXMLFileName)
  {
    $this->oXml = simplexml_load_file($sXMLFileName);
    
    $sMainNodeName = $this->oYmlMapping['passerelle']['xml_annonce_node'];
    
    
    $aXmlMappedKey = $this->oYmlMapping['passerelle']['keys_parser'];
    $aXmlMappedFields = $this->oYmlMapping['passerelle']['parser'];
    
    foreach($this->oXml->{$sMainNodeName} as $oNode)
    {
      /*Traitement préliminaire du XML*/
      foreach ($aXmlMappedKey as $sKeyName => $aKeyInfos)
      {
        $sObjectName = "o".$aKeyInfos['entity'];
        $sEntityObjectName = 'o'.$aKeyInfos['entity']."Entity";
        
        $$sObjectName = $this->oEm->getRepository('JLPCoreBundle:'.$aKeyInfos['entity'])->findOneBy(array($aKeyInfos['field']=>$oNode->$sKeyName));
    
        if(!empty($$sObjectName)){
          $this->$sEntityObjectName = $$sObjectName();
        }else{
          $sEntityClassName = "JLP\CoreBundle\Entity\\".$aKeyInfos['entity'];
          $this->$sEntityObjectName = new $sEntityClassName;
        }        
      }
      foreach ($aXmlMappedFields as $sFieldName => $aFieldInfos) 
      { 
        $sEntityObjectName = 'o'.$aFieldInfos['entity']."Entity";
        $sSetFunc = 'set'.ucfirst($aFieldInfos['field']);
        
        $this->$sEntityObjectName->$sSetFunc = $oNode->{$sFieldName};
      }
      /* Persisting the entities*/
      
      $this->oEm->persist($this->oAgenceEntity);
      $this->oNegociateurEntity->setAgence($this->oAgenceEntity);
      $this->oEm->persist($this->oNegociateurEntity);
      $this->oAnnonceEntity->setAgence($this->oAgenceEntity);
      $this->oAnnonceEntity->setNegociateur($this->oNegociateurEntity);
      //$this->oEm->persist($this->oAnnonceEntity);
      
      $this->oEm->flush();
    }
    
    
    
    return true;   
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
