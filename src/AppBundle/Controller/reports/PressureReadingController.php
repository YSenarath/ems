<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:06 AM
 */

namespace AppBundle\Controller\reports;

use AppBundle\Entity\report\PressureReading;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PressureReadingController extends Controller
{
    private $connection;

    /**
     * PressureReadingController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $sensor_id
     * @return PressureReading|bool
     */
    public function pressureFirstReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,pressure_value FROM pressure WHERE sensor_id = ? ORDER BY timestamp ASC LIMIT 1',
            array($sensor_id)
        );

        if ($lastReading != null) {
            $pressureReading = new PressureReading();
            $pressureReading->setTimestamp($lastReading["timestamp"]);
            $pressureReading->setPressureValue($lastReading["pressure_value"]);

            return $pressureReading;
        }

        return false;
    }

    /**
     * @param $sensor_id
     * @return PressureReading|bool
     */
    public function pressureLastReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,pressure_value FROM pressure WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );

        if ($lastReading != null) {
            $pressureReading = new PressureReading();
            $pressureReading->setTimestamp($lastReading["timestamp"]);
            $pressureReading->setPressureValue($lastReading["pressure_value"]);

            return $pressureReading;
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
    public function pressureReadingsSearchAction($sensor_id, $noOfReadings, $startDate, $endDate)
    {
        $quarry = $this->connection->executeQuery(
            'SELECT timestamp,pressure_value FROM pressure WHERE sensor_id = ? AND date(timestamp) BETWEEN ? AND ? ORDER BY timestamp DESC LIMIT '.$noOfReadings ,
            array($sensor_id, $startDate, $endDate)
        );

        $lastReadings = $quarry->fetchAll();
        $pressureReadings = array();
        foreach ($lastReadings as $lastReading) {
            if ($lastReading != null) {
                $pressureReading = new PressureReading();
                $pressureReading->setTimestamp($lastReading["timestamp"]);
                $pressureReading->setPressureValue($lastReading["pressure_value"]);

                $pressureReadings[] = $pressureReading;
            }
        }

        return $pressureReadings;
    }
}