<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:26 AM
 */

namespace AppBundle\Entity\report;


class HumidityReading extends SensorReading
{
    private $humidityValue;

    /**
     * @return mixed
     */
    public function getHumidityValue()
    {
        return $this->humidityValue;
    }

    /**
     * @param mixed $humidityValue
     */
    public function setHumidityValue($humidityValue)
    {
        $this->humidityValue = $humidityValue;
    }


}