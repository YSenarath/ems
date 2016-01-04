<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 5:11 PM
 */

namespace AppBundle\Entity\sensor;


class Type
{

    protected $type_name;
    protected $res_interval;

    /**
     * @return mixed
     */
    public function getTypeName()
    {
        return $this->type_name;
    }

    /**
     * @param mixed $type_name
     */
    public function setTypeName($type_name)
    {
        $this->type_name = $type_name;
    }


    /**
     * @return mixed
     */
    public function getResInterval()
    {
        return $this->res_interval;
    }

    /**
     * @param mixed $res_interval
     */
    public function setResInterval($res_interval)
    {
        $this->res_interval = $res_interval;
    }


}