<?php
// src/JLP/CoreBundle/DataFixtures/ORM/LoadTypeImage.php

namespace JLP\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JLP\CoreBundle\Entity\TypeImage;

class LoadTypeImage implements FixtureInterface
{
  public function load(ObjectManager $oManager)
  {  
    $aTypes = array(
        "Thumb" => array("width"=>180,"height"=>120),
        "Origin480p" => array("width"=>720,"height"=>483),
        "Origin720p" => array("width"=>1280,"height"=>720),
        "Origin1080p" => array("width"=>1920,"height"=>1080),
        "OriginFull" => array("width"=>3840,"height"=>2160)
    );

    foreach ($aTypes as $sType => $aInfoType) {
      $oTypeImage = new TypeImage();
      $oTypeImage->setType($sType);
      $oTypeImage->setWidth($aInfoType['width']);
      $oTypeImage->setHeight($aInfoType['height']);
      $oManager->persist($oTypeImage);
    }
    $oManager->flush();
  }
}