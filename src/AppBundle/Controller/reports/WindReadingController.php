<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:07 AM
 */

namespace AppBundle\Controller\reports;

use AppBundle\Entity\report\WindReading;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class WindReadingController extends Controller
{
    private $connection;

    /**
     * WindController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**
     * @param $sensor_id
     * @return WindReading|bool
     */
    public function windFirstReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,wind_speed,direction FROM wind WHERE sensor_id = ? ORDER BY timestamp ASC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {

            // print_r($lastReading);
            $windReading = new WindReading();
            $windReading->setTimestamp($lastReading["timestamp"]);
            $windReading->setWindSpeed($lastReading["wind_speed"]);
            $windReading->setDirection($lastReading["direction"]);

            return $windReading;

        }

        return false;
    }

    /**
     * @param $sensor_id
     * @return WindReading|bool
     */
    public function windLastReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,wind_speed,direction FROM wind WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {

            // print_r($lastReading);
            $windReading = new WindReading();
            $windReading->setTimestamp($lastReading["timestamp"]);
            $windReading->setWindSpeed($lastReading["wind_speed"]);
            $windReading->setDirection($lastReading["direction"]);

            return $windReading;

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
    public function windReadingsSearchAction($sensor_id, $noOfReadings, $startDate, $endDate)
    {
        $quarry = $this->connection->executeQuery(
            'SELECT timestamp,wind_speed,direction FROM wind WHERE sensor_id = ? AND date(timestamp) BETWEEN ? AND ? ORDER BY timestamp DESC LIMIT '.$noOfReadings ,
            array($sensor_id, $startDate, $endDate)
        );
        $lastReadings = $quarry->fetchAll();
        $windReadings = array();
        foreach ($lastReadings as $lastReading) {
            if ($lastReading != null) {

                // print_r($lastReading);
                $windReading = new WindReading();
                $windReading->setTimestamp($lastReading["timestamp"]);
                $windReading->setWindSpeed($lastReading["wind_speed"]);
                $windReading->setDirection($lastReading["direction"]);

                $windReadings[]= $windReading;
            }
        }

        return $windReadings;
    }
}