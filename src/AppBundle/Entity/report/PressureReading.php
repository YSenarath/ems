<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:25 AM
 */

namespace AppBundle\Entity\report;


class PressureReading extends SensorReading
{
    private $pressure_value;

    /**
     * @return mixed
     */
    public function getPressureValue()
    {
        return $this->pressure_value;
    }

    /**
     * @param mixed $pressure_value
     */
    public function setPressureValue($pressure_value)
    {
        $this->pressure_value = $pressure_value;
    }

}