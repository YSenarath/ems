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

class WindController extends Controller
{
    private $connection;

    /**
     * WindController constructor.
     * @param $connection
     */
    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function presuureLastReadingSearchAction(Connection $sensor_id)
    {
        $lastReading = $this->connection->fetchAssoc(
            'SELECT * FROM pressure WHERE sensor_id = ? ORDER BY timestamp DESC LIMIT 1',
            array($sensor_id)
        );
        if ($lastReading != null) {
            return $lastReading;
        }

        return false;
    }
}