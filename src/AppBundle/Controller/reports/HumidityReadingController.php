<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:06 AM
 */

namespace AppBundle\Controller\reports;


use AppBundle\Entity\report\HumidityReading;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HumidityReadingController extends Controller
{
    private $connection;

    /**
     * HumidityReadingController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $sensor_id
     * @return HumidityReading|bool
     */
    public function humidityFirstReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,humidity_value FROM humidity WHERE sensor_id = ? ORDER BY timestamp ASC LIMIT 1',
            array($sensor_id)
        );

        if ($lastReading != null) {
            $humidityReading = new HumidityReading();
            $humidityReading->setTimestamp($lastReading["timestamp"]);
            $humidityReading->setHumidityValue($lastReading["humidity_value"]);

            return $humidityReading;
        }

        return false;
    }

    /**
     * @param $sensor_id
     * @return HumidityReading|bool
     */
    public function humidityLastReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,humidity_value FROM humidity WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );

        if ($lastReading != null) {
            $humidityReading = new HumidityReading();
            $humidityReading->setTimestamp($lastReading["timestamp"]);
            $humidityReading->setHumidityValue($lastReading["humidity_value"]);

            return $humidityReading;
        }

        return false;
    }

    /**
     * @param $sensor_id
     * @param $noOfReadigs
     * @param $startDate
     * @param $endDate
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function humidityReadingsSearchAction($sensor_id, $noOfReadings, $startDate, $endDate)
    {
        $quarry = $this->connection->executeQuery(
            'SELECT timestamp,humidity_value FROM humidity WHERE sensor_id = ? AND date(timestamp) BETWEEN ? AND ? ORDER BY timestamp DESC LIMIT '.$noOfReadings ,
            array($sensor_id, $startDate, $endDate)
        );

        $lastReadings = $quarry->fetchAll();
        $humidityReadings = array();
        foreach ($lastReadings as $lastReading) {
            if ($lastReading != null) {
                $humidityReading = new HumidityReading();
                $humidityReading->setTimestamp($lastReading["timestamp"]);
                $humidityReading->setHumidityValue($lastReading["humidity_value"]);
                $humidityReadings[] = $humidityReading;
            }
        }

        return $humidityReadings;
    }
}