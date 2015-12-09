<?php
// src/JLP/CoreBundle/EventListener/ImagesToolsSubscriber.php
namespace JLP\CoreBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
// for Doctrine 2.4: Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use JLP\CoreBundle\Entity\Images;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Imagine\Image\Box;
use Imagine\Gd\Imagine;
use Psr\Log\LoggerInterface;

class ImagesToolsSubscriber implements EventSubscriber
{
    private $logger;
    const   BUNDLE_IMAGE_DIR = "web/bundles/jlpcore/images/";
    
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
    
    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'preRemove',
        );
    }
    
    public function postPersist(LifecycleEventArgs $args)
    {
      
      $oEntity = $args->getEntity();
      $oEm = $args->getEntityManager();
      
      if ($oEntity instanceof Images) {  
        
        $aTypeImage = $oEm->getRepository('JLPCoreBundle:TypeImage')->findAll();
        $sImageName = $oEntity->getFilename();
        $this->logger->info("postPersist Images : ".$sImageName);
        $this->createImages($aTypeImage, $sImageName);
        unset($aTypeImage, $sImageName);
      }
    }

    public function createImages($aTypeImage, $sImageName)
    {
      foreach($aTypeImage as $oTypeImage) {
       $oImagine = new Imagine();
       $oImagine->open(self::BUNDLE_IMAGE_DIR."source/".$sImageName)
               ->resize(new Box($oTypeImage->getWidth(),$oTypeImage->getHeight()))
               ->save(self::BUNDLE_IMAGE_DIR.$oTypeImage->getDir().'/'.$sImageName, array('jpeg_quality' => 100));
      }
    }
    
    
    public function preRemove(LifecycleEventArgs $args)
    {
      $oEntity = $args->getEntity();
      $oEm = $args->getEntityManager();
      if ($oEntity instanceof Images && $oEntity->getId() === null) {
        $aTypeImage = $oEm->getRepository('JLPCoreBundle:TypeImage')->findAll();
        $sImageName = $oEntity->getFilename();
        $this->deleteImages($aTypeImage, $sImageName);
        unset($aTypeImage, $sImageName);
      }
    }
    
    public function deleteImages($aTypeImage, $sImageName)
    {
      foreach($aTypeImage as $oTypeImage){
        $sImageLink = self::BUNDLE_IMAGE_DIR.$oTypeImage->getDir()."/".$sImageName;
        $deleteImageProcess = new Process('rm '.$sImageLink);
        $deleteImageProcess->run();
        if (!$deleteImageProcess->isSuccessful()) {
          throw new ProcessFailedException($deleteImageProcess);
        }
        unset($deleteImageProcess,$sImageLink);
      }
    }
}
