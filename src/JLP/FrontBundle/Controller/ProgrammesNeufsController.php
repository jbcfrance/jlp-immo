<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ProgrammesNeufsController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('JLPFrontBundle:ProgrammesNeufs:index.html.twig');
    }

}
