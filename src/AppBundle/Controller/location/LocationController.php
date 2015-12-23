<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
namespace AppBundle\Controller\location;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LocationController extends Controller
{
    private $connection;

    public function __construct($connection)
    {
        $this->connection = $connection;
    }

    public function getLocationsAction()
    {
        $locations = $this->connection->fetchAll('SELECT address, longitude, latitude FROM location');

        if ($locations != null)
            return $locations;
        return false;
    }
}
