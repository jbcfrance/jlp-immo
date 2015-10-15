<?php
// src/JLP/CoreBundle/DataFixtures/ORM/LoadTypeBien.php

namespace JLP\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JLP\CoreBundle\Entity\TypeBien;

class LoadTypeBien implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $types = array(
      'appartement',
      'maison',
      'terrain',
      'commerce'
    );

    foreach ($types as $type) {
      $typeBien = new TypeBien();
      $typeBien->setType($type);
      $manager->persist($typeBien);
    }
    $manager->flush();
  }
}