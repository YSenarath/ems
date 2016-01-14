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
        $locationHumidity = round($locationHumidity, 2);
        $locationPressure = round($locationPressure / 1000, 2);

        $locationWindSpeed = round($locationWindSpeed, 2);
        $locationWindDirection = round($locationWindDirection, 2);

        $locationAirQty = round($locationAirQty, 2);
        $locationAirO2 = round($locationAirO2, 2);
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
        $sensor = $sensorController->searchReportSensor($sensorId);

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
                $lineChart_generic = new LineChart();
                $lineChart_airQly = null;
                $lineChart_airOxygen = null;
                $lineChart_airCO2 = null;

                $lineChart_windDir = null;

                switch ($sensor->getTypeName()) {
                    case "Temperature"://temp
                        /* @var $readings TempReading[] */
                        $arr_generic = array();
                        $arr_generic[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'temperature (°C)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_generic[] = array($reading->getTimestamp(), $reading->getTempValue());
                        }

                        $lineChart_generic->getData()->setArrayToDataTable($arr_generic);

                        break;
                    case "Air Quality"://air
                        /* @var $readings AirQlyReading[] */

                        //All
                        $arr_generic = array();
                        $arr_generic[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Air Quality (%)', 'type' => 'number'),
                            array('label' => 'CO2 (%)', 'type' => 'number'),
                            array('label' => 'Oxygen (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_generic[] = array(
                                $reading->getTimestamp(),
                                $reading->getAirQtyPercentage(),
                                $reading->getCo2Percentage(),
                                $reading->getOxygenPercentage(),
                            );
                        }
                        $lineChart_generic->getData()->setArrayToDataTable($arr_generic);

                        //Air Quality
                        $arr_airQly = array();
                        $arr_airQly[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Air Quality (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_airQly[] = array(
                                $reading->getTimestamp(),
                                $reading->getAirQtyPercentage(),
                            );
                        }
                        $lineChart_airQly = new LineChart();
                        $lineChart_airQly->getData()->setArrayToDataTable($arr_airQly);

                        //CO2
                        $arr_airCO2 = array();
                        $arr_airCO2[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'CO2 (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_airCO2[] = array(
                                $reading->getTimestamp(),
                                $reading->getCo2Percentage(),
                            );
                        }
                        $lineChart_airCO2 = new LineChart();
                        $lineChart_airCO2->getData()->setArrayToDataTable($arr_airCO2);

                        //Oxygen
                        $arr_airOxygen = array();
                        $arr_airOxygen[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Oxygen (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_airOxygen[] = array(
                                $reading->getTimestamp(),
                                $reading->getOxygenPercentage(),
                            );
                        }
                        $lineChart_airOxygen = new LineChart();
                        $lineChart_airOxygen->getData()->setArrayToDataTable($arr_airOxygen);

                        break;
                    case  "Humidity"://humidity
                        /* @var $readings HumidityReading[] */
                        $arr_generic = array();
                        $arr_generic[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'humidity (%)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_generic[] = array($reading->getTimestamp(), $reading->getHumidityValue());
                        }

                        $lineChart_generic->getData()->setArrayToDataTable($arr_generic);
                        break;
                    case "Pressure"://pressure
                        /* @var $readings PressureReading[] */
                        $arr_generic = array();
                        $arr_generic[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'pressure (KPa)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_generic[] = array($reading->getTimestamp(), $reading->getPressureValue() / 1000);
                        }

                        $lineChart_generic->getData()->setArrayToDataTable($arr_generic);
                        break;
                    case "Wind"://wind
                        /* @var $readings WindReading[] */
                        $arr_generic = array();
                        $arr_generic[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Wind speed (m/s)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_generic[] = array($reading->getTimestamp(), $reading->getWindSpeed());
                        }

                        $lineChart_generic->getData()->setArrayToDataTable($arr_generic);


                        $arr_wind = array();
                        $arr_wind[] = array(
                            array('label' => 'Timestamp', 'type' => 'string'),
                            array('label' => 'Wind Direction (°)', 'type' => 'number'),
                        );
                        foreach ($readings as $reading) {
                            $arr_wind[] = array($reading->getTimestamp(), $reading->getDirection());
                        }

                        $lineChart_windDir= new LineChart();
                        $lineChart_windDir->getData()->setArrayToDataTable($arr_wind);
                        break;
                }

                $lineChart_generic->getOptions()->getChartArea()->setTop(50);
                //$lineChart_generic->getOptions()->getChartArea()->setLeft(50);
                //$lineChart_generic->getOptions()->setEnableInteractivity(true);
                $lineChart_generic->getOptions()->setCurveType('function');

                $lineChart_generic->getOptions()->setHeight(700);
                $lineChart_generic->getOptions()->setWidth(1500);

                $lineChart_generic->getOptions()->getHAxis()->setSlantedTextAngle(90);
                $lineChart_generic->getOptions()->getHAxis()->setSlantedText(true);
                $lineChart_generic->getOptions()->getHAxis()->setShowTextEvery(1);
                $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setFontSize(10);
                $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setColor('#673AB7');
//                $lineChart_generic->getOptions()->getHAxis()->;

                // $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setColor('#FF5722');
                $lineChart_generic->getOptions()->getVAxis()->getTextStyle()->setColor('#2196F3');
                $lineChart_generic->getOptions()->getVAxis()->getTextStyle()->setBold(true);

                $lineChart_generic->getOptions()->getTooltip()->setIsHtml(true);
                $lineChart_generic->getOptions()->getTooltip()->setIgnoreBounds(true);

                if ($lineChart_airQly != null) {
                    /* @var $lineChart_airQly LineChart] */
                    $lineChart_airQly->getOptions()->getChartArea()->setTop(50);
                    //$lineChart_generic->getOptions()->getChartArea()->setLeft(50);
                    //$lineChart_generic->getOptions()->setEnableInteractivity(true);
                    $lineChart_airQly->getOptions()->setCurveType('function');

                    $lineChart_airQly->getOptions()->setHeight(700);
                    $lineChart_airQly->getOptions()->setWidth(1500);

                    $lineChart_airQly->getOptions()->getHAxis()->setSlantedTextAngle(90);
                    $lineChart_airQly->getOptions()->getHAxis()->setSlantedText(true);
                    $lineChart_airQly->getOptions()->getHAxis()->setShowTextEvery(1);
                    $lineChart_airQly->getOptions()->getHAxis()->getTextStyle()->setFontSize(10);
                    $lineChart_airQly->getOptions()->getHAxis()->getTextStyle()->setColor('#673AB7');

                    // $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setColor('#FF5722');
                    $lineChart_airQly->getOptions()->getVAxis()->getTextStyle()->setColor('#2196F3');
                    $lineChart_airQly->getOptions()->getVAxis()->getTextStyle()->setBold(true);

                    $lineChart_airQly->getOptions()->getTooltip()->setIsHtml(true);
                    $lineChart_airQly->getOptions()->getTooltip()->setIgnoreBounds(true);
                    $lineChart_airQly->getOptions()->setColors(['#3366cc']);


                }
                if ($lineChart_airOxygen != null) {
                    /* @var $lineChart_airOxygen LineChart] */
                    $lineChart_airOxygen->getOptions()->getChartArea()->setTop(50);
                    //$lineChart_generic->getOptions()->getChartArea()->setLeft(50);
                    //$lineChart_generic->getOptions()->setEnableInteractivity(true);
                    $lineChart_airOxygen->getOptions()->setCurveType('function');

                    $lineChart_airOxygen->getOptions()->setHeight(700);
                    $lineChart_airOxygen->getOptions()->setWidth(1500);

                    $lineChart_airOxygen->getOptions()->getHAxis()->setSlantedTextAngle(90);
                    $lineChart_airOxygen->getOptions()->getHAxis()->setSlantedText(true);
                    $lineChart_airOxygen->getOptions()->getHAxis()->setShowTextEvery(1);
                    $lineChart_airOxygen->getOptions()->getHAxis()->getTextStyle()->setFontSize(10);
                    $lineChart_airOxygen->getOptions()->getHAxis()->getTextStyle()->setColor('#673AB7');

                    // $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setColor('#FF5722');
                    $lineChart_airOxygen->getOptions()->getVAxis()->getTextStyle()->setColor('#2196F3');
                    $lineChart_airOxygen->getOptions()->getVAxis()->getTextStyle()->setBold(true);

                    $lineChart_airOxygen->getOptions()->getTooltip()->setIsHtml(true);
                    $lineChart_airOxygen->getOptions()->getTooltip()->setIgnoreBounds(true);
                    $lineChart_airOxygen->getOptions()->setColors(['#ff9900']);

                }
                if ($lineChart_airCO2 != null) {
                    /* @var $lineChart_airCO2 LineChart] */
                    $lineChart_airCO2->getOptions()->getChartArea()->setTop(50);
                    //$lineChart_generic->getOptions()->getChartArea()->setLeft(50);
                    //$lineChart_generic->getOptions()->setEnableInteractivity(true);
                    $lineChart_airCO2->getOptions()->setCurveType('function');

                    $lineChart_airCO2->getOptions()->setHeight(700);
                    $lineChart_airCO2->getOptions()->setWidth(1500);

                    $lineChart_airCO2->getOptions()->getHAxis()->setSlantedTextAngle(90);
                    $lineChart_airCO2->getOptions()->getHAxis()->setSlantedText(true);
                    $lineChart_airCO2->getOptions()->getHAxis()->setShowTextEvery(1);
                    $lineChart_airCO2->getOptions()->getHAxis()->getTextStyle()->setFontSize(10);
                    $lineChart_airCO2->getOptions()->getHAxis()->getTextStyle()->setColor('#673AB7');

                    // $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setColor('#FF5722');
                    $lineChart_airCO2->getOptions()->getVAxis()->getTextStyle()->setColor('#2196F3');
                    $lineChart_airCO2->getOptions()->getVAxis()->getTextStyle()->setBold(true);

                    $lineChart_airCO2->getOptions()->getTooltip()->setIsHtml(true);
                    $lineChart_airCO2->getOptions()->getTooltip()->setIgnoreBounds(true);
                    $lineChart_airCO2->getOptions()->setColors(['#c4492c']);

                }
                if ($lineChart_windDir != null) {
                    /* @var $lineChart_windDir LineChart] */
                    $lineChart_windDir->getOptions()->getChartArea()->setTop(50);
                    //$lineChart_generic->getOptions()->getChartArea()->setLeft(50);
                    //$lineChart_generic->getOptions()->setEnableInteractivity(true);
                    $lineChart_windDir->getOptions()->setCurveType('function');

                    $lineChart_windDir->getOptions()->setHeight(700);
                    $lineChart_windDir->getOptions()->setWidth(1500);

                    $lineChart_windDir->getOptions()->getHAxis()->setSlantedTextAngle(90);
                    $lineChart_windDir->getOptions()->getHAxis()->setSlantedText(true);
                    $lineChart_windDir->getOptions()->getHAxis()->setShowTextEvery(1);
                    $lineChart_windDir->getOptions()->getHAxis()->getTextStyle()->setFontSize(10);
                    $lineChart_windDir->getOptions()->getHAxis()->getTextStyle()->setColor('#673AB7');

                    // $lineChart_generic->getOptions()->getHAxis()->getTextStyle()->setColor('#FF5722');
                    $lineChart_windDir->getOptions()->getVAxis()->getTextStyle()->setColor('#2196F3');
                    $lineChart_windDir->getOptions()->getVAxis()->getTextStyle()->setBold(true);

                    $lineChart_windDir->getOptions()->getTooltip()->setIsHtml(true);
                    $lineChart_windDir->getOptions()->getTooltip()->setIgnoreBounds(true);
                    $lineChart_windDir->getOptions()->setColors(['#c4492c']);

                }

                //end of charts


                return $this->render(
                    '@App/reports/sensorReadings.html.twig',
                    array(
                        'sensor' => $sensor,
                        'readings' => $readings,
                        'lineChart_generic' => $lineChart_generic,
                        'lineChart_airQly' => $lineChart_airQly,
                        'lineChart_airOxygen' => $lineChart_airOxygen,
                        'lineChart_airCO2' => $lineChart_airCO2,
                        'lineChart_windDir' => $lineChart_windDir,

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