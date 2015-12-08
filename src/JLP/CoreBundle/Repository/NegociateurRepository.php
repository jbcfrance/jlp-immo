<?php

namespace JLP\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class NegociateurRepository extends EntityRepository
{
  public function getNegociateurWithoutAnnonce()
  {

    $qb = $this->createQueryBuilder('n')
            ->leftJoin('n.annonces', 'a')
            ->where('a.id is null');
      
    $aResult = $qb->getQuery()->getResult();
    
    return $aResult;
  }
  
}
