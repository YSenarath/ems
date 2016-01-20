<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 12/11/2015
 * Time: 6:26 PM
 */
namespace AppBundle\Controller\location;

use AppBundle\Entity\location\Location;
use AppBundle\Entity\location\Area;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;

class LocationController extends Controller
{
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }


    /**created by jnj
     * bug fixes
     * add location
     * */
    public function searchAddressInArea($address,$area)
    {
        $location = $this->connection->fetchAssoc(
            'SELECT location_id,address, longitude, latitude, area_code FROM location WHERE address = ? AND area_code = ?',
            array($address,$area)
        );
        //print_r($result);
        if ($location != null) {

            $tmpLocation = new Location();
            $tmpLocation->setId($location["location_id"]);
            $tmpLocation->setAddress($location["address"]);
            $tmpLocation->setLongitude($location["longitude"]);
            $tmpLocation->setLatitude($location["latitude"]);
            $tmpLocation->setAreaCode($location["area_code"]);
            return $tmpLocation;
            //return array($locationsResult["location_id"],$locationsResult["address"],$locationsResult["longitude"],$locationsResult["latitude"]);
        }
        //print_r($locationArray);
        return false;

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
    //changed method on 9/1/2016
    public function getAllLocationNames()
    {

        $result = $this->connection->executeQuery(
            'SELECT location.location_id , area.name , address FROM location NATURAL JOIN area ORDER BY area.name '
        );
        $result = $result->fetchAll();

        //print_r($result);

        $tempArea = null;
        $tempLocation = null;
        $locations = null;
        foreach ($result as $s) {
            if ($s != null) {
                if ($tempArea != $s["name"]) {

                    if ($tempLocation != null) {
                        $locations[$tempArea] = $tempLocation;
                        $tempLocation = null;
                    }
                    $tempArea = $s["name"];
                }
                $tempLocation[$s["address"]] = $s["location_id"];
            }
        }


        return $locations;
    }

    /**
     * Created by Shehan
     * @param $areaId
     * @return array|bool
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
                    $tmpLocation = new Location();
                    $tmpLocation->setId($loc["location_id"]);
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
     * @return Location|bool
     */
    public function getLocationDetailsAction($locationAddress)
    {
        $location = $this->connection->fetchAssoc(
            'SELECT location_id,address, longitude, latitude FROM location WHERE address=?',
            array($locationAddress)
        );

        //print_r($result);
        if ($location != null) {

            $tmpLocation = new Location();
            $tmpLocation->setId($location["location_id"]);
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
    public function getAllAreaCodes()
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
        $result = $this->connection->executeQuery('SELECT * FROM area ORDER BY area_code');
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

    public function getAllDistrictsAction()
    {
        $districts = $this->connection->fetchAll('SELECT area_code,name, center_longitude, center_latitude FROM area ORDER BY area_code');

        if ($districts != null) {
            return $districts;
        }

        return false;
    }

    public function getLocationbyIDAction($locationID)
    {
        $location = $this->connection->fetchAssoc(
            'SELECT location_id,address, longitude, latitude, area_code FROM location WHERE location_id=?',
            array($locationID)
        );
        //print_r($result);
        if ($location != null) {

            $tmpLocation = new Location();
            $tmpLocation->setId($location["location_id"]);
            $tmpLocation->setAddress($location["address"]);
            $tmpLocation->setLongitude($location["longitude"]);
            $tmpLocation->setLatitude($location["latitude"]);
            $tmpLocation->setAreaCode($location["area_code"]);
            return $tmpLocation;
            //return array($locationsResult["location_id"],$locationsResult["address"],$locationsResult["longitude"],$locationsResult["latitude"]);
        }
        //print_r($locationArray);
        return false;

    }
    public function changeLocation(Location $location)
    {
        $this->connection->beginTransaction();

        try {
            $statement = $this->connection->prepare(
                'UPDATE location SET address = ?, longitude = ?, latitude = ?, area_code = ? WHERE location_id=?'
            );

            $statement->bindValue(1, $location->getAddress());
            $statement->bindValue(2, $location->getLongitude());
            $statement->bindValue(3, $location->getLatitude());
            $statement->bindValue(4, $location->getAreaCode());
            $statement->bindValue(5, $location->getId());

            $statement->execute();
            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();
            // throw $e;
        }
    }

    public function deleteLocation($locationView)
    {
        $this->connection->beginTransaction();
        try {
            $statement = $this->connection->prepare(
                'DELETE FROM Location WHERE location_id = ?'
            );

            $statement->bindValue(1, $locationView);
            $statement->execute();
            $this->connection->commit();
            return true;
        } catch (ForeignKeyConstraintViolationException $e) {
            $this->connection->rollBack();
            return false;
        }

    }
}
