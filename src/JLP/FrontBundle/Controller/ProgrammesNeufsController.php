<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class ProgrammesNeufsController extends Controller
{
    public function indexAction()
    {
        $typesBiens = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\TypeBien')->findAll();

        return $this->render('JLPFrontBundle:ProgrammesNeufs:index.html.twig', array('typesBiens' => $typesBiens));
    }

}
