<?php

namespace JLP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class PrgNeufsController extends Controller
{

    public function indexAction()
    {

        

        return $this->render('JLPAdminBundle:PrgNeufs:index.html.twig');
    }

    public function addAction()
    {

        

        return $this->render('JLPAdminBundle:PrgNeufs:add.html.twig');
    }
}
