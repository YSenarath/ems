<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:07 AM
 */

namespace AppBundle\Controller\reports;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\DBAL\Connection;

class TempReadingController extends Controller
{
    private $connection;

    /**
     * TempReadingController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function tempLastReadingSearchAction($sensor_id)
    {
        //print_r($sensor_id);
        $lastReading = $this->connection->executeQuery(
            'SELECT temp_value  FROM temp WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        $lastReading=$lastReading->fetchAll();

        if ($lastReading != null) {
            return $lastReading[0]["temp_value"];
        }

        return false;
    }
}