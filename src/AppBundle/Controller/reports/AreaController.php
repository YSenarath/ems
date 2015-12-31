<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 3:43 PM
 */

namespace AppBundle\Controller\reports;


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

    public function getAreaIdAction($areaName)
    {
        $result = $this->connection->fetchAssoc('SELECT area_code FROM area WHERE name=?', array($areaName));
        // $result = $result->fetchAll();

        if ($result != null) {
            //print_r($result["area_code"]);

            return $result["area_code"];
        }

        return false;

    }

    public function getAreaDetailsAction($areaName)
    {
        $result = $this->connection->fetchAssoc('SELECT area_code,center_longitude,center_latitude FROM area WHERE name=?', array($areaName));
        // $result = $result->fetchAll();

        if ($result != null) {
            //print_r($result["area_code"]);

            return array($result["area_code"],$result["center_longitude"],$result["center_latitude"]);
        }

        return false;

    }

}