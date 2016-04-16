<?php

namespace JLP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class DefaultController extends Controller
{

    public function indexAction()
    {
        
        

        return $this->render('JLPAdminBundle:Default:index.html.twig');
    }
}
