<?php
namespace EmsBundle\Controller;

// use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EmsController extends Controller
{
    public function listAction()
    {
        // return new Response("Welcome to Blog List");
        return $this->render('EmsBundle:Ems:list.html.twig');
    }
}