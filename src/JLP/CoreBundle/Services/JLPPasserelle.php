<?php
// src/JLP/CoreBundle/Services/JLPPasserelle.php

namespace JLP\CoreBundle\Services;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Finder\Finder;
use \Symfony\Component\Yaml\Yaml;
use Doctrine\ORM\EntityManagerInterface;
use Assetic\Exception\Exception;


/**
* Service Passerelle
* 
* @author      Jean-Baptiste CIEPKA
* @version     1.0
* @package     JLP
* @bundle      CoreBundle
*
* 
*/
class JLPPasserelle
{
    const   LOCAL_PATH        = "web/bundles/jlpcore/upload/";

    const   TARGET_UNZIP_DIR  = "web/bundles/jlpcore/upload/connectimmo";
    const   TARGET_IMAGE_DIR  = "web/bundles/jlpcore/images/source/";

    const   AGENCE            = "Agence";
    const   NEGOCIATEUR       = "Negociateur";
    const   ANNONCE           = "Annonce";
   
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
    
    /**
     *
     * @var string
     */
    private $zipFilename;
    
    /**
     *
     * @var string
     */
    private $xmlFilename;
    
    /**
     *
     * @var integer
     */
    private $iNbAnnonceTraite = 0;
    
    /**
     *
     * @var integer
     */
    private $iNbPhotoMaj = 0;
    
    /**
     *
     * @var integer
     */
    private $iNbAnnonceSuppr = 0;
    
    /**
     *
     * @var integer
     */
    private $iNbAnnonceAjouter = 0;
    
    /**
     *
     * @var integer
     */
    private $bStatusPasserelle = 0;
   
    
    public function __construct($oKernel, $oYmlMapping, EntityManagerInterface $oEm, $bDebug = false) {
    $this->bDebug = $bDebug; 
    $this->oKernel = $oKernel;
    $this->oEm = $oEm;
    $this->oYmlMapping = Yaml::parse($oYmlMapping);
    $this->zipFilename = $this->oYmlMapping['passerelle']['zip_name'];
    $this->xmlFilename = $this->oYmlMapping['passerelle']['xml_filename'];
    }
  
    /**
     * execute
     * 
     * Method lauching the process. 
     *
     * @param Logger  $oLogger
     */
    public function execute($oLogger) {

        $this->oLogger = $oLogger;
      
    if (!$this->prepAnnonces(self::LOCAL_PATH.$this->zipFilename)) {
        $this->oLogger->error('Erreur lors de la preparation des annonces : Import stoppé !');
        throw new Exception('Erreur lors de la preparation des annonces : Import stoppé !');
    }
    
    $oParser = $this->oKernel->getContainer()->get('jlp_core.parser');
    $oParser->execute(self::TARGET_UNZIP_DIR."/".$this->xmlFilename, $oLogger);
    $this->iNbAnnonceTraite = $oParser->getNbAnnonceTraite();
    $this->deleteStandByAnnonce();
    $this->checkNegociateur();
    $this->checkAgence();
    
    }
  
    /**
     * prepAnnonces
     * 
     * Methode preparing the Annonces by extracting the zip archive and moving the images to the images dir. 
     *  
     *
     * @param string  $sFileName
     */
    private function prepAnnonces($sFileName)
    {
    $oFilesystem = new Filesystem();

    $this->oLogger->info("ZIP : ".$sFileName);
    if ($oFilesystem->exists($sFileName)) {
        $this->oLogger->info("File Exists true");            
        //Upload du Zip
        if ($this->extractionProcess($sFileName)) {
        $this->oLogger->info("Extraction du fichier ".$sFileName." réussit");    
        $this->moveSourceImage();

        $this->bStatusPasserelle = 1;
        
        $this->putAnnonceInStandBy();
        
        return true;
        } else {
        $this->oLogger->error("Erreur lors de l'extraction du fichier ".$sFileName);
        $this->bStatusPasserelle = 0;
        return false;
        }
    } else {
        $this->oLogger->error("$sFileName does not exists !");
    }	
    }
  
    /**
     * extractionProcess
     * 
     * Method cleaning the target dir inorder to proceed to a new extraction of the ZIP Archive.
     *
     * @param string  $sFileToExtract
     */
    private function extractionProcess($sFileToExtract) {  
    $oCleaningProcess = new Process('rm -rf '.self::TARGET_UNZIP_DIR);
    $oCleaningProcess->run();
    if (!$oCleaningProcess->isSuccessful()) {
        throw new ProcessFailedException($oCleaningProcess);
    }

    $oCleaningSourceProcess = new Process('rm -rf '.self::TARGET_IMAGE_DIR."*");
    $oCleaningSourceProcess->run();
    if (!$oCleaningSourceProcess->isSuccessful()) {
        throw new ProcessFailedException($oCleaningSourceProcess);
    }

    $oExtractProcess = new Process('unzip '.$sFileToExtract.' -d '.self::TARGET_UNZIP_DIR);
    $oExtractProcess->run();
    if (!$oExtractProcess->isSuccessful()) {
        throw new ProcessFailedException($oExtractProcess);
    }
    
    unset($oCleaningProcess, $oCleaningSourceProcess, $oExtractProcess);
    
    return true;
    }
  
    /**
     * moveSourceImage
     * 
     * Method searching the images jpg in the dir extracted from the zip and moving them in the images/source dir.
     *
     * @param void
     */
    private function moveSourceImage() {
    $oFinder = new Finder();
    $oFinder->files()->name('*.jpg');

    foreach ($oFinder->in(self::TARGET_UNZIP_DIR) as $oImage) {
        $oMoveImageProcess = new Process('mv '.self::TARGET_UNZIP_DIR.'/'.$oImage->getFilename().' '.self::TARGET_IMAGE_DIR.$oImage->getFilename());
        $oMoveImageProcess->run();
        if (!$oMoveImageProcess->isSuccessful()) {
        throw new ProcessFailedException($oMoveImageProcess);
        }
        unset($oMoveImageProcess);
    }
    }
  
    /**
     * putAnnonceInStandBy
     * 
     * Method updating the status of each annonce to "Standby".
     *
     * @param void
     */
    private function putAnnonceInStandBy()
    {
    $aAnnonceEntities = $this->oEm->getRepository('JLPCoreBundle:Annonce')->findAll();
 
    foreach ($aAnnonceEntities as $oAnnonce)
    {

        $oAnnonce->setStatusAnnonce('standby');
        $this->oEm->persist($oAnnonce);
        $this->oEm->flush($oAnnonce);

    }
    
    }
  
    /**
     * checkNegociateur
     * 
     * Method removing the negociateur without any annonce left. 
     *
     * @param void
     */
    private function checkNegociateur() {
    $this->oLogger->info("Delete Negociateur without any annonce."); 
    $aNegociateurEntities = $this->oEm->getRepository('JLPCoreBundle:Negociateur')->getNegociateurWithoutAnnonce();
    foreach ($aNegociateurEntities as $oNegociateur)
    {
        $this->oEm->remove($oNegociateur);
        $this->oEm->flush($oNegociateur);
    }
    }
  
    /**
     * checkAgence
     * 
     * Method removing the agence without any negociateur left. 
     *
     * @param void
     */
    private function checkAgence() {
    $this->oLogger->info("Delete Agence without any Negociateur.");
    $aAgenceEntities = $this->oEm->getRepository('JLPCoreBundle:Agence')->getAgenceWithoutNegociateur();
    foreach ($aAgenceEntities as $oAgence)
    {
        $this->oEm->remove($oAgence);
        $this->oEm->flush($oAgence);
    }
    }
  
    /**
     * deleteStandByAnnonce
     * 
     * This method delete the annonce that a left in standby status by the passerelle's process. 
     * It's mean that they are not present in the XML source anymore. The images associated with the annonce are deleted two. 
     *
     * @param void
     */
    private function deleteStandByAnnonce()
    {
    $this->oLogger->info("Delete Annonce still in standby"); 
    $aAnnonceEntities = $this->oEm->getRepository('JLPCoreBundle:Annonce')->findBy(array('statusAnnonce'=>'standby'));
        
    foreach ($aAnnonceEntities as $oAnnonce)
    {
        $aImagesCollection = $oAnnonce->getImages();
        $this->deleteImages($aImagesCollection);
        $this->oEm->remove($oAnnonce);
        $this->oEm->flush($oAnnonce);
    }
    }
  
    /**
     * deleteImages
     * 
     * This method delete the images given in a collection
     *
     * @param array : $aImagesCollection
     */
    private function deleteImages($aImagesCollection)
    {
    if (!empty($aImagesCollection))
    {
        foreach ($aImagesCollection as $oAnnonceImages)
        {
        $this->oEm->remove($oAnnonceImages);
        }
        $this->oEm->flush();
    }
    }
  
  
    public function informations() {
    return 'Information';
    }
  
    public function getName() {
    return 'jlp_core.passerelle';
    }
}
