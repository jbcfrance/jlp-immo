<?php

namespace JLP\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ContactController extends Controller
{
    public function indexAction()
    {
        
        return $this->render('JLPFrontBundle:Contact:index.html.twig');
    }
    
    public function sendMailAction(Request $request)
    {

        echo $request->request->get('name');
        echo $request->request->get('email');
        echo $request->request->get('message');
        

        return $this->render('JLPFrontBundle:Contact:email.html.twig');
    }
}
