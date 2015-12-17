<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:06 AM
 */

namespace AppBundle\Controller\reports;

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

    public function pressureLastReadingSearchAction($sensor_id)
    {
        $lastReading = $this->connection->executeQuery(
            'SELECT pressure_value FROM pressure WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        $lastReading = $lastReading->fetchAll();

        if ($lastReading != null) {
            return $lastReading[0]["pressure_value"];
        }

        return false;
    }
}