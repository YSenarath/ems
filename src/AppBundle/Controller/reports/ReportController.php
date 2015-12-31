<?php

namespace AppBundle\Controller\reports;

use AppBundle\Controller\sensor\SensorController;
use AppBundle\Entity\report\Area;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
        $areaId = $area[0];
        $locationArray = $locationController->getLocationDetailsAction($areaId);
        //  $locationIdArray = $locationController->getLocationIdsAction($areaId);
        // print_r($locationArray);
        $sensorArray = array();

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

        foreach ($locationArray as $location){
            //print_r($location[0]."<br>");
            $sensorArray = array_merge($sensorArray, $sensorController->getSensorDetailsByLocationAction($location[0]));
        }

        $areaHumidity = 0;
        $areaTemp = 0;
        $areaWindSpeed = 0;
        $areaWindDirection = 0;
        $areaPressure = 0;

        $areaAirQty = 0;
        $areaAirO2 = 0;
        $areaAirCo2 = 0;

        foreach ($sensorArray as $sensorDetail) {
           // print_r($sensorDetail);
            switch ($sensorDetail[1]) {
                case "temp"://temp
                   // print_r($sensorDetail[0].'<br>');
                    $areaTemp += $tempController->tempLastReadingSearchAction($sensorDetail[0]);
                    break;
                case "air_qty"://air
                    $lastAirReading = $airController->airQtlLastReadingSearchAction($sensorDetail[0]);

                    $areaAirQty += $lastAirReading["air_qty_percentage"];
                    $areaAirO2 += $lastAirReading["oxygen_percentage"];
                    $areaAirCo2 += $lastAirReading["co2_percentage"];
                    break;
                case  "humidity"://humidity
                    $areaHumidity += $humidityController->humidityLastReadingSearchAction($sensorDetail[0]);
                    break;
                case "pressure"://pressure
                    $areaPressure += $pressureController->pressureLastReadingSearchAction($sensorDetail[0]);
                    break;
                case "wind"://wind
                    $lastWindReading = $windController->windLastReadingSearchAction($sensorDetail[0]);

                    $areaWindSpeed += $lastWindReading["wind_speed"];
                    $areaWindDirection += $lastWindReading["direction"];
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
                'area_longitude' => $area[1],
                'area_latitude' => $area[2],
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
    public function reportLocationViewAction($viewArea,$viewLocation){
        return $this->render('@App/reports/locationView.html.twig',array('areaName'=>$viewArea,'locationName'=>$viewLocation));
    }

    /**
     * @Route("/reports/summery", name="reportSummery")
     */
    public function reportSummeryAction()
    {
        return $this->render('@App/reports/summery.html.twig');
    }
}
