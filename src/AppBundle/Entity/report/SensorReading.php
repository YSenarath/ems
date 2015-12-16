<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:24 AM
 */

namespace AppBundle\Entity\report;


abstract class SensorReading
{
    protected $sensor_id;
    protected $timestamp;

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
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }


}