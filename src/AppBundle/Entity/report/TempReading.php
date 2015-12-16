<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:24 AM
 */

namespace AppBundle\Entity\report;


class TempReading extends  SensorReading
{
    private $tempValue;

    /**
     * @return mixed
     */
    public function getTempValue()
    {
        return $this->tempValue;
    }

    /**
     * @param mixed $tempValue
     */
    public function setTempValue($tempValue)
    {
        $this->tempValue = $tempValue;
    }

}