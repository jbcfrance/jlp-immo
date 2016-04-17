<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ThemeController extends Controller
{
    public function menuAction()
    {
        $typesBiens = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\TypeBien')->findAll();

        return $this->render('JLPFrontBundle:Theme:menu.html.twig', array('typesBiens' => $typesBiens));
    }

    public function footerAction()
    {

        return $this->render('JLPFrontBundle:Theme:footer.html.twig');
    }
}
