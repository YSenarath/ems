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
}