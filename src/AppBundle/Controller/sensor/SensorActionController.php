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
use AppBundle\Entity\sensor\SensorError;
use AppBundle\Entity\sensor\SensorSearch;
use AppBundle\Entity\sensor\Type;
use AppBundle\Form\sensor\FindSensor;
use AppBundle\Form\sensor\SensorType;
use AppBundle\Form\sensor\ModelType;
use AppBundle\Form\sensor\ErrorType;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Controller\security\EmployeeController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormError;
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

        if ($sensorId == null){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Sensor ID is null'));
        }

        $sensorController = new SensorController($connection);
        $sensor=$sensorController ->getSensor($sensorId);

        if(!$sensor){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Sensor "'.$sensorId.'" not fount'));
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
     * @Route("/sensor/error/list", name="sensor_error_list")
     */
    public function listSensorErrorAction()
    {
        $sensorErrs[] = new SensorError();

        $connection = $this->get('database_connection');
        $sensorErrorController = new SensorErrorController($connection);
        $sensorErrs = $sensorErrorController ->getAllErrors();

        return $this->render('AppBundle:sensor:sensorErrorList.html.twig', array('sensors' => $sensorErrs));

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
        $sensor = new SensorSearch();
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

            if (sizeof($sensors)<=1){
                $this->get('session')->getFlashBag()->add('msg', 'No sensor found');
            }
            return $this->render('AppBundle:sensor:sensorList.html.twig', array('sensors' => $sensors));
        }
        return $this->render(
            'AppBundle:sensor:findSensor.html.twig',
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
        $form = $this->createForm(ModelType::class, $model ,
            array(
            'type'=> 'add'
        ));


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
                $form->get('model_id')->addError(new FormError('The Model already exists'));
                return $this->render(
                    'AppBundle:sensor:addModel.html.twig',
                    array('form' => $form->createView() , 'model'=>$model ,'title'=>'Add Model')
                );
            }
            return $this->redirectToRoute('model_list');
        }

        return $this->render(
            'AppBundle:sensor:addModel.html.twig',
            array('form' => $form->createView(), 'title'=>'Add Model')
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
                'locations' => $locations,
                'type'=> 'add'
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
                $form->get('sensor_id')->addError(new FormError('The Sensor ID already exists'));
                return $this->render(
                    'AppBundle:sensor:addSensor.html.twig',
                    array('form' => $form->createView() , 'sensor'=>$sensor ,'title'=>'Add Model' )
                );
            }
            return $this->redirectToRoute('viewSensor', array('id'=> $sensor->getSensorId()));
        }

        return $this->render(
            'AppBundle:sensor:addSensor.html.twig',
            array('form' => $form->createView() ,'title' => 'Add Sensor')
        );
    }

//    ----------update methods-----------------

    /**
     * @Route("/sensor/edit", name="edit_sensor")
     */
    public function editSensorAction(Request $request)
    {
        $connection = $this->get('database_connection');

        $sensorId = $request->query->get('id');
        if ($sensorId != null ){

            $sensorController = new SensorController($connection);

            $sensor=$sensorController ->searchSensor($sensorId);

            if (!$sensor){
                return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Sensor "'.$sensorId.'" is not found'));
            }

            $sensor->setInsDate((new \DateTime($sensor->getInsDate())));
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
                    'locations' => $locations,
                    'type'=> 'edit'
                ));


            //Handle submission (will only happen on POST)
            $form->handleRequest($request);

            if ($form->isValid() && $form->isSubmitted()) {

                if ($sensor->getTMax() > $sensor->getTMin()){
                    $sensorController->sensorUpdateAction($sensor);
                }else{
                    printf("TMax <= TMin");
                    return $this->redirectToRoute('add_sensor');
                }


                return $this->redirectToRoute('viewSensor', array('id'=> $sensor->getSensorId()));
            }

            return $this->render(
                'AppBundle:sensor:addSensor.html.twig',
                array('form' => $form->createView() , 'title' => 'Edit Sensor')
            );
        }
        else{

            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Sensor ID is null'));
        }

    }


    /**
     * @Route("/model/edit", name="edit_model")
     */
    public function editModelAction(Request $request)
    {


        $modelId = $request->query->get('id');

        if ($modelId == null){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Model ID is null'));
        }


        $connection = $this->get('database_connection');
        $modelController = new ModelController($connection);
        $model = $modelController->searchModel($modelId);

        if (!$model){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Model "'.$modelId.'" is not found'));

        }


        // build the form
        $form = $this->createForm(ModelType::class, $model,
        array(
            'type'=> 'edit'
        ));


        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

                $modelController->updateModelAction($model);

            return $this->redirectToRoute('model_list');
        }

        return $this->render(
            'AppBundle:sensor:addModel.html.twig',
            array('form' => $form->createView() , 'title'=>'Edit Model')
        );
    }


    /**
     * @Route("/sensor/error/set", name="set_error")
     */
    public function setErrorAction(Request $request)
    {

        $sensorId = $request->query->get('id1');
        $reportId = $request->query->get('id2');

        if ($sensorId == null){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Sensor ID is null'));
        }
        if ($reportId == null){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Report ID is null'));
        }


        $connection = $this->get('database_connection');
        $errorController = new SensorErrorController($connection);
        $error = $errorController->searchError($sensorId, $reportId);

        if (!$error){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Error data "'.$sensorId.'-'.$reportId.'" is not found'));

        }


        // build the form
        $form = $this->createForm(ErrorType::class, $error);


        //Handle submission (will only happen on POST)
        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {

            $errorController->updateErrorAction($error);

            return $this->redirectToRoute('sensor_error_list');
        }

        return $this->render(
            'AppBundle:sensor:setErrorInfo.Html.twig',
            array('form' => $form->createView() )
        );
    }


    /**
     * @Route("/type/update", name="edit_type")
     */
    public function editTypeAction(Request $request)
    {

        $typeId = $request->query->get('id');
        $range = $request->query->get('value');


        if ($typeId == null || $range == null){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Invalid values'));
        }


        $connection = $this->get('database_connection');
        $typeController = new TypeController($connection);
        $type = $typeController->searchType($typeId);
        $type->setResInterval($range);

        if (!$type){
            return $this->render('AppBundle:sensor:error.html.twig', array('msg' => 'Type "'.$typeId.'" is not found'));

        }

        if ($typeController->changeResponseTime($type)){
            $this->get('session')->getFlashBag()->add('msg', 'Sensor Type :'.$typeId.' response interval is changed to : '.$range);

        }else{
            $this->get('session')->getFlashBag()->add('msg', 'Changing the response interval to : '.$range.' of Type :'.$typeId.' is failed' );

        }

        return $this->redirectToRoute('type_list');

    }

    //--------------------delete methods-------------------//
    /**
     * @Route("/model/remove", name="remove_model")
     */
    public function deleteSensorModelAction(Request $request)
    {

        $modelId = $request->query->get('id');

        $connection = $this->get('database_connection');
        $modelController = new ModelController($connection);

        if ($modelController->removeModel($modelId)){

            $this->get('session')->getFlashBag()->add('msg', 'Sensor Model : '.$modelId.' Delete Succeed');
        }
        else{
            $this->get('session')->getFlashBag()->add('msg', 'Sensor Model : '.$modelId.' Delete Failed (Warning - can not remove model having registered sensors)');
        }

        return $this->redirectToRoute('model_list');
    }

    /**
     * @Route("/sensor/remove", name="remove_sensor")
     */
    public function deleteSensorAction(Request $request)
    {

        $sensorId = $request->query->get('id');

        $connection = $this->get('database_connection');
        $sensorController = new SensorController($connection);

        if ($sensorController->removeSensor($sensorId)){

            $this->get('session')->getFlashBag()->add('msg', 'Sensor  : '.$sensorId.' Delete Succeed');
            return $this->redirectToRoute('sensor_list');

        }
        else{
            $this->get('session')->getFlashBag()->add('msg', 'Sensor : '.$sensorId.' Delete Failed (Warning - can not remove sensors having sensor readings)');
            if (!$sensorController->searchSensor($sensorId)) {
                return $this->redirectToRoute('sensor_list');
            }
            return $this->redirectToRoute('viewSensor', array('id' => $sensorId));
        }
    }

}