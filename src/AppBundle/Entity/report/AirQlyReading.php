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
    private $airQtyPercentage;
    private $oxygenPercentage;
    private $co2Percentage;

    /**
     * @return mixed
     */
    public function getAirQtyPercentage()
    {
        return $this->airQtyPercentage;
    }

    /**
     * @param mixed $airQtyPercentage
     */
    public function setAirQtyPercentage($airQtyPercentage)
    {
        $this->airQtyPercentage = $airQtyPercentage;
    }

    /**
     * @return mixed
     */
    public function getCo2Percentage()
    {
        return $this->co2Percentage;
    }

    /**
     * @param mixed $co2Percentage
     */
    public function setCo2Percentage($co2Percentage)
    {
        $this->co2Percentage = $co2Percentage;
    }

    /**
     * @return mixed
     */
    public function getOxygenPercentage()
    {
        return $this->oxygenPercentage;
    }

    /**
     * @param mixed $oxygenPercentage
     */
    public function setOxygenPercentage($oxygenPercentage)
    {
        $this->oxygenPercentage = $oxygenPercentage;
    }


}