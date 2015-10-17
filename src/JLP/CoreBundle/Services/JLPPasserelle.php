<?php
// src/JLP/CoreBundle/Services/JLPPasserelle.php

namespace JLP\CoreBundle\Services;

use Symfony\Component\Filesystem\Filesystem;

class JLPPasserelle
{
  private $debug;
  private $logger;
  
    const 	LOCAL_PATH = "web/bundles/jlpcorebundle/upload/";
    const 	FLASH_XML_DIR = "front/web/flash/";
    const 	TARGET_UNZIP_DIR = "web/bundles/jlpcorebundle/upload/connectimmo";
    const 	LOG_FILE = "front/web/import/";
    const	ZIP_FILE = "connectimmo.zip";

    private $sLog = '',
                    $iNbAnnonceTraite = 0,
                    $iNbPhotoMaj = 0,
                    $iNbAnnonceSuppr = 0,
                    $iNbAnnonceAjouter = 0,
                    $bStatusPasserelle = 0;
    
  public function __construct($debug = false){
      $this->debug = $debug;
      
  }
  
    public function execute($logger){

        $this->logger = $logger;
        $filesystem = new Filesystem();

        $oZip = new \ZipArchive();
          $oZip->open("web/bundles/jlpcorebundle/upload/connectimmo.zip");
          $bFile = $oZip->extractTo("web/bundles/jlpcorebundle/upload/connectimmo");
          $oZip->close();

        var_dump($bFile);

        //$oXML = \simplexml_load_file($filename);
        $this->logger->info('I love Tony Vairelles\' hairdresser.');
        $this->logger->error('I love Tony Vairelles\' hairdresser.');

    }
    
    private function PrepAnnonces($sFileName) {
        $filesystem = new Filesystem();
        
        self::ToLog("ZIP : ".$sFileName);
        if ( $filesystem->exists($sFileName) ) {
            //Nettoyage du dossier de destination
            $aFilesDel = $this->searchdir(self::TARGET_UNZIP_DIR,0,"FILES");
            foreach ($aFilesDel as $k=>$v) {
                    unlink($v);
            }
            //Upload du Zip
            if($this->UpAndExtract($sFileName,self::TARGET_UNZIP_DIR)){
                    self::ToLog("Extraction du fichier ".$sFileName." réussit");
                    //$oZipFile->delete();
                    $this->bStatusPasserelle = 1;
                    return true;
            }else{
                    self::ToLog("Erreur lors de l'extraction du fichier ".$sFileName);
                    $this->bStatusPasserelle = 0;
                    return false;
            }
        }	
    }
    
    private function UpAndExtract($sZipName,$sDir) {

        //$oFichier->copy($sDir);
        $oZip = new ZipArchive();
        $oZip->open($sZipName);
        $bFile = $oZip->extractTo($sDir);
        $oZip->close();
        $aFilesOrig = $this->searchdir($sDir,0,"FILES");
        $i = 0;
        foreach ($aFilesOrig as $k=>$v) {
                list($width, $height, $type, $attr) = getimagesize($v);
                if($type==2) {
                        $aFiles[$i] = $v;
                        $i++;
                }
        }
        if(sizeof($aFiles>0)){
                if(file_exists($sDir.$sZipName)){
                        if(unlink($sDir.$sZipName)) {
                                return true;
                        }
                }else{
                        return true;
                }
        }
        return $bFile;
    }
    
    private function searchdir ( $path , $maxdepth = -1 , $mode = "FULL" , $d = 0 ){
        if ( substr ( $path , strlen ( $path ) - 1 ) != '/' ) {
            $path .= '/' ;
        }     
        $dirlist = array () ;
        if ( $mode != "FILES" ) {
            $dirlist[] = $path ;
        }
        if (true === $handle = opendir ( $path ) ) {
            while ( false !== ( $file = readdir ( $handle ) ) ) {
                if ( $file != '.' && $file != '..' ) {
                    $file = $path . $file ;
                    if (is_dir ( $file ) ) { 
                        if ( $mode != "DIRS" )  { 
                                     $dirlist[] = $file ; 
                        } 
                    } elseif ( $d >=0 && ($d < $maxdepth || $maxdepth < 0) ) {
                        $result = searchdir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
                        $dirlist = array_merge ( $dirlist , $result ) ;
                    }
                }
            }
            closedir ( $handle ) ;
       }
       if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
       return ( $dirlist ) ;
    }
  
  public function informations(){
      return 'Coucou !! ';
  }
  
  public function getName(){
      return 'jlp_core.passerelle';
  }
}