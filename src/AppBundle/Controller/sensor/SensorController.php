<?php

/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/10/2015
 * Time: 2:30 PM
 */
namespace AppBundle\Controller\sensor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SensorController extends  Controller{


    /**
     * @Route("/sensor", name="sensor_list")
     */
    public function sensorAction() {

        $sensors = $this-> getDoctrine()

            ->getRepository('AppBundle:sensor\Sensor')
            ->findAll();


        return $this->render('AppBundle:sensor:sensorList.html.twig', array('sensors'=>$sensors));
        ;
    }
}
