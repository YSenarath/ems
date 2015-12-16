<?php

/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/10/2015
 * Time: 2:30 PM
 */
namespace AppBundle\Controller\sensor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\sensor\Sensor;
use Doctrine\DBAL\Connection;


class SensorController extends  Controller{

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


}
