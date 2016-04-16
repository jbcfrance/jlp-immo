<?php

namespace JLP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ParamController extends Controller
{

    public function indexAction()
    {
        


        return $this->render('JLPAdminBundle:Param:index.html.twig');
    }

    public function accueilAction()
    {
        


        return $this->render('JLPAdminBundle:Param:accueil.html.twig');
    }

    public function contactAction()
    {
        
        

        return $this->render('JLPAdminBundle:Param:contact.html.twig');
    }
}
