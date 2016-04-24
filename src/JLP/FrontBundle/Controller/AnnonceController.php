<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AnnonceController extends Controller
{
    public function indexAction($annonceId)
    {
        $annonce = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\Annonce')->find($annonceId);

        return $this->render('JLPFrontBundle:Annonce:index.html.twig', array('annonce'=>$annonce));
    }

    public function testAction()
    {
        $annonces = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\Annonce')->findAll();

        return $this->render('JLPFrontBundle:Annonce:test.html.twig', array('annonces'=>$annonces));
    }

    public function typeBienAction($typeBienId)
    {

        $typeBien = $this->getDoctrine()->getRepository('JLP\CoreBundle\Entity\TypeBien')->find($typeBienId);

        $annonces = $this->getDoctrine()->getRepository('JLP\CoreBundle\Entity\Annonce')->findByTypeBien($typeBien);

        return $this->render('JLPFrontBundle:Annonce:listing.html.twig', array( 'annonces'=>$annonces));

    }

    public function searchAction($searchString)
    {
        $annonce = null;

        if (true === is_int($searchString)) {
            $annonce = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\Annonce')->findOneBy(array('reference'=>$searchString));
        } elseif (preg_match('/type(?<type_id>[1-9]*\Z)/', $searchString, $aMatches)) {

            $typeBien = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\TypeBien')->findOneBy(array('id'=>$aMatches['type_id']));
            
            if (false === is_null($typeBien)) {
                $annonce = $this->getDoctrine()->getManager()->getRepository('JLP\CoreBundle\Entity\Annonce')->findBy(array('typeBien'=>$typeBien->getId()));
            }
        }
       
        return $this->render('JLPFrontBundle:Annonce:index.html.twig', array('annonces'=>$annonce));
    }
}
