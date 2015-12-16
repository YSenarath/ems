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
    protected $sensorId;
    protected $timestamp;

    /**
     * @return mixed
     */
    public function getSensorId()
    {
        return $this->sensorId;
    }

    /**
     * @param mixed $sensorId
     */
    public function setSensorId($sensorId)
    {
        $this->sensorId = $sensorId;
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