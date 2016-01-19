<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 5:04 PM
 */

namespace AppBundle\Entity\sensor;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * @Assert\GroupSequence({"Sensor", "Strict", "Third" })
 */
class Sensor
{

    /**
     * @Assert\Length(
     *      max = 8,
     *      maxMessage = "Sensor ID cannot be longer than {{ limit }} characters",
     *
     * )
     */
    protected $sensor_id;


    /**
     * @Assert\Regex(
     *     pattern="/^[+-]?\d+(\.\d+)?$/",
     *     match=true,
     *     message="The value {{ value }} is not a valid Floating value.",
     *
     * )
     *
     * @Assert\Range(
     *      min = -10000000,
     *      max = 10000000,
     *      minMessage = "The min Threshold can be {{ limit }}",
     *      maxMessage = "The max Threshold can be {{ limit }}",
     *      groups={"Strict"}
     * )
     *
     */
    protected $t_min;

    /**
     * @Assert\Regex(
     *     pattern="/^[+-]?\d+(\.\d+)?$/",
     *     match=true,
     *     message="The value {{ value }} is not a valid Floating value.",
     *
     * )
     *
     * @Assert\Expression(
     *     value = "this.getTMax() > this.getTMin() or this.getTMax() == null or this.getTMin() == null",
     *     message="The Threshold max should be greater than Threshold Min",
     *     groups={"Third"}
     * )
     *
     * @Assert\Range(
     *      min = -10000000,
     *      max = 10000000,
     *      minMessage = "The min Threshold can be {{ limit }}",
     *      maxMessage = "The max Threshold can be {{ limit }}",
     *      groups={"Strict"}
     * )
     */
    protected $t_max;

    protected $loc_id;
    protected $type_name;
    protected $model_id;
    protected $ins_date;
    protected $locAddress;

    /**
     * @return mixed
     */
    public function getLocAddress()
    {
        return $this->locAddress;
    }

    /**
     * @param mixed $locAddress
     */
    public function setLocAddress($locAddress)
    {
        $this->locAddress = $locAddress;
    }


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