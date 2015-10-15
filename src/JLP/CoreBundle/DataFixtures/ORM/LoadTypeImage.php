<?php
// src/JLP/CoreBundle/DataFixtures/ORM/LoadTypeImage.php

namespace JLP\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JLP\CoreBundle\Entity\TypeImage;

class LoadTypeImage implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $types = array(
      'Thumb',
      'Origin',
      'OriginSD',
      'OriginHD',
      'Coeur'
    );

    foreach ($types as $type) {
      $typeImage = new TypeImage();
      $typeImage->setType($type);
      $manager->persist($typeImage);
    }
    $manager->flush();
  }
}