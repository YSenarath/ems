<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:06 AM
 */

namespace AppBundle\Controller\reports;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\DBAL\Connection;

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

    public function humidityLastReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT * FROM humidity WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {
            return $lastReading;
        }

        return false;
    }

}