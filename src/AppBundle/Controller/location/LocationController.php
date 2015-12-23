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



    //by jnj to update my sensor add form
    public function getAllLocationNames()
    {

        $result =$this->connection->executeQuery('SELECT location.location_id , area.name FROM location NATURAL JOIN area ORDER BY area.name ');
        $result = $result->fetchAll();

        //print_r($result);

        $tempArea = null;
        $tempLocation = null;
        $locations = null;
        foreach ($result as $s) {
            if ($s != null) {
                if ($tempArea != $s["name"]) {
                    $tempArea = $s["name"];
                    if($tempLocation != null){
                        $locations[$s["name"]] = $tempLocation;
                        $tempLocation = null;
                    }
                }
                $tempLocation[$s["location_id"]] = $s["location_id"];
            }
        }

        return $locations;
    }
}

