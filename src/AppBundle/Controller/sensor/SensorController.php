<?php

/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/10/2015
 * Time: 2:30 PM
 */
namespace AppBundle\Controller\sensor;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SensorController extends Controller
{

    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    public function sensorListAction($username)
    {
        $sensor = $this->connection->fetchAssoc('SELECT * FROM sensor WHERE user_name = ?', array($username));

        if ($sensor != null) {
            return $sensor;
        }

        return false;
    }

    /**
     * Created by Shehan
     * @param $locationId
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getSensorIdsByLocationAction($locationId)
    {

        $result = $this->connection->executeQuery('SELECT sensor_id,type_id FROM sensor WHERE location_id=? ORDER BY sensor_id',array($locationId));
        $result = $result->fetchAll();
        //print_r($result);
        $sensorIdArray = array();

        foreach ($result as $a) {
            if ($a != null) {
                $sensorIdArray[] = array($a["sensor_id"],$a["type_id"]);
            }
        }
        //print_r($sensorIdArray);

        return $sensorIdArray;
    }


}
