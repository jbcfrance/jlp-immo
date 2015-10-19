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

        if(!$this->PrepAnnonces(self::LOCAL_PATH.self::ZIP_FILE)){
		
            /*STOP FICHIER NON IMPORTER*/
            $this->logger->error('Erreur lors de la preparation des annonces : Import stoppé !');
            exit;
	}
        
        $oZip = new \ZipArchive();
          $oZip->open("web/bundles/jlpcorebundle/upload/connectimmo.zip");
          $bFile = $oZip->extractTo("web/bundles/jlpcorebundle/upload/connectimmo");
          $oZip->close();

        var_dump($bFile);

        //$oXML = \simplexml_load_file($filename);
        $this->logger->info('I love Tony Vairelles\' hairdresser.');
        $this->logger->error('I love Tony Vairelles\' hairdresser.');

    }
    
    private function prepAnnonces($sFileName) {
        $filesystem = new Filesystem();
        
        $this->logger->info("ZIP : ".$sFileName);
        if ( $filesystem->exists($sFileName) ) {
            $this->logger->info("File Exists true");
            //Nettoyage du dossier de destination
            $aFilesDel = $this->searchdir(self::TARGET_UNZIP_DIR,0,"FILES");
            foreach ($aFilesDel as $k=>$v) {
                    unlink($v);
            }
            //Upload du Zip
            if($this->upAndExtract($sFileName,self::TARGET_UNZIP_DIR)){
                    $this->logger->info("Extraction du fichier ".$sFileName." réussit");    
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
    
    private function upAndExtract($sZipName,$sDir) {

        $this->logger->info("upAndExtract Start");
        //$oFichier->copy($sDir);
        $oZip = new \ZipArchive();
        $oZip->open($sZipName);
        $bFile = $oZip->extractTo($sDir);
        $oZip->close();
        $aFilesOrig = $this->searchDir($sDir,0,"FILES");
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
    
    private function searchDir ( $path , $maxdepth = -1 , $mode = "FULL" , $d = 0 ){
        $this->logger->info("searchDir on path : $path");
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
                        $result = searchDir ( $file . '/' , $maxdepth , $mode , $d + 1 ) ;
                        $dirlist = array_merge ( $dirlist , $result ) ;
                    }
                }
            }
            closedir ( $handle ) ;
       }
       if ( $d == 0 ) { natcasesort ( $dirlist ) ; }
       $this->logger->info("searchDir return : ".print_r($dirlist,true));
       
       return ( $dirlist ) ;
    }
  
  public function informations(){
      return 'Coucou !! ';
  }
  
  public function getName(){
      return 'jlp_core.passerelle';
  }
}