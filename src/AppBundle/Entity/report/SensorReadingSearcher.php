<?php
/**
 * Created by PhpStorm.
 * User: Shehan
 * Date: 1/2/2016
 * Time: 11:47 AM
 */

namespace AppBundle\Entity\report;

use Symfony\Component\Validator\Constraints as Assert;


class SensorReadingSearcher
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="integer")
     */
    protected $noOfReadings;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    protected $startDate;

    /**
     * @Assert\NotBlank()
     * @Assert\Type("\DateTime")
     */
    protected $endDate;

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getNoOfReadings()
    {
        return $this->noOfReadings;
    }

    /**
     * @param mixed $noOfReadings
     */
    public function setNoOfReadings($noOfReadings)
    {
        $this->noOfReadings = $noOfReadings;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

}