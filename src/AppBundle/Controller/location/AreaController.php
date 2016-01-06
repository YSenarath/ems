<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 3:43 PM
 */

namespace AppBundle\Controller\location;


use AppBundle\Entity\report\Area;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AreaController extends Controller
{
    private $connection;

    /**
     * AreaController constructor.
     * @param $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    public function getAllAreasAction()
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

//    public function getAreaIdAction($areaName)
//    {
//        $result = $this->connection->fetchAssoc('SELECT area_code FROM area WHERE name=?', array($areaName));
//        // $result = $result->fetchAll();
//
//        if ($result != null) {
//            //print_r($result["area_code"]);
//
//            return $result["area_code"];
//        }
//
//        return false;
//
//    }
//
    /**
     * @param $areaName
     * @return Area|bool
     */
    public function getAreaDetailsAction($areaName)
    {
        $result = $this->connection->fetchAssoc('SELECT area_code,center_longitude,center_latitude FROM area WHERE name=?', array($areaName));
        // $result = $result->fetchAll();

        if ($result != null) {
            //print_r($result["area_code"]);

            $area=new Area();
            $area->setAreaCode($result["area_code"]);
            $area->setCenterLongitude($result["center_longitude"]);
            $area->setCenterLatitude($result["center_latitude"]);

            return $area;
            // return array("area_code"=>$result["area_code"],"center_longitude"=>$result["center_longitude"],"center_latitude"=>$result["center_latitude"]);
        }

        return false;

    }

}