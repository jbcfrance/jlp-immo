<?php
// src/JLP/CoreBundle/Services/JLPPasserelle.php

namespace JLP\CoreBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Finder\Finder;
use \Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\EntityManagerInterface;



class JLPPasserelle
{
  const   LOCAL_PATH        = "web/bundles/jlpcore/upload/";

  const   TARGET_UNZIP_DIR  = "web/bundles/jlpcore/upload/connectimmo/";
  const   TARGET_IMAGE_DIR  = "web/bundles/jlpcore/images/source/";

  const   AGENCE            = "Agence";
  const   NEGOCIATEUR       = "Negociateur";
  const   ANNONCE           = "Annonce";
  
  private $debug;
  private $logger;
  private $kernel;
  private $ymlMapping;
  private $em;
  
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
  
    
  public function __construct($kernel,$ymlMapping,EntityManagerInterface $em,$debug = false){
    $this->debug = $debug; 
    $this->kernel = $kernel;
    $this->em = $em;
    $this->ymlMapping = Yaml::parse($ymlMapping);
    $this->zipFilename = $this->ymlMapping['passerelle']['zip_name'];
    $this->xmlFilename = $this->ymlMapping['passerelle']['xml_filename'];
  }
  
  public function execute($logger){

      $this->logger = $logger;
      
    if(!$this->prepAnnonces(self::LOCAL_PATH.$this->zipFilename)){
      $this->logger->error('Erreur lors de la preparation des annonces : Import stoppé !');
      throw new Exception('Erreur lors de la preparation des annonces : Import stoppé !');
    }
    
    $oParser = $this->kernel->getContainer()->get('jlp_core.parser');
    $oParser->execute(self::TARGET_UNZIP_DIR."/".$this->xmlFilename,$logger);
    $this->iNbAnnonceTraite = $oParser->getNbAnnonceTraite();
    $this->deleteStandByAnnonce();
    
    $oImageTool = $this->kernel->getContainer()->get('jlp_core.image_tool');
    $oImageTool->execute(self::TARGET_UNZIP_DIR."/".$this->xmlFilename,$logger);
    
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
        
        $this->putAnnonceInStandBy();
        
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
  
  private function putAnnonceInStandBy()
  {
    $aAnnonceEntities = $this->em->getRepository('JLPCoreBundle:Annonce')->findAll();
 
    foreach($aAnnonceEntities as $oAnnonce)
    {

      $oAnnonce->setStatusAnnonce('standby');
      $this->em->persist($oAnnonce);
      $this->em->flush($oAnnonce);

    }
    
  }
  
  private function deleteStandByAnnonce()
  {
    $aAnnonceEntities = $this->em->getRepository('JLPCoreBundle:Annonce')->findBy(array('statusAnnonce'=>'standby'));
        
    foreach($aAnnonceEntities as $oAnnonce)
    {
      $this->em->remove($oAnnonce);
      $this->em->flush($oAnnonce);
    }
  }
  public function informations(){
    return 'Coucou !! ';
  }
  
  public function getName(){
    return 'jlp_core.passerelle';
  }
}
