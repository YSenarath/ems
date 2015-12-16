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

    protected $type_id;
    protected $type;
    protected $res_interval;

    /**
     * @return mixed
     */
    public function getTypeId()
    {
        return $this->type_id;
    }

    /**
     * @param mixed $type_id
     */
    public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
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