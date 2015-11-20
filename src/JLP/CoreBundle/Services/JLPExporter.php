<?php
// src/JLP/CoreBundle/Services/JLPExporter.php

namespace JLP\CoreBundle\Services;

use JLP\CoreBundle\Entity\Annonce;
use Symfony\Component\Finder\Finder;
use Symfony\Component\DomCrawler\Crawler;


/*
 * Objectif : exporter un listing des annonces selon certain paramètres sous un format au choix : xml, json, csv 
 */

class JLPExporter
{
  
  private $oEm;
  
  public function __construct(EntityManagerInterface $oEm)
  {
      $this->oEm = $oEm;
  }
  
  public function setExportFormat(){
    
  }
  
  public function setParameters(){
    
  }
  
  public function export(){
    
  }
  
  public function getName(){
    return 'jlp_core.exporter';
  }
}
