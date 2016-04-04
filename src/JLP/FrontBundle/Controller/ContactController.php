<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function indexAction()
    {
        $typesBiens = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\TypeBien')->findAll();

        return $this->render('JLPFrontBundle:Contact:index.html.twig', array('typesBiens' => $typesBiens));
    }
}
