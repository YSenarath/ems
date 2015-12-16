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
    private $pressureValue;

    /**
     * @return mixed
     */
    public function getPressureValue()
    {
        return $this->pressureValue;
    }

    /**
     * @param mixed $pressureValue
     */
    public function setPressureValue($pressureValue)
    {
        $this->pressureValue = $pressureValue;
    }

}