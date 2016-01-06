<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 5:04 PM
 */

namespace AppBundle\Entity\sensor;


class Sensor
{

    protected $sensor_id;
    protected $t_min;
    protected $t_max;
    protected $loc_id;
    protected $type_name;
    protected $model_id;
    protected $ins_date;

    /**
     * @return mixed
     */
    public function getSensorId()
    {
        return $this->sensor_id;
    }

    /**
     * @param mixed $sensor_id
     */
    public function setSensorId($sensor_id)
    {
        $this->sensor_id = $sensor_id;
    }

    /**
     * @return mixed
     */
    public function getTMin()
    {
        return $this->t_min;
    }

    /**
     * @param mixed $t_min
     */
    public function setTMin($t_min)
    {
        $this->t_min = $t_min;
    }

    /**
     * @return mixed
     */
    public function getTMax()
    {
        return $this->t_max;
    }

    /**
     * @param mixed $t_max
     */
    public function setTMax($t_max)
    {
        $this->t_max = $t_max;
    }

    /**
     * @return mixed
     */
    public function getLocId()
    {
        return $this->loc_id;
    }

    /**
     * @param mixed $loc_id
     */
    public function setLocId($loc_id)
    {
        $this->loc_id = $loc_id;
    }

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
    public function getModelId()
    {
        return $this->model_id;
    }

    /**
     * @param mixed $model_id
     */
    public function setModelId($model_id)
    {
        $this->model_id = $model_id;
    }

    /**
     * @return mixed
     */
    public function getInsDate()
    {
        return $this->ins_date;
    }

    /**
     * @param mixed $ins_date
     */
    public function setInsDate($ins_date)
    {
        $this->ins_date = $ins_date;
    }



}