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


        $areaId = $areaController->getAreaIdAction($viewArea);
        $locationIdArray = $locationController->getLocationIdsAction($areaId);
        $sensorIdArray = array();
        foreach ($locationIdArray as $locationId) {
            $sensorIdArray = array_merge($sensorIdArray, $sensorController->getSensorIdsByLocationAction($locationId));
        }
        // print_r($sensorIdArray);
        $areaHumidity = 0;
        $areaTemp = 0;
        $areaWindSpeed = 0;
        $areaWindDirection = 0;
        $areaPressure = 0;

        $areaAirQty = 0;
        $areaAirO2 = 0;
        $areaAirCo2 = 0;

        foreach ($sensorIdArray as $sensorId) {
            switch ($sensorId[1]) {
                case 1://temp
                    $areaTemp += $tempController->tempLastReadingSearchAction($sensorId[0]);
                    break;
                case 2://air
                    $lastAirReading = $airController->airQtlLastReadingSearchAction($sensorId[0]);

                    $areaAirQty += $lastAirReading["air_qty_percentage"];
                    $areaAirO2 += $lastAirReading["oxygen_percentage"];
                    $areaAirCo2 += $lastAirReading["co2_percentage"];
                    break;
                case  3://humidity
                    $areaHumidity += $humidityController->humidityLastReadingSearchAction($sensorId[0]);
                    break;
                case 4://pressure
                    $areaPressure += $pressureController->pressureLastReadingSearchAction($sensorId[0]);
                    break;
                case 5://wind
                    $lastWindReading = $windController->windLastReadingSearchAction($sensorId[0]);

                    $areaWindSpeed += $lastWindReading["wind_speed"];
                    $areaWindDirection += $lastWindReading["direction"];
                    break;
            }
        }

        $noOfLocations = count($locationIdArray);

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
                'meanTemp' => $areaTemp,
                'meanHumidity' => $areaHumidity,
                'meanPressure' => $areaPressure,
                'meanWind' => array('speed' => $areaWindSpeed, 'direction' => $areaWindDirection),
                'meanAir' => array('airQly' => $areaAirQty, 'O2' => $areaAirO2, 'CO2' => $areaAirCo2),
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
