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
use AppBundle\Entity\sensor\Model;
use AppBundle\Entity\sensor\Type;
use AppBundle\Form\sensor\FindSensor;
use AppBundle\Form\sensor\SensorType;
use AppBundle\Form\sensor\ModelType;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\security\EmployeeController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class SensorActionController extends  Controller
{

    /**
     * @Route("/sensor/view", name="viewSensor")
     * @param Request $request
     * @return Response|void
     */
    public function viewSensorAction(Request $request)
    {

        $connection = $this->get('database_connection');
        $sensorId = $request->query->get('id');
        $sensorController = new SensorController($connection);
        $sensor=$sensorController ->searchSensor($sensorId);

        if(!$sensor){
            return $this->render('AppBundle:sensor:sensorNotFound.html.twig');
        }else{
            return $this->render('AppBundle:sensor:viewSensor.html.twig', array('sensor' => $sensor));
        }
    }

    /**
     * @Route("/sensor/list", name="sensor_list")
     */
    public function listSensorAction()
    {
        $sensors[] = new Sensor();

        $connection = $this->get('database_connection');
        $sensorController = new SensorController($connection);
        $sensors=$sensorController ->getAllSensors();

        return $this->render('AppBundle:sensor:sensorList.html.twig', array('sensors' => $sensors));

    }


    /**
     * @Route("/model/list", name="model_list")
     */
    public function listModelAction()
    {
        $models[] = new Model();

        $connection = $this->get('database_connection');
        $modelController = new ModelController($connection);
        $models=$modelController->getAllModels();

        return $this->render('AppBundle:sensor:modelList.html.twig', array('models' => $models));
    }

    /**
     * @Route("/type/list", name="type_list")
     */
    public function listTypesAction()
    {
        $types[] = new Type();

        $connection = $this->get('database_connection');
        $typeController = new TypeController($connection);
        $types=$typeController->getAllTypes();

        //print_r($types);
        return $this->render('AppBundle:sensor:typeList.html.twig', array('types' => $types));
    }

    /**
     * @Route("/sensor/find", name="find_sensor")
     */
    public function findAction(Request $request)
    {
        $sensor = new Sensor();
        $sensor->setInsDate((new \DateTime('today')));
        $sensor->setInsBefore((new \DateTime('today')));

        $connection = $this->get('database_connection');

        $modelController = new ModelController($connection);
        $typeController = new TypeController($connection);
        $locationController = new LocationController($connection);

        $models = $modelController->getAllModelNames();
        $types = $typeController->getAllTypeNames();
        $locations = $locationController ->getAllLocationNames();

        // build the form
        $form = $this->createForm(FindSensor::class, $sensor ,
            array(
                'models' => $models,
                'types' => $types,
                'locations' => $locations
            ));


        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            //find sensors
            $sensors[] = new Sensor();

            $sensorController = new SensorController($connection);
            $sensors = $sensorController->findSensors($sensor);

            return $this->render('AppBundle:sensor:sensorList.html.twig', array('sensors' => $sensors));
        }
        return $this->render(
            'AppBundle:sensor:findSensor.html.twig',
            array('form' => $form->createView())
        );

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

    /**
     * @Route("/model/add", name="add_model")
     */
    public function addModelAction(Request $request)
    {

        $model = new Model();

        // build the form
        $form = $this->createForm(ModelType::class, $model );


        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            //add model
            $connection = $this->get('database_connection');
            $modelController = new ModelController($connection);

            if (!$modelController->searchModel($model->getModelId())) {
                    $modelController->addModelAction($model);
            } else{
                //---------------------------------------
                //------implement later---------------
                printf("The Model ID exists");
                return $this->redirectToRoute('add_model');
            }
            return $this->redirectToRoute('model_list');
        }

        return $this->render(
            'AppBundle:sensor:addModel.html.twig',
            array('form' => $form->createView())
        );
    }
}