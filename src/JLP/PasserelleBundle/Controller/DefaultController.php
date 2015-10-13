<?php

namespace JLP\PasserelleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('JLPPasserelleBundle:Default:index.html.twig', array('name' => $name));
    }
}
