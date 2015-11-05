<?php
// src/JLP/CoreBundle/Services/JLPPasserelle.php

namespace JLP\CoreBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Finder\Finder;
//use Symfony\Component\DomCrawler\Crawler;


class JLPPasserelle
{
  const   LOCAL_PATH        = "web/bundles/jlpcorebundle/upload/";
  //const   FLASH_XML_DIR     = "front/web/flash/";
  const   TARGET_UNZIP_DIR  = "web/bundles/jlpcorebundle/upload/connectimmo";
  const   TARGET_IMAGE_DIR  = "web/bundles/jlpcorebundle/images/source/";
  //const   LOG_FILE          = "front/web/import/";
  const   AGENCE            = "Agence";
  const   NEGOCIATEUR       = "Negociateur";
  
  private $debug;
  private $logger;
  private $kernel;
  
  private $zipFilename;
  private $xmlFilename;

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
  
    
  public function __construct($kernel,$debug = false){
    $this->debug = $debug; 
    $this->kernel = $kernel;
    $this->zipFilename = $this->kernel->getContainer()->getParameter('jlp_core.passerelle.zip_name');
    $this->xmlFilename = $this->kernel->getContainer()->getParameter('jlp_core.passerelle.xml_filename');
  }
  
  public function execute($logger){

      $this->logger = $logger;
      
    if(!$this->prepAnnonces(self::LOCAL_PATH.$this->zipFilename)){
      $this->logger->error('Erreur lors de la preparation des annonces : Import stoppé !');
      throw new Exception('Erreur lors de la preparation des annonces : Import stoppé !');
    }
    
    $agenceParser = $this->kernel->getContainer()->get('jlp_core.parser.agence');
    $agenceParser->execute(self::TARGET_UNZIP_DIR."/".$this->xmlFilename);
    
    /*if(!$this->parsingXml(self::TARGET_UNZIP_DIR."/".$this->xmlFilename)){
      $this->logger->error('Erreur de parsing du XML !');
      exit;
    }*/

    var_dump($agenceParser->getRawAgenceInfo());
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