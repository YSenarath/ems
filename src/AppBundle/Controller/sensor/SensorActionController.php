<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 7:53 PM
 */

namespace AppBundle\Controller\sensor;


use AppBundle\Controller\location\LocationController;
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
        return $this->render('AppBundle:sensor:findSensor.html.twig');
    }

    /**
     * @Route("/sensor/add", name="add_sensor")
     */
    public function addSensorAction(Request $request)
    {

        $sensor = new Sensor();
        $sensor->setInsDate((new \DateTime('today')));

        $connection = $this->get('database_connection');

        $modelController = new ModelController($connection);
        $typeController = new TypeController($connection);
        $locationController = new LocationController($connection);

        $models = $modelController->getAllModelNames();
        $types = $typeController->getAllTypeNames();
        $locations = $locationController ->getAllLocationNames();

        // build the form
        $form = $this->createForm(SensorType::class, $sensor ,
            array(
                'models' => $models,
                'types' => $types,
                'locations' => $locations
            ));


        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            //add sensor
            $sensorController = new SensorController($connection);

            if (!$sensorController->searchSensor($sensor->getSensorId())) {
                if ($sensor->getTMax() > $sensor->getTMin()){
                    $sensorController->sensorAddAction($sensor);
                }else{
                    printf("TMax <= TMin");
                    return $this->redirectToRoute('add_sensor');
                }

            } else{
                //---------------------------------------
                //------implement later---------------
                printf("The Sensor ID exists");
                return $this->redirectToRoute('find_sensor');
            }
            return $this->redirectToRoute('sensor_list');
        }

        return $this->render(
            'AppBundle:sensor:addSensor.html.twig',
            array('form' => $form->createView())
        );
    }
}