<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 7:53 PM
 */

namespace AppBundle\Controller\sensor;

use AppBundle\Entity\sensor\Sensor;
use AppBundle\Form\sensor\SensorType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\security\EmployeeController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
    public function findAction()
    {
        return $this->render('AppBundle:sensor:findSensor.htlm.twig');
    }

    /**
     * @Route("/sensor/add", name="add_sensor")
     */
    public function addSensorAction(Request $request)
    {

        $sensor = new Sensor();
        $sensor->setInsDate((new \DateTime('today')));

        // build the form
        $form = $this->createForm(SensorType::class, $sensor);

        //Handle submission (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isValid() && $form->isSubmitted()) {

            //add sensor
            $connection = $this->get('database_connection');
            $sensorController = new SensorController($connection);
            if (!$sensorController->searchSensor($sensor->getSensorId())) {
                    $sensorController->sensorAddAction($sensor);
            } else{
                //---------------------------------------
                //------implement later---------------
                print_r("The Sensor ID exists");
                return $this->redirectToRoute('find_sensor');
                }

        }

        return $this->render(
            'AppBundle:sensor:addSensor.htlm.twig',
            array('form' => $form->createView())
        );
    }
}