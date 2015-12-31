<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 10:11 PM
 */

namespace AppBundle\Controller\reports;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LocationController extends Controller
{
    private $connection;

    /**
     * LocationController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function getLocationIdsAction($areaId)
    {
        $result = $this->connection->executeQuery(
            'SELECT location_id FROM location WHERE area_code=? ORDER BY location_id',
            array($areaId)
        );
        $result = $result->fetchAll();
        //print_r($result);
        $locationIdArray = array();

        foreach ($result as $a) {
            if ($a != null) {
                $locationIdArray[] = $a["location_id"];
            }
        }

        //print_r($locationIdArray);

        return $locationIdArray;

    }

    public function getLocationDetailsAction($areaId)
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
                    $locationArray[] = array($loc["location_id"],$loc["address"],$loc["longitude"],$loc["latitude"]);
                }
            }
            return $locationArray;
        }
        //print_r($locationArray);
        return false;

    }
}