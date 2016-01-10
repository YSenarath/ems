<?php

namespace AppBundle\Controller\reports;

use AppBundle\Controller\location;
use AppBundle\Controller\location\AreaController;
use AppBundle\Controller\location\LocationController;
use AppBundle\Controller\sensor\SensorController;
use AppBundle\Entity\report\AirQlyReading;
use AppBundle\Entity\report\Area;
use AppBundle\Entity\report\HumidityReading;
use AppBundle\Entity\report\LocationEntity;
use AppBundle\Entity\report\PressureReading;
use AppBundle\Entity\report\SensorReading;
use AppBundle\Entity\report\SensorReadingSearcher;
use AppBundle\Entity\report\TempReading;
use AppBundle\Entity\report\WindReading;
use AppBundle\Entity\sensor\Sensor;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\LineChart;
use DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
                case "Temperature"://temp
                    $lastReading = ($tempController->tempLastReadingSearchAction($sensorDetail->getSensorId()));
                    /* @var $lastReading TempReading */
                    if ($lastReading != null) {
                        $areaTemp += $lastReading->getTempValue();
                    }
                    break;
                case "Air Quality"://air
                    $lastReading = $airController->airQtlLastReadingSearchAction($sensorDetail->getSensorId());

                    /* @var $lastReading AirQlyReading */
                    if ($lastReading != null) {
                        $areaAirQty += $lastReading->getAirQtyPercentage();
                        $areaAirO2 += $lastReading->getOxygenPercentage();
                        $areaAirCo2 += $lastReading->getCo2Percentage();
                    }
                    break;
                case  "Humidity"://humidity
                    $lastReading = $humidityController->humidityLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading HumidityReading */
                    if ($lastReading != null) {
                        $areaHumidity += $lastReading->getHumidityValue();
                    }
                    break;
                case "Pressure"://pressure
                    $lastReading = $pressureController->pressureLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading PressureReading */
                    if ($lastReading != null) {
                        $areaPressure += $lastReading->getPressureValue();
                    }
                    break;
                case "Wind"://wind
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

        $sensorArray = $sensorController->getSensorDetailsByLocationAction($locationDetail->getLocationId());

        $locationHumidity = 0;
        $locationTemp = 0;
        $locationWindSpeed = 0;
        $locationWindDirection = 0;
        $locationPressure = 0;

        $locationAirQty = 0;
        $locationAirO2 = 0;
        $locationAirCo2 = 0;

        foreach ($sensorArray as $sensorDetail) {
            /* @var $sensorDetail Sensor */
            // print_r($sensorDetail);
            switch ($sensorDetail->getTypeName()) {
                case "Temperature"://temp
                    $lastReading = ($tempController->tempLastReadingSearchAction($sensorDetail->getSensorId()));
                    /* @var $lastReading TempReading */
                    if ($lastReading != null) {
                        $locationTemp = $lastReading->getTempValue();
                    }
                    break;
                case "Air Quality"://air
                    $lastReading = $airController->airQtlLastReadingSearchAction($sensorDetail->getSensorId());

                    /* @var $lastReading AirQlyReading */
                    if ($lastReading != null) {
                        $locationAirQty = $lastReading->getAirQtyPercentage();
                        $locationAirO2 = $lastReading->getOxygenPercentage();
                        $locationAirCo2 = $lastReading->getCo2Percentage();
                    }
                    break;
                case  "Humidity"://humidity
                    $lastReading = $humidityController->humidityLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading HumidityReading */
                    if ($lastReading != null) {
                        $locationHumidity = $lastReading->getHumidityValue();
                    }
                    break;
                case "Pressure"://pressure
                    $lastReading = $pressureController->pressureLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading PressureReading */
                    if ($lastReading != null) {
                        $locationPressure = $lastReading->getPressureValue();
                    }
                    break;
                case "Wind"://wind
                    $lastReading = $windController->windLastReadingSearchAction($sensorDetail->getSensorId());
                    /* @var $lastReading WindReading */
                    if ($lastReading != null) {
                        $locationWindSpeed = $lastReading->getWindSpeed();
                        $locationWindDirection = $lastReading->getDirection();
                    }
                    break;
            }
        }

        $sensorDetailArray = $sensorController->getSensorsByLocationAction($locationDetail->getLocationId());

        $locationTemp = round($locationTemp, 2);
        $locationHumidity = round($locationHumidity , 2);
        $locationPressure = round($locationPressure  / 1000, 2);

        $locationWindSpeed = round($locationWindSpeed, 2);
        $locationWindDirection = round($locationWindDirection, 2);

        $locationAirQty = round($locationAirQty, 2);
        $locationAirO2 = round($locationAirO2 , 2);
        $locationAirCo2 = round($locationAirCo2, 4);

        //print_r($sensorArray);

        return $this->render(
            '@App/reports/locationView.html.twig',
            array(
                'areaName' => $viewArea,
                'location' => $locationDetail,
                'sensorDetails' => $sensorDetailArray,
                'meanTemp' => $locationTemp,
                'meanHumidity' => $locationHumidity,
                'meanPressure' => $locationPressure,
                'meanWind' => array('speed' => $locationWindSpeed, 'direction' => $locationWindDirection),
                'meanAir' => array('airQly' => $locationAirQty, 'O2' => $locationAirO2, 'CO2' => $locationAirCo2),
            )
        );
    }

    /**
     * @Route("/reports/view_sensor_readings", name="reportSensorReadings")
     */
    public function reportSensorReadingsAction(Request $request)
    {

        $sensorId = $request->query->get('id');

        $connection = $this->get('database_connection');

        $sensorController = new SensorController($connection);
        $sensor = $sensorController->searchSensor($sensorId);

        $tempController = new TempReadingController($connection);
        $humidityController = new HumidityReadingController($connection);
        $pressureController = new PressureReadingController($connection);
        $windController = new WindReadingController($connection);
        $airController = new AirQlyReadingController($connection);


        $startDate = date('Y-m-d', strtotime('01/01/2010'));
        $endDate = date('Y-m-d');

        $noReadings = false;

        /* @var $sensor Sensor */
        // print_r($sensorDetail);
        switch ($sensor->getTypeName()) {
            case "Temperature"://temp
                $firstReading = ($tempController->tempFirstReadingSearchAction($sensor->getSensorId()));
                $lastReading = ($tempController->tempLastReadingSearchAction($sensor->getSensorId()));
                /* @var $firstReading TempReading */
                /* @var $lastReading TempReading */

                if ($firstReading == null or $lastReading == null) {
                    $noReadings = true;
                } else {
                    //print_r($firstReading->getTimestamp());
                    $startDate = date('Y-m-d H:i:s', strtotime($firstReading->getTimestamp()));
                    $endDate = date('Y-m-d H:i:s', strtotime($lastReading->getTimestamp()));
                }
                break;
            case "Air Quality"://air

                $firstReading = $airController->airQtlFirstReadingSearchAction($sensor->getSensorId());
                $lastReading = $airController->airQtlLastReadingSearchAction($sensor->getSensorId());
                //print_r($firstReading->getTimestamp());
                /* @var $firstReading AirQlyReading */
                /* @var $lastReading AirQlyReading */
                // print_r($firstReading);
                if ($firstReading == null or $lastReading == null) {
                    $noReadings = true;
                } else {
                    //print_r($firstReading->getTimestamp());
                    $startDate = date('Y-m-d H:i:s', strtotime($firstReading->getTimestamp()));
                    $endDate = date('Y-m-d H:i:s', strtotime($lastReading->getTimestamp()));
                }
                break;
            case  "Humidity"://humidity
                $firstReading = $humidityController->humidityFirstReadingSearchAction($sensor->getSensorId());
                $lastReading = $humidityController->humidityLastReadingSearchAction($sensor->getSensorId());
                /* @var $firstReading HumidityReading */
                /* @var $lastReading HumidityReading */
                //print_r($firstReading->getTimestamp());
                if ($firstReading == null or $lastReading == null) {
                    $noReadings = true;
                } else {
                    //print_r($firstReading->getTimestamp());
                    $startDate = date('Y-m-d H:i:s', strtotime($firstReading->getTimestamp()));
                    $endDate = date('Y-m-d H:i:s', strtotime($lastReading->getTimestamp()));
                }
                break;
            case "Pressure"://pressure
                $firstReading = $pressureController->pressureFirstReadingSearchAction($sensor->getSensorId());
                $lastReading = $pressureController->pressureLastReadingSearchAction($sensor->getSensorId());
                /* @var $firstReading PressureReading */
                /* @var $lastReading PressureReading */
                //print_r($firstReading->getTimestamp());
                if ($firstReading == null or $lastReading == null) {
                    $noReadings = true;
                } else {
                    //print_r($firstReading->getTimestamp());
                    $startDate = date('Y-m-d H:i:s', strtotime($firstReading->getTimestamp()));
                    $endDate = date('Y-m-d H:i:s', strtotime($lastReading->getTimestamp()));
                }
                break;
            case "Wind"://wind
                $firstReading = $windController->windFirstReadingSearchAction($sensor->getSensorId());
                $lastReading = $windController->windLastReadingSearchAction($sensor->getSensorId());
                /* @var $firstReading WindReading */
                /* @var $lastReading WindReading */
                // print_r($firstReading->getTimestamp());
                if ($firstReading == null or $lastReading == null) {
                    $noReadings = true;
                } else {
                    //print_r($firstReading->getTimestamp());
                    $startDate = date('Y-m-d H:i:s', strtotime($firstReading->getTimestamp()));
                    $endDate = date('Y-m-d H:i:s', strtotime($lastReading->getTimestamp()));
                }
                break;
        }

        if (!$noReadings) {
            //Create search form
            $srs = new SensorReadingSearcher();
            $srs->setNoOfReadings(50);
            $srs->setEndDate(new DateTime());
            // 'attr' => array(
            $form = $this->createFormBuilder($srs)
                ->add(
                    'noOfReadings',
                    IntegerType::class,
                    array('attr' => array('min' => 1, 'max' => 1000))
                )
                ->add(
                    'startDate',
                    DateTimeType ::class,
                    array(
                        'input' => 'datetime',
//                    'widget' => 'choice',
                        'date_widget' => "choice",
                        'time_widget' => "choice",
                        'years' => range(date('Y', strtotime($startDate)), date('Y', strtotime($endDate))),
                    )
                )
                ->add(
                    'endDate',
                    DateTimeType::class,
                    array(
                        'input' => 'datetime',
//                    'widget' => 'choice',
                        'date_widget' => "choice",
                        'time_widget' => "choice",
                        'years' => range(date('Y', strtotime($startDate)), date('Y', strtotime($endDate))),
                    )
                )
                ->add('Show', SubmitType::class, array('label' => 'Show'))
                ->getForm();

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $readingLimit = $form["noOfReadings"]->getData();
                $startDate = $form["startDate"]->getData();
                $endDate = $form["endDate"]->getData();

//            /* @var $startDate \DateTime */
//            /* @var $endDate \DateTime */

                $readings = array();
                /* @var $readings SensorReading[] */
                switch ($sensor->getTypeName()) {
                    case "Temperature"://temp
                        $readings = $tempController->tempReadingsSearchAction(
                            $sensor->getSensorId(),
                            $readingLimit,
                            $startDate->format("Y-m-d H:i:s"),
                            $endDate->format("Y-m-d H:i:s")
                        );
                        /* @var $readings TempReading[] */
                        break;
                    case "Air Quality"://air
                        $readings = $airController->airQtlReadingsSearchAction(
                            $sensor->getSensorId(),
                            $readingLimit,
                            $startDate->format("Y-m-d H:i:s"),
                            $endDate->format("Y-m-d H:i:s")
                        );
                        /* @var $readings AirQlyReading[] */

                        break;
                    case  "Humidity"://humidity
                        $readings = $humidityController->humidityReadingsSearchAction(
                            $sensor->getSensorId(),
                            $readingLimit,
                            $startDate->format("Y-m-d H:i:s"),
                            $endDate->format("Y-m-d H:i:s")
                        );
                        /* @var $readings HumidityReading[] */

                        break;
                    case "Pressure"://pressure
                        $readings = $pressureController->pressureReadingsSearchAction(
                            $sensor->getSensorId(),
                            $readingLimit,
                            $startDate->format("Y-m-d H:i:s"),
                            $endDate->format("Y-m-d H:i:s")
                        );
                        /* @var $readings PressureReading[] */

                        break;
                    case "Wind"://wind
                        $readings = $windController->windReadingsSearchAction(
                            $sensor->getSensorId(),
                            $readingLimit,
                            $startDate->format("Y-m-d H:i:s"),
                            $endDate->format("Y-m-d H:i:s")
                        );
                        /* @var $readings WindReading[] */
                        break;
                }
                // print_r($readings);


                //google graphs
                //
                $lineChart = new LineChart();
                switch ($sensor->getTypeName()) {
                    case "Temperature"://temp
                        /* @var $readings TempReading[] */
                        $arr = array();
                        $arr[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'temperature (°C)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr[] = array($reading->getTimestamp(), $reading->getTempValue());
                        }

                        $lineChart->getData()->setArrayToDataTable($arr);

                        break;
                    case "Air Quality"://air
                        /* @var $readings AirQlyReading[] */
                        $arr = array();
                        $arr[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Air Quality (%)', 'type' => 'number'),
                            array('label' => 'CO2 (%)', 'type' => 'number'),
                            array('label' => 'Oxygen (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr[] = array(
                                $reading->getTimestamp(),
                                $reading->getAirQtyPercentage(),
                                $reading->getCo2Percentage(),
                                $reading->getOxygenPercentage(),
                            );
                        }

                        $lineChart->getData()->setArrayToDataTable($arr);
                        break;
                    case  "Humidity"://humidity
                        /* @var $readings HumidityReading[] */
                        $arr = array();
                        $arr[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'humidity (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr[] = array($reading->getTimestamp(), $reading->getHumidityValue());
                        }

                        $lineChart->getData()->setArrayToDataTable($arr);
                        break;
                    case "Pressure"://pressure
                        /* @var $readings PressureReading[] */
                        $arr = array();
                        $arr[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'pressure (KPa)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr[] = array($reading->getTimestamp(), $reading->getPressureValue() / 1000);
                        }

                        $lineChart->getData()->setArrayToDataTable($arr);
                        break;
                    case "Wind"://wind
                        /* @var $readings WindReading[] */
                        $arr = array();
                        $arr[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Wind speed (m/s)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr[] = array($reading->getTimestamp(), $reading->getWindSpeed());
                        }

                        $lineChart->getData()->setArrayToDataTable($arr);
                        break;
                }

                $lineChart->getOptions()->getChartArea()->setTop(50);
                //$lineChart->getOptions()->getChartArea()->setLeft(50);
                //$lineChart->getOptions()->setEnableInteractivity(true);
                $lineChart->getOptions()->setCurveType('function');

                $lineChart->getOptions()->setHeight(700);
                $lineChart->getOptions()->setWidth(1500);

                $lineChart->getOptions()->getHAxis()->setSlantedTextAngle(90);
                $lineChart->getOptions()->getHAxis()->setSlantedText(true);
                $lineChart->getOptions()->getHAxis()->setShowTextEvery(1);

                // $lineChart->getOptions()->getHAxis()->getTextStyle()->setColor('#FF5722');
                $lineChart->getOptions()->getVAxis()->getTextStyle()->setColor('#2196F3');
                $lineChart->getOptions()->getVAxis()->getTextStyle()->setBold(true);

                $lineChart->getOptions()->getTooltip()->setIsHtml(true);
                $lineChart->getOptions()->getTooltip()->setIgnoreBounds(true);


                //end of charts


                return $this->render(
                    '@App/reports/sensorReadings.html.twig',
                    array(
                        'sensor' => $sensor,
                        'readings' => $readings,
                        'lineChart' => $lineChart,
                    )
                );


            }//end of form submission

            //print_r($sensorArray);

            return $this->render(
                '@App/reports/sensorReadingSearch.html.twig',
                array(
                    'sensor' => $sensor,
                    'form' => $form->createView(),
                )
            );
        } else {
            return $this->render(
                '@App/reports/sensorReadingSearch.html.twig',
                array(
                    'sensor' => $sensor,
                )
            );
        }


    }
}