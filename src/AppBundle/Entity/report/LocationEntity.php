<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:23 AM
 */

namespace AppBundle\Entity\report;

class LocationEntity
{
    private $location_id;
    private $address;
    private $longitude;
    private $latitude;
    private $area_code;

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getAreaCode()
    {
        return $this->area_code;
    }

    /**
     * @param mixed $area_code
     */
    public function setAreaCode($area_code)
    {
        $this->area_code = $area_code;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * @param mixed $location_id
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }


}