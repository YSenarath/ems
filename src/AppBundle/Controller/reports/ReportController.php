<?php

namespace AppBundle\Controller\reports;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ReportController extends Controller
{
    /**
     * @Route("/reports/areas", name="reportAreas")
     */
    public function areaAction()
    {

        return $this->render('@App/reports/areas.html.twig');
    }

    /**
     * @Route("/reports/summery", name="reportSummery")
     */
    public function summeryAction()
    {
        return $this->render('@App/reports/summery.html.twig');
    }
}
