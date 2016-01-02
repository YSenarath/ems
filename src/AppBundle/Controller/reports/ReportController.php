<?php

namespace AppBundle\Controller\reports;

use AppBundle\Controller\location;
use AppBundle\Controller\location\LocationController;
use AppBundle\Controller\sensor\SensorController;
use AppBundle\Entity\report\AirQlyReading;
use AppBundle\Entity\report\Area;
use AppBundle\Entity\report\HumidityReading;
use AppBundle\Entity\report\LocationEntity;
use AppBundle\Entity\report\PressureReading;
use AppBundle\Entity\report\SensorReadingSearcher;
use AppBundle\Entity\report\TempReading;
use AppBundle\Entity\report\WindReading;
use AppBundle\Entity\sensor\Sensor;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;


class ReportController extends Controller
{
    /**
     * @Route("/reports/areas", name="reportAreas")
     */
    public function reportAreaAction()
    {
        $areas[] = new Area();
        // save the User
        $connection = $this->get('database_connection');
        $areaController = new AreaController($connection);
        $areas = $areaController->getAllAreasAction();

        return $this->render('@App/reports/areas.html.twig', array('areas' => $areas));
    }

    /**
     * @Route("/reports/areas/{viewArea}", name="reportAreaView")
     */
    public function reportAreaViewAction($viewArea)
    {
        $connection = $this->get('database_connection');

        $areaController = new AreaController($connection);
        $locationController = new LocationController($connection);

        $sensorController = new SensorController($connection);
        $tempController = new TempReadingController($connection);
        $humidityController = new HumidityReadingController($connection);
        $pressureController = new PressureReadingController($connection);
        $windController = new WindReadingController($connection);
        $airController = new AirQlyReadingController($connection);

        $area = $areaController->getAreaDetailsAction($viewArea);
        //print_r($area);
        if (!$area) {
            return $this->render(
                '@App/reports/areaView.html.twig',
                array(
                    'areaName' => $viewArea,
                    'error' => 'No such area',
                )
            );
        }

        $areaId = $area->getAreaCode();
        $locationArray = $locationController->getLocationDetailsByAreaAction($areaId);
        // print_r($locationArray);

        $noOfLocations = count($locationArray);
        if (!$locationArray) {
            return $this->render(
                '@App/reports/areaView.html.twig',
                array(
                    'areaName' => $viewArea,
                    'error' => 'No locations in the area',
                )
            );
        }

        $sensorArray = array();
        foreach ($locationArray as $location) {
            /* @var $location LocationEntity */
            //print_r($location[0]."<br>");
            $sensorArray = array_merge(
                $sensorArray,
                $sensorController->getSensorDetailsByLocationAction($location->getLocationId())
            );
        }
        //print_r($sensorArray);
        $areaHumidity = 0;
        $areaTemp = 0;
        $areaWindSpeed = 0;
        $areaWindDirection = 0;
        $areaPressure = 0;

        $areaAirQty = 0;
        $areaAirO2 = 0;
        $areaAirCo2 = 0;

        foreach ($sensorArray as $sensorDetail) {
            /* @var $sensorDetail Sensor */
            // print_r($sensorDetail);
            switch ($sensorDetail->getTypeName()) {
                case "temp"://temp
                    $lastReading = ($tempController->tempLastReadingSearchAction($sensorDetail->getSensorId()));
                    /* @var $lastReading TempReading */
                    if ($lastReading != null) {
                        $areaTemp += $lastReading->getTempValue();
                    }
                    break;
                case "air_qty"://air
                    $lastReading = $airController->airQtlLastReadingSearchAction($sensorDetail->getSensorId());

                    /* @var $lastReading AirQlyReading */
                    if ($lastReading != null) {
                        $areaAirQty += $lastReading->getAirQtyPercentage();
                        $areaAirO2 += $lastReading->getOxygenPercentage();
                        $areaAirCo2 += $lastReading->getCo2Percentage();
                    }
                    break;
                case  "humidity"://humidity
                    $lastReading = $humidityController->humidityLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading HumidityReading */
                    if ($lastReading != null) {
                        $areaHumidity += $lastReading->getHumidityValue();
                    }
                    break;
                case "pressure"://pressure
                    $lastReading = $pressureController->pressureLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading PressureReading */
                    if ($lastReading != null) {
                        $areaPressure += $lastReading->getPressureValue();
                    }
                    break;
                case "wind"://wind
                    $lastReading = $windController->windLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading WindReading */
                    if ($lastReading != null) {
                        $areaWindSpeed += $lastReading->getWindSpeed();
                        $areaWindDirection += $lastReading->getDirection();
                    }
                    break;
            }
        }

        $areaTemp = round($areaTemp / $noOfLocations, 2);
        $areaHumidity = round($areaHumidity / $noOfLocations, 2);
        $areaPressure = round($areaPressure / $noOfLocations / 1000, 2);

        $areaWindSpeed = round($areaWindSpeed / $noOfLocations, 2);
        $areaWindDirection = round($areaWindDirection / $noOfLocations, 2);

        $areaAirQty = round($areaAirQty / $noOfLocations, 2);
        $areaAirO2 = round($areaAirO2 / $noOfLocations, 2);
        $areaAirCo2 = round($areaAirCo2 / $noOfLocations, 4);


        // print_r($areaTemp);

        //Get air quality info
        //Get Humidity info
        //Get Pressure info
        //Get Temp info
        //Get wind info
        return $this->render(
            '@App/reports/areaView.html.twig',
            array(
                'areaName' => $viewArea,
                'area_longitude' => $area->getCenterLongitude(),
                'area_latitude' => $area->getCenterLatitude(),
                'noOfLocations' => $noOfLocations,
                'meanTemp' => $areaTemp,
                'meanHumidity' => $areaHumidity,
                'meanPressure' => $areaPressure,
                'meanWind' => array('speed' => $areaWindSpeed, 'direction' => $areaWindDirection),
                'meanAir' => array('airQly' => $areaAirQty, 'O2' => $areaAirO2, 'CO2' => $areaAirCo2),
                'locations' => $locationArray,
            )
        );
    }

    /**
     * @Route("/reports/areas/{viewArea}/{viewLocation}", name="reportLocationView")
     */
    public function reportLocationViewAction($viewArea, $viewLocation)
    {
        $connection = $this->get('database_connection');

        $locationController = new LocationController($connection);

        $sensorController = new SensorController($connection);
        $tempController = new TempReadingController($connection);
        $humidityController = new HumidityReadingController($connection);
        $pressureController = new PressureReadingController($connection);
        $windController = new WindReadingController($connection);
        $airController = new AirQlyReadingController($connection);

        $locationDetail = $locationController->getLocationDetailsAction($viewLocation);

        $sensorArray = $sensorController->getSensorsByLocationAction($locationDetail->getLocationId());

        //print_r($sensorArray);

        return $this->render(
            '@App/reports/locationView.html.twig',
            array(
                'areaName' => $viewArea,
                'location' => $locationDetail,
                'sensorDetail' => $sensorArray,
            )
        );
    }

    /**
     * @Route("/reports/areas/{viewArea}/{viewLocation}/{sensorId}", name="reportSensorReadings")
     */
    public function reportSensorReadingsAction(Request $request, $viewArea, $viewLocation, $sensorId)
    {
        $connection = $this->get('database_connection');

        $sensorController = new SensorController($connection);
        $sensor = $sensorController->searchSensor($sensorId);


        $srs = new SensorReadingSearcher();
        $srs->setReadingLimit(50);
        $srs->setEndDate(new \DateTime('today'));
       // 'attr' => array(
        $form = $this->createFormBuilder($srs)
            ->add('readingLimit', IntegerType::class, array('attr' => array('min' =>1, 'max' =>1000,'style' => 'width: 400px')))
            ->add(
                'startDate',
                DateType::class,
                array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'years' => range(2010, date('Y')),
                    'attr' => array('style' => 'width: 400px')
                )
            )
            ->add(
                'endDate',
                DateType::class,
                array(
                    'input' => 'datetime',
                    'widget' => 'choice',
                    'years' => range(2010, date('Y')),
                    'attr' => array('style' => 'width: 400px')
                )
            )
            ->add('Show', SubmitType::class, array('label' => 'Show'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $readingLimit = $form["readingLimit"]->getData();
            $startDate = $form["startDate"]->getData();
            $endDate = $form["endDate"]->getData();

            /* @var $startDate \DateTime */
            /* @var $endDate \DateTime */
            return $this->render(
                '@App/reports/sensorReadings.html.twig',
                array(
                    'sensor' => $sensor,
                    'readingLimit' => $readingLimit,
                    'startDate' => $startDate->format("Y-m-d"),
                    'endDate' => $endDate->format("Y-m-d"),
                )
            );
        }

        //print_r($sensorArray);

        return $this->render(
            '@App/reports/sensorReadingSearch.html.twig',
            array(
                'sensor' => $sensor,
                'form' => $form->createView(),
            )
        );
    }


    /**
     * @Route("/reports/summery", name="reportSummery")
     */
    public function reportSummeryAction()
    {
        return $this->render('@App/reports/summery.html.twig');
    }
}
