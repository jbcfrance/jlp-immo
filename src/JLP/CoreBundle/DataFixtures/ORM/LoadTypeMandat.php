<?php
// src/JLP/CoreBundle/DataFixtures/ORM/LoadTypeMandat.php

namespace JLP\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JLP\CoreBundle\Entity\TypeMandat;

class LoadTypeMandat implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    $types = array(
      'simple',
      'exclusif',
      'terrain',
      'commerce'
    );

    foreach ($types as $type) {
      $typeMandat = new TypeMandat();
      $typeMandat->setType($type);
      $manager->persist($typeMandat);
    }
    $manager->flush();
  }
}