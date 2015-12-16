<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:26 AM
 */

namespace AppBundle\Entity\report;


class WindReading extends SensorReading
{
    private $wind_speed;
    private $direction;

    /**
     * @return mixed
     */
    public function getWindSpeed()
    {
        return $this->wind_speed;
    }

    /**
     * @param mixed $wind_speed
     */
    public function setWindSpeed($wind_speed)
    {
        $this->wind_speed = $wind_speed;
    }

    /**
     * @return mixed
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param mixed $direction
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;
    }

}