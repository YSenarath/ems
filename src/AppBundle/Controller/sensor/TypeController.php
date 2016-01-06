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

    public function searchType($type_name)
    {
        $s =$this->connection->fetchAssoc('SELECT * FROM sensor_type WHERE type_name = ?', array($type_name));

        //print_r($result);
        $type = new Type();

        if ($s != null) {

            $type->setTypeName($s["type_name"]);
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


        $types[] = new Type();

        foreach ($result as $s) {

            if ($s != null) {
                $type = new Type();

                $type->setTypeName($s["type_name"]);
                $type->setResInterval($s["res_intervel"]);
                $types[] = $type;
            }

        }
        return $types;
    }

    public function getAllTypeNames(){
        $result = $this->connection->executeQuery('SELECT type_name FROM sensor_type');
        $result = $result->fetchAll();

        $types = null;
        //print_r($result);
        foreach( $result as $s){

            if ($s != null) {

                $types[$s["type_name"]] = $s["type_name"];
            }
        }
        return $types;
    }
}