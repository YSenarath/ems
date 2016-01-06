<?php
/**
 * Created by PhpStorm.
 * User: Dulanjaya Tennekoon
 */

namespace AppBundle\Entity\location;

class Location {
    protected $loc_id;
    protected $address;
    protected $longitude;
    protected $latitude;
    protected $area_code;

    /**
     * @return mixed
     */
    public function getLocId()
    {
        return $this->loc_id;
    }

    /**
     * @param mixed $loc_id
     */
    public function setLocId($loc_id)
    {
        $this->loc_id = $loc_id;
    }

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
     * @param mixed $lat
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $long
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }


}