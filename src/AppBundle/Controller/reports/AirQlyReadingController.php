<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:06 AM
 */

namespace AppBundle\Controller\reports;

use AppBundle\Entity\report\AirQlyReading;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AirQlyReadingController extends Controller
{
    private $connection;

    /**
     * AirQlyReadingController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $sensor_id
     * @return AirQlyReading|bool
     */
    public function airQtlFirstReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,air_qty_percentage,oxygen_percentage,co2_percentage FROM air_qty WHERE sensor_id = ? ORDER BY timestamp ASC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {
            $airQtyReading = new AirQlyReading();
            $airQtyReading->setTimestamp($lastReading["timestamp"]);
            $airQtyReading->setAirQtyPercentage($lastReading["air_qty_percentage"]);
            $airQtyReading->setOxygenPercentage($lastReading["oxygen_percentage"]);
            $airQtyReading->setCo2Percentage($lastReading["co2_percentage"]);

            return $airQtyReading;
        }

        return false;
    }

    /**
     * @param $sensor_id
     * @return AirQlyReading|bool
     */
    public function airQtlLastReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,air_qty_percentage,oxygen_percentage,co2_percentage FROM air_qty WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {
            $airQtyReading = new AirQlyReading();
            $airQtyReading->setTimestamp($lastReading["timestamp"]);
            $airQtyReading->setAirQtyPercentage($lastReading["air_qty_percentage"]);
            $airQtyReading->setOxygenPercentage($lastReading["oxygen_percentage"]);
            $airQtyReading->setCo2Percentage($lastReading["co2_percentage"]);

            return $airQtyReading;
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
    public function airQtlReadingsSearchAction($sensor_id, $noOfReadings, $startDate, $endDate)
    {
        $quarry = $this->connection->executeQuery(
            'SELECT timestamp,air_qty_percentage,oxygen_percentage,co2_percentage FROM air_qty WHERE sensor_id = ? AND date(timestamp) BETWEEN ? AND ? ORDER BY timestamp DESC LIMIT '.$noOfReadings ,
            array($sensor_id, $startDate, $endDate)
        );
        $lastReadings = $quarry->fetchAll();
        $airQtyReadings = array();
        foreach ($lastReadings as $lastReading) {
            if ($lastReading != null) {
                $airQtyReading = new AirQlyReading();
                $airQtyReading->setTimestamp($lastReading["timestamp"]);
                $airQtyReading->setAirQtyPercentage($lastReading["air_qty_percentage"]);
                $airQtyReading->setOxygenPercentage($lastReading["oxygen_percentage"]);
                $airQtyReading->setCo2Percentage($lastReading["co2_percentage"]);

                $airQtyReadings[]= $airQtyReading;
            }
        }

        return $airQtyReadings;
    }
}