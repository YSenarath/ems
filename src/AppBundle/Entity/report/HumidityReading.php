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
    private $humidity_value;

    /**
     * @return mixed
     */
    public function getHumidityValue()
    {
        return $this->humidity_value;
    }

    /**
     * @param mixed $humidity_value
     */
    public function setHumidityValue($humidity_value)
    {
        $this->humidity_value = $humidity_value;
    }


}