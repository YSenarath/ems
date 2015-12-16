<?php

/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/10/2015
 * Time: 2:30 PM
 */
namespace AppBundle\Controller\sensor;

use AppBundle\Entity\sensor\Sensor;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SensorController extends Controller
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAllSensors()
    {
        $result = $this->connection->executeQuery('SELECT * FROM sensor ORDER BY sensor_id');
        $result = $result->fetchAll();

        //print_r($result);
        $sensors[] = new Sensor();

        foreach ($result as $s) {
            if ($s != null) {
                $sensor = new Sensor();
                $sensor->setSensorId($s["sensor_id"]);
                $sensor->setTypeId($s["type_id"]);
                $sensor->setModelId($s["model_id"]);
                $sensor->setInsDate($s["installed_date"]);
                $sensor->setTMin($s["threshold_min"]);
                $sensor->setTMax($s["threshold_max"]);
                $sensor->setLocId($s["location_id"]);
                $sensors[] = $sensor;
            }
        }

        return $sensors;
    }

    /**
     * Created by Shehan
     * @param $locationId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getSensorIdsByLocationAction($locationId)
    {

        $result = $this->connection->executeQuery(
            'SELECT sensor_id,type_id FROM sensor WHERE location_id=? ORDER BY sensor_id',
            array($locationId)
        );
        $result = $result->fetchAll();
        //print_r($result);
        $sensorIdArray = array();

        foreach ($result as $a) {
            if ($a != null) {
                $sensorIdArray[] = array($a["sensor_id"], $a["type_id"]);
            }
        }

        //print_r($sensorIdArray);

        return $sensorIdArray;
    }


}
