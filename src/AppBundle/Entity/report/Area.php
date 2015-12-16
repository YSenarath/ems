<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 12/16/2015
 * Time: 12:21 AM
 */

namespace AppBundle\Entity\report;

class Area
{
    private $areaCode;
    private $name;
    private $areaSize;

    /**
     * @return mixed
     */
    public function getAreaCode()
    {
        return $this->areaCode;
    }

    /**
     * @param mixed $areaCode
     */
    public function setAreaCode($areaCode)
    {
        $this->areaCode = $areaCode;
    }

    /**
     * @return mixed
     */
    public function getAreaSize()
    {
        return $this->areaSize;
    }

    /**
     * @param mixed $areaSize
     */
    public function setAreaSize($areaSize)
    {
        $this->areaSize = $areaSize;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

}