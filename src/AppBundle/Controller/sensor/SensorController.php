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
use Symfony\Component\Validator\Constraints\DateTime;


class SensorController extends  Controller{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAllSensors()
    {
        $result = $this->connection->executeQuery('SELECT * FROM sensor NATURAL JOIN sensor_type ORDER BY installed_date DESC');
        $result = $result->fetchAll();

        //print_r($result);
        $sensors[] = new Sensor();

        foreach ($result as $s) {
            if ($s != null) {
                $sensor = new Sensor();
                $sensor->setSensorId($s["sensor_id"]);
                $sensor->setTypeId($s["type"]);
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
     * Created by ShehanGemba
     * @param $locationId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getSensorDetailsByLocationAction($locationId)
    {

        $result = $this->connection->executeQuery(
            'SELECT sensor_id,type_name FROM sensor WHERE location_id=? ORDER BY sensor_id',
            array($locationId)
        );
        $result = $result->fetchAll();
        //print_r($result);
        $sensorIdArray = array();

        foreach ($result as $a) {
            if ($a != null) {
                $sensorIdArray[] = array($a["sensor_id"], $a["type_name"]);
            }
        }

        //print_r($sensorIdArray);

        return $sensorIdArray;
    }

    public function searchSensor($sensor_id)
    {
        $s =$this->connection->fetchAssoc('SELECT * FROM sensor NATURAL JOIN sensor_type WHERE sensor_id = ?', array($sensor_id));

        //print_r($result);
        $sensor = new Sensor();

        if ($s != null) {
                $sensor->setSensorId($s["sensor_id"]);
                $sensor->setTypeId($s["type"]);
                $sensor->setModelId($s["model_id"]);
                $sensor->setInsDate($s["installed_date"]);
                $sensor->setTMin($s["threshold_min"]);
                $sensor->setTMax($s["threshold_max"]);
                $sensor->setLocId($s["location_id"]);

        }else{
            return false;
        }

        return $sensor;
    }

    public function sensorAddAction(Sensor $sensor)
    {
        $this->connection->beginTransaction();

        try{
            $statement = $this->connection->prepare('INSERT INTO sensor (sensor_id ,threshold_min , threshold_max , location_id, type_id , model_id , installed_date ) VALUES (?,?,?,?, ?, ?, ?)');

            $statement->bindValue(1, $sensor->getSensorId());
            $statement->bindValue(2, $sensor->getTMin());
            $statement->bindValue(3, $sensor->getTMax());
            $statement->bindValue(4, $sensor->getLocId());
            $statement->bindValue(5, $sensor->getTypeId());
            $statement->bindValue(6, $sensor->getModelId());
            $statement->bindValue(7, $sensor->getInsDate()->format('Y-m-d'));

            $statement->execute();
            $this->connection->commit();
        } catch(Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }


}
