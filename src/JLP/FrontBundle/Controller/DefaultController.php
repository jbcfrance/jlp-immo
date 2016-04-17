<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JLPFrontBundle:Default:index.html.twig');
    }
}
