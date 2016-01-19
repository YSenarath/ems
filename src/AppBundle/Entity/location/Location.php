<?php
/**
 * Created by PhpStorm.
 * User: Dulanjaya Tennekoon
 */

namespace AppBundle\Entity\location;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class Location {
    protected $id;
    protected $address;

    /**
     * @Assert\Regex(
     *     pattern="/^[+]?\d+(\.\d{1,6})?$/",
     *     match=true,
     *     message="The value {{ value }} is not a valid Floating value."
     * )
     *
     * @Assert\Range(
     *      min = 79.761600,
     *      max = 79.938755,
     *      minMessage = "The longitude is not withing the Colombo City Limits",
     *      maxMessage = "The longitude is not withing the Colombo City Limits"
     * )
     */
    protected $longitude;



    /**
     * @Assert\Regex(
     *     pattern="/^[+]?\d+(\.\d{1,6})?$/",
     *     match=true,
     *     message="The value {{ value }} is not a valid Floating value."
     * )
     *
     * @Assert\Range(
     *      min = 6.852805,
     *      max = 6.977547,
     *      minMessage = "The latitude is not withing the Colombo City Limits",
     *      maxMessage = "The latitude is not withing the Colombo City Limits"
     * )
     */
    protected $latitude;
    protected $area_code;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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