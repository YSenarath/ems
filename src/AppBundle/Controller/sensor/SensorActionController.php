<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 7:53 PM
 */

namespace AppBundle\Controller\sensor;

use AppBundle\Entity\sensor\Sensor;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SensorActionController extends  Controller
{

    /**
     * @Route("/sensor/list", name="sensor_list")
     */
    public function areaAction()
    {
        $sensors[] = new Sensor();

        $connection = $this->get('database_connection');
        $sensorController = new SensorController($connection);
        $sensors=$sensorController ->getAllSensors();

        return $this->render('AppBundle:sensor:sensorList.html.twig', array('sensors' => $sensors));
    }

    /**
     * @Route("/sensor/find", name="find_sensor")
     */
    public function summeryAction()
    {
        return $this->render('AppBundle:sensor:findSensor.htlm.twig');
    }
}