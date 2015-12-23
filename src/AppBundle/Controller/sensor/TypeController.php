<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/17/2015
 * Time: 9:58 AM
 */

namespace AppBundle\Controller\sensor;

use AppBundle\Entity\sensor\Type;
use Doctrine\DBAL\Connection;


class TypeController
{

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function searchType($type_id)
    {
        $s =$this->connection->fetchAssoc('SELECT * FROM sensor_type WHERE type_id = ?', array($type_id));

        //print_r($result);
        $type = new Type();

        if ($s != null) {
            $type->setTypeId($s["type_id"]);
            $type->setType($s["type"]);
            $type->setResInterval($s["res_intervel"]);

        }else{
            return false;
        }

        return $type;
    }

    public function getAllTypes()
    {
        $result = $this->connection->executeQuery('SELECT * FROM sensor_type');
        $result = $result->fetchAll();

        //print_r($result);
        $types[] = new Type();

        foreach ($result as $s) {

            if ($s != null) {
                $type = new Type();
                $type->setTypeId($s["type_id"]);
                $type->setType($s["type"]);
                $type->setResInterval($s["res_intervel"]);
                $types[] = $type;
            }

        }
    }

    public function getAllTypeNames(){
        $result = $this->connection->executeQuery('SELECT type_id , type FROM sensor_type');
        $result = $result->fetchAll();

        //print_r($result);
        foreach( $result as $s){

            if ($s != null) {

                $types[$s["type"]] = $s["type_id"];
            }
        }
        return $types;
    }
}