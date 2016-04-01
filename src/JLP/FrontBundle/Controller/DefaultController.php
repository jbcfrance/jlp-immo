<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        $typesBiens = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\TypeBien')->findAll();

        return $this->render('JLPFrontBundle:Default:index.html.twig', array('typesBiens' => $typesBiens));
    }
}
