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
    private $temp_value;

    /**
     * @return mixed
     */
    public function getTempValue()
    {
        return $this->temp_value;
    }

    /**
     * @param mixed $temp_value
     */
    public function setTempValue($temp_value)
    {
        $this->temp_value = $temp_value;
    }

}