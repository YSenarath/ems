<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
namespace AppBundle\Controller\location;

use AppBundle\Entity\location\Location;
use AppBundle\Entity\report\Area;
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

    /**
     * By: Dulanjaya Tennekoon
     * Date: 1/4/2016
     * Time: 5.12pm
     */

    public function addLocation(Location $location)
    {
        $this->connection->beginTransaction();

        try {
            $statement = $this->connection->prepare(
                'INSERT INTO location (address , longitude, latitude, area_code ) VALUES (?,?,?,?)'
            );

            $statement->bindValue(1, $location->getAddress());
            $statement->bindValue(2, $location->getLongitude());
            $statement->bindValue(3, $location->getLatitude());
            $statement->bindValue(4, $location->getAreaCode());

            $statement->execute();
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }

    /*By Dulanjaya*/
    public function getAllAreas()
    {
        $result = $this->connection->executeQuery('SELECT area_code,name FROM area ORDER BY area_code');
        $results = $result->fetchAll();

        $areas = null;
        foreach ($results as $s) {
            if ($s != null) {

                $areas[$s["name"]] = $s["area_code"];

            }
        }

        return $areas;
    }

//    In order to view the areas
    public function getArea()
    {
        $result = $this->connection->executeQuery('SELECT * FROM area ORDER BY name');
        $result = $result->fetchAll();

        //print_r($result);
        $areas[] = new Area();

        foreach ($result as $a) {
            if ($a != null) {
                $tmpArea = new Area();
                $tmpArea->setAreaCode($a["area_code"]);
                $tmpArea->setName($a["name"]);
                $tmpArea->setAreaSize($a["area_size"]);
                $areas[] = $tmpArea;
            }
        }

        return $areas;
    }
}

