<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:07 AM
 */

namespace AppBundle\Controller\reports;

use AppBundle\Entity\report\TempReading;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TempReadingController extends Controller
{
    private $connection;

    /**
     * TempReadingController constructor.
     * @param Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param $sensor_id
     * @return TempReading|bool
     */
    public function tempLastReadingSearchAction($sensor_id)
    {
        //print_r($sensor_id);
        $lastReading = $this->connection->fetchAssoc(
            'SELECT timestamp,temp_value  FROM temp WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        //$lastReading=$lastReading->();

        if ($lastReading != null) {
            //print_r($lastReading);
            $tempReading = new TempReading();
            $tempReading->setTimestamp($lastReading["timestamp"]);
            $tempReading->setTempValue($lastReading["temp_value"]);

            return $tempReading;
        }

        return false;
    }
}