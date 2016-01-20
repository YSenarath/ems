<?php
/**
 * Created by PhpStorm.
 * User: Yasas
 * Date: 1/7/2016
 * Time: 8:14 AM
 */

namespace AppBundle\Entity\security;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class Employee
{
    /**
     * @Assert\Length(
     *      max = 8,
     *      maxMessage = "Employee ID cannot be longer than {{ limit }} characters"
     * )
     */
    protected $employee_id;

    /**
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "First name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $first_name;

    /**
     * @Assert\Length(
     *      max = 30,
     *      maxMessage = "Last name cannot be longer than {{ limit }} characters"
     * )
     */
    protected $last_name;

    /**
     * @Assert\Regex(
     *     pattern="/^\d{9}[vVxX]$/",
     *     match=true,
     *     message="The value {{ value }} is not a National ID number."
     * )
     *
     * @Assert\Length(
     *      max = 10,
     *      maxMessage = "NIC cannot be longer than {{ limit }} characters"
     * )
     */
    protected $NIC;

    /**
     * @Assert\Regex(
     *     pattern="/^\d{10}$/",
     *     match=true,
     *     message="The value {{ value }} is not a Telephone number."
     * )
     *
     * @Assert\Length(
     *      max = 11,
     *      maxMessage = "Tel. No cannot be longer than {{ limit }} characters"
     * )
     */
    protected $tel_no;

    /**
     * Employee constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    /**
     * @param mixed $employee_id
     */
    public function setEmployeeId($employee_id)
    {
        $this->employee_id = $employee_id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * @param mixed $first_name
     */
    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * @param mixed $last_name
     */
    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
    }

    /**
     * @return mixed
     */
    public function getNIC()
    {
        return $this->NIC;
    }

    /**
     * @param mixed $NIC
     */
    public function setNIC($NIC)
    {
        $this->NIC = $NIC;
    }

    /**
     * @return mixed
     */
    public function getTelNo()
    {
        return $this->tel_no;
    }

    /**
     * @param mixed $tel_no
     */
    public function setTelNo($tel_no)
    {
        $this->tel_no = $tel_no;
    }


}