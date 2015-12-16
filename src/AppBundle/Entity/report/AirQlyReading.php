<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:25 AM
 */

namespace AppBundle\Entity\report;


class AirQlyReading extends SensorReading
{
    private $air_qty_percentage;
    private $oxygen_percentage;
    private $co2_percentage;

    /**
     * @return mixed
     */
    public function getAirQtyPercentage()
    {
        return $this->air_qty_percentage;
    }

    /**
     * @param mixed $air_qty_percentage
     */
    public function setAirQtyPercentage($air_qty_percentage)
    {
        $this->air_qty_percentage = $air_qty_percentage;
    }

    /**
     * @return mixed
     */
    public function getCo2Percentage()
    {
        return $this->co2_percentage;
    }

    /**
     * @param mixed $co2_percentage
     */
    public function setCo2Percentage($co2_percentage)
    {
        $this->co2_percentage = $co2_percentage;
    }

    /**
     * @return mixed
     */
    public function getOxygenPercentage()
    {
        return $this->oxygen_percentage;
    }

    /**
     * @param mixed $oxygen_percentage
     */
    public function setOxygenPercentage($oxygen_percentage)
    {
        $this->oxygen_percentage = $oxygen_percentage;
    }


}