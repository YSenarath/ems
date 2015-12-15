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
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function tempLastReadingSearchAction(Connection $sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT * FROM temp WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {
            return $lastReading;
        }

        return false;
    }
}