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

}