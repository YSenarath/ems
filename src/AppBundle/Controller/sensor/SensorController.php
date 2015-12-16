<?php

/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/10/2015
 * Time: 2:30 PM
 */
namespace AppBundle\Controller\sensor;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class SensorController extends  Controller{

    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }


    public function sensorListAction($username)
    {
        $sensor = $this->connection->fetchAssoc('SELECT * FROM sensor WHERE user_name = ?', array($username));

        if ($sensor != null)
            return $sensor;
        return false;
    }


}
