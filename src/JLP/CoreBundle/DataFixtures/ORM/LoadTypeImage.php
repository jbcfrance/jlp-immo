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
        "Thumb" => array("width"=>180, "height"=>120, "dir"=>"thumb"),
        "Sd" => array("width"=>720, "height"=>483, "dir"=>"sd"),
        "Coeur" => array("width"=>1280, "height"=>720, "dir"=>"coeur"),
        "Hd" => array("width"=>1920, "height"=>1080, "dir"=>"hd")
        );

    foreach ($aTypes as $sType => $aInfoType) {
        $oTypeImage = new TypeImage();
        $oTypeImage->setType($sType);
        $oTypeImage->setWidth($aInfoType['width']);
        $oTypeImage->setHeight($aInfoType['height']);
        $oTypeImage->setDir($aInfoType['dir']);
        $oManager->persist($oTypeImage);
    }
    $oManager->flush();
    }
}