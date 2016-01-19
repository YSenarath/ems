<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 1/19/2016
 * Time: 10:33 PM
 */

namespace AppBundle\Controller\reports;


use AppBundle\Entity\report\SensorReadingFilter;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SensorReadingFilterController extends  Controller{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    //gives an array of sensors
    public function findSensors(SensorReadingFilter $sensor)
    {
        $sql = 'SELECT * FROM (sensor  NATURAL JOIN sensor_model) NATURAL JOIN location WHERE ';
        $param=array();
        $passing =array();

        if ($sensor->getModelId() != null){
            $sql = $sql.' model_id IN (?) AND ' ;
            $param[] = $sensor->getModelId();
            $passing[] = \Doctrine\DBAL\Connection::PARAM_STR_ARRAY;
        }
        if ($sensor->getTypeName() != null){
            $sql = $sql.' type_name IN (?) AND ' ;
            $param[] = $sensor->getTypeName();
            $passing[] = \Doctrine\DBAL\Connection::PARAM_STR_ARRAY;
        }
        if ($sensor->getLocId() != null){
            $sql = $sql.' location_id IN (?) AND ' ;
            $param[] = $sensor->getLocId();
            $passing[] = \Doctrine\DBAL\Connection::PARAM_STR_ARRAY;
        }

        $sql =  $sql.' installed_date BETWEEN ? AND ? AND sensor_id LIKE ?';
        $param[]= $sensor-> getInsDate()->format('Y-m-d');
        $param[]= $sensor->getInsBefore()->format('Y-m-d');
        $param[]= '%'.$sensor->getSensorId().'%';


        $result = $this->connection->executeQuery(
            $sql,
            $param,
            $passing);
        $result = $result->fetchAll();

        //print_r($result);
        $sensors[] = new SensorReadingFilter();

        foreach ($result as $s) {
            if ($s != null) {
                $sensor = new SensorReadingFilter();
                $sensor->setSensorId($s["sensor_id"]);
                $sensor->setTypeName($this->getTypeName($s["type_name"]));
                $sensors[] = $sensor;
            }
        }

        return $sensors;
    }


    public function getTypeName($input)
    {
        switch($input) {
            case 'air_qty' :
                return 'Air Quality';
            case 'humidity' :
                return 'Humidity';
            case 'pressure' :
                return 'Pressure';
            case 'temp' :
                return 'Temperature';
            case 'wind' :
                return 'Wind';
        }
    }
}