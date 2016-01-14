<?php

namespace AppBundle\Controller;

use AppBundle\Entity\sensor\SensorError;
use AppBundle\Controller\sensor\SensorErrorController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $connection = $this->get('database_connection');
        $errorReports = new SensorErrorController($connection);

        $errors = $errorReports->getTopErrors();

        return $this->render(
            'AppBundle::home.html.twig', array('errors' => $errors)
        );
    }

    /**
     * @Route("/about", name="about")
     */
    public function aboutAction()
    {
        return $this->render(
            'AppBundle::about.html.twig'
        );
    }
}
