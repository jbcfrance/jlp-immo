<?php

namespace JLP\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class PasserelleController extends Controller
{

    public function indexAction()
    {
        $user = $this->getUser();
        

        return $this->render('JLPAdminBundle:Passerelle:index.html.twig');
    }
}
