<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
namespace AppBundle\Controller\location;

use AppBundle\Entity\report\LocationEntity;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LocationController extends Controller
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getLocationsAction()
    {
        $locations = $this->connection->fetchAll('SELECT address, longitude, latitude FROM location');

        if ($locations != null) {
            return $locations;
        }

        return false;
    }


    //by jnj to update my sensor add form
    public function getAllLocationNames()
    {

        $result = $this->connection->executeQuery(
            'SELECT location.location_id , area.name FROM location NATURAL JOIN area ORDER BY area.name '
        );
        $result = $result->fetchAll();

        //print_r($result);

        $tempArea = null;
        $tempLocation = null;
        $locations = null;
        foreach ($result as $s) {
            if ($s != null) {
                if ($tempArea != $s["name"]) {
                    $tempArea = $s["name"];
                    if ($tempLocation != null) {
                        $locations[$s["name"]] = $tempLocation;
                        $tempLocation = null;
                    }
                }
                $tempLocation[$s["location_id"]] = $s["location_id"];
            }
        }

        return $locations;
    }

//    /**
//     * Created by Shehan
//     * @param $areaId
//     * @return array
//     * @throws \Doctrine\DBAL\DBALException
//     */
//    public function getLocationIdsAction($areaId)
//    {
//        $result = $this->connection->executeQuery(
//            'SELECT location_id FROM location WHERE area_code=? ORDER BY location_id',
//            array($areaId)
//        );
//        $result = $result->fetchAll();
//        //print_r($result);
//        $locationIdArray = array();
//
//        foreach ($result as $a) {
//            if ($a != null) {
//                $locationIdArray[] = $a["location_id"];
//            }
//        }
//
//        //print_r($locationIdArray);
//
//        return $locationIdArray;
//
//    }
//
    /**
     * Created by Shehan
     * @param $areaId
     * @return LocationEntity[]|bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getLocationDetailsByAreaAction($areaId)
    {
        $locations = $this->connection->executeQuery(
            'SELECT location_id,address, longitude, latitude FROM location WHERE area_code=? ORDER BY location_id',
            array($areaId)
        );
        $locationsResult = $locations->fetchAll();

        $locationArray = array();
        //print_r($result);
        if ($locationsResult != null) {
            foreach ($locationsResult as $loc) {
                if ($loc != null) {
                    $tmpLocation = new LocationEntity();
                    $tmpLocation->setLocationId($loc["location_id"]);
                    $tmpLocation->setAddress($loc["address"]);
                    $tmpLocation->setLongitude($loc["longitude"]);
                    $tmpLocation->setLatitude($loc["latitude"]);
                    $locationArray[] = $tmpLocation;
                }
            }

            return $locationArray;
        }

        //print_r($locationArray);
        return false;
    }

    /**
     * Created by Shehan
     * @param $locationAddress
     * @return LocationEntity|bool
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getLocationDetailsAction($locationAddress)
    {
        $location = $this->connection->fetchAssoc(
            'SELECT location_id,address, longitude, latitude FROM location WHERE address=?',
            array($locationAddress)
        );

        //print_r($result);
        if ($location != null) {

            $tmpLocation = new LocationEntity();
            $tmpLocation->setLocationId($location["location_id"]);
            $tmpLocation->setAddress($location["address"]);
            $tmpLocation->setLongitude($location["longitude"]);
            $tmpLocation->setLatitude($location["latitude"]);
            return $tmpLocation;

            //return array($locationsResult["location_id"],$locationsResult["address"],$locationsResult["longitude"],$locationsResult["latitude"]);
        }

        //print_r($locationArray);
        return false;

    }
}

