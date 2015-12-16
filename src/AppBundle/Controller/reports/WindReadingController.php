<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 1:07 AM
 */

namespace AppBundle\Controller\reports;

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

    public function windLastReadingSearchAction( $sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT wind_speed,direction FROM wind WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {

           // print_r($lastReading);
            return $lastReading;
        }
        return false;
    }
}