<?php
// src/JLP/CoreBundle/Services/JLPPasserelle.php

namespace JLP\CoreBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DomCrawler\Crawler;


class JLPPasserelle
{
  private $debug;
  private $logger;
  
  const   LOCAL_PATH        = "web/bundles/jlpcorebundle/upload/";
  const   FLASH_XML_DIR     = "front/web/flash/";
  const   TARGET_UNZIP_DIR  = "web/bundles/jlpcorebundle/upload/connectimmo";
  const   TARGET_IMAGE_DIR  = "web/bundles/jlpcorebundle/images/source/";
  const   LOG_FILE          = "front/web/import/";
  const   ZIP_FILE          = "connectimmo.zip";

  private $sLog = '',
          $iNbAnnonceTraite = 0,
          $iNbPhotoMaj = 0,
          $iNbAnnonceSuppr = 0,
          $iNbAnnonceAjouter = 0,
          $bStatusPasserelle = 0;
  
  private $oXml                 = null;
  private $aAgenceInfo          = array();
  private $aNegociateurInfo 	= array();
  private $aAnnonceInfo 		= array();
  
    
  public function __construct($debug = false){
    $this->debug = $debug;  
  }
  
  public function execute($logger){

      $this->logger = $logger;

    if(!$this->prepAnnonces(self::LOCAL_PATH.self::ZIP_FILE)){
      $this->logger->error('Erreur lors de la preparation des annonces : Import stoppé !');
      exit;
    }
    
    //$this->oXml = new Crawler(self::TARGET_UNZIP_DIR."/annonces.xml");
    $this->oXml = simplexml_load_file(self::TARGET_UNZIP_DIR."/annonces.xml");
    
    foreach($this->oXml->annonce as $oNode) {
      /*Traitement préliminaire du XML*/
      foreach ($oNode->children() as $elementAnnonce) {
        if(strstr($elementAnnonce->getName(),"A")=="Agence") {
          $tmp = $elementAnnonce;
          $this->aAgenceInfo = array_merge($this->aAgenceInfo,array($elementAnnonce->getName()=>(string)$tmp));
        }
        if(strstr($elementAnnonce->getName(),"N")=="Negociateur") {
          $tmp = $elementAnnonce;
          $this->aNegociateurInfo = array_merge($this->aNegociateurInfo,array($elementAnnonce->getName()=>(string)$tmp));
        }
        if((strstr($elementAnnonce->getName(),"A")!="Agence") && (strstr($elementAnnonce->getName(),"N")!="Negociateur")) {
          $tmp = $elementAnnonce;
          $this->aAnnonceInfo = array_merge($this->aAnnonceInfo,array($elementAnnonce->getName()=>(string)$tmp));
        }
      }
    }
    
    var_dump($this->aAgenceInfo);
    //var_dump($this->aNegociateurInfo);
    //var_dump($this->aAnnonceInfo);

  }

  private function prepAnnonces($sFileName) {
    $filesystem = new Filesystem();

    $this->logger->info("ZIP : ".$sFileName);
    if ( $filesystem->exists($sFileName) ) {
      $this->logger->info("File Exists true");            
      //Upload du Zip
      if($this->extractionProcess($sFileName)){
        $this->logger->info("Extraction du fichier ".$sFileName." réussit");    
        $this->moveSourceImage();
        //$oZipFile->delete();
        $this->bStatusPasserelle = 1;
        return true;
      }else{
        $this->logger->error("Erreur lors de l'extraction du fichier ".$sFileName);
        $this->bStatusPasserelle = 0;
        return false;
      }
    }else{
      $this->logger->error("$sFileName does not exists !");
    }	
  }

  private function extractionProcess( $sFileToExtract){  
    $cleaningProcess = new Process('rm -rf '.self::TARGET_UNZIP_DIR);
    $cleaningProcess->run();
    if (!$cleaningProcess->isSuccessful()) {
      throw new ProcessFailedException($cleaningProcess);
    }

    $cleaningSourceProcess = new Process('rm -rf '.self::TARGET_IMAGE_DIR."*");
    $cleaningSourceProcess->run();
    if (!$cleaningSourceProcess->isSuccessful()) {
      throw new ProcessFailedException($cleaningSourceProcess);
    }

    $extractProcess = new Process('unzip '.$sFileToExtract.' -d '.self::TARGET_UNZIP_DIR);
    $extractProcess->run();
    if (!$extractProcess->isSuccessful()) {
      throw new ProcessFailedException($extractProcess);
    }
    return true;
  }

  private function moveSourceImage(){
    $finder = new Finder();
    $finder->files()->name('*.jpg');

    foreach($finder->in(self::TARGET_UNZIP_DIR) as $image){
      $moveImageProcess = new Process('mv '.self::TARGET_UNZIP_DIR.'/'.$image->getFilename().' '.self::TARGET_IMAGE_DIR.$image->getFilename());
      $moveImageProcess->run();
      if (!$moveImageProcess->isSuccessful()) {
        throw new ProcessFailedException($moveImageProcess);
      }
      unset($moveImageProcess);
    }
  }
      
  public function informations(){
    return 'Coucou !! ';
  }
  
  public function getName(){
    return 'jlp_core.passerelle';
  }
}