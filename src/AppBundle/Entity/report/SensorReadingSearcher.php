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
    protected $readingLimit;

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
    public function getReadingLimit()
    {
        return $this->readingLimit;
    }

    /**
     * @param mixed $readingLimit
     */
    public function setReadingLimit($readingLimit)
    {
        $this->readingLimit = $readingLimit;
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