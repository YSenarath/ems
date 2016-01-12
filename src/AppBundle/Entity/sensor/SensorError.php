<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 1/13/2016
 * Time: 12:38 AM
 */

namespace AppBundle\Entity\sensor;

use Symfony\Component\Validator\Constraints as Assert;


class SensorError
{

    protected $sensor_id;
    protected $report_id;

    /**
     * @Assert\Length(
     *      max = 250,
     *      maxMessage = "Error Description cannot be longer than {{ limit }} characters"
     * )
     */
    protected $error_desc;


    /**
     * @Assert\Type(
     *     type="bool",
     *     message="The value {{ value }} is not a valid {{ type }}."
     * )
     */
    protected $is_fixed;


    protected $timestamp;
    protected $location;
    protected $type;

    /**
     * @return mixed
     */
    public function getSensorId()
    {
        return $this->sensor_id;
    }

    /**
     * @param mixed $sensor_id
     */
    public function setSensorId($sensor_id)
    {
        $this->sensor_id = $sensor_id;
    }

    /**
     * @return mixed
     */
    public function getReportId()
    {
        return $this->report_id;
    }

    /**
     * @param mixed $report_id
     */
    public function setReportId($report_id)
    {
        $this->report_id = $report_id;
    }

    /**
     * @return mixed
     */
    public function getErrorDesc()
    {
        return $this->error_desc;
    }

    /**
     * @param mixed $error_desc
     */
    public function setErrorDesc($error_desc)
    {
        $this->error_desc = $error_desc;
    }

    /**
     * @return mixed
     */
    public function getIsFixed()
    {
        return $this->is_fixed;
    }

    /**
     * @param mixed $is_fixed
     */
    public function setIsFixed($is_fixed)
    {
        $this->is_fixed = $is_fixed;
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

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

}