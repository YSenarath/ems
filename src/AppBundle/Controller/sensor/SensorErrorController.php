<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 1/13/2016
 * Time: 12:40 AM
 */

namespace AppBundle\Controller\sensor;

use AppBundle\Entity\sensor\SensorError;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class SensorErrorController extends Controller
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getAllErrors()
    {
        $result = $this->connection->executeQuery('SELECT * FROM (err_sens_report NATURAL JOIN sensor) NATURAL JOIN location ORDER BY is_fixed, timestamp DESC');
        $result = $result->fetchAll();

        //print_r($result);
        $sensorErrs[] = new SensorError();

        foreach ($result as $s) {
            if ($s != null) {
                $sensorErr = new SensorError();
                $sensorErr->setSensorId($s["sensor_id"]);
                $sensorErr->setType($this->getTypeName($s["type_name"]));
                $sensorErr->setErrorDesc($s["error_desc"]);
                $sensorErr->setReportId($s["report_id"]);
                $sensorErr->setTimestamp($s["timestamp"]);
                $sensorErr->setIsFixed($this->booleanFilter($s["is_fixed"]));
                $sensorErr->setLocation($s["address"]);
                $sensorErrs[] = $sensorErr;
            }
        }

        return $sensorErrs;
    }


    public function searchError($sensor_id, $report_id )
    {
        $s =$this->connection->fetchAssoc('SELECT * FROM err_sens_report WHERE sensor_id= ? AND report_id = ?', array($sensor_id,$report_id));

        //print_r($result);
        $sensorErr = new SensorError();

        if ($s != null) {

            $sensorErr->setSensorId($s["sensor_id"]);
            $sensorErr->setErrorDesc($s["error_desc"]);
            $sensorErr->setReportId($s["report_id"]);
            $sensorErr->setTimestamp($s["timestamp"]);
            $sensorErr->setIsFixed($this->booleanFilter2($s["is_fixed"]));

        }else{
            return false;
        }

        return $sensorErr;
    }

    public function updateErrorAction(SensorError $sensorError)
    {
        $this->connection->beginTransaction();

        try{
            $statement = $this->connection->prepare('UPDATE err_sens_report SET error_desc = ?, is_fixed = ?  WHERE sensor_id =? AND report_id = ?');

            $statement->bindValue(1, $sensorError->getErrorDesc());
            $statement->bindValue(2, $this->booleanFilter3($sensorError->getIsFixed()));
            $statement->bindValue(3, $sensorError->getSensorId());
            $statement->bindValue(4, $sensorError->getReportId());

            $statement->execute();
            $this->connection->commit();
        } catch(Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }


    public function booleanFilter($input){
        if ($input == '1'){
            return 'Fixed';
        }
        return 'Not Fixed';
    }

    public function booleanFilter2($input){
        if ($input == '1'){
            return true;
        }
        return false;
    }

    public function booleanFilter3($input){
        if ($input){
            return 1;
        }
        return 0;
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