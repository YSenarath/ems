<?php
/**
 * Created by PhpStorm.
 * User: Nadheesh
 * Date: 12/16/2015
 * Time: 5:09 PM
 */

namespace AppBundle\Entity\sensor;

use Symfony\Component\Validator\Constraints as Assert;

class Model
{

    /**
     * @Assert\Length(
     *      max = 8,
     *      maxMessage = "Model ID cannot be longer than {{ limit }} characters"
     * )
     */
    protected $model_id;
    protected $manufacture;
    protected $unit;


    /**
     * @Assert\Regex(
     *     pattern="/^[+-]?\d+(\.\d+)?$/",
     *     match=true,
     *     message="The value {{ value }} is not a valid Floating value."
     * )
     *
     * @Assert\Range(
     *      min= 0,
     *      max = 20000000,
     *      maxMessage = "The max Detection Range can be {{ limit }}",
     *      minMessage = "Detection Range can't be negative"
     * )
     */
    protected $det_range;


    /**
     * @return mixed
     */
    public function getModelId()
    {
        return $this->model_id;
    }

    /**
     * @param mixed $model_id
     */
    public function setModelId($model_id)
    {
        $this->model_id = $model_id;
    }

    /**
     * @return mixed
     */
    public function getManufacture()
    {
        return $this->manufacture;
    }

    /**
     * @param mixed $manufacture
     */
    public function setManufacture($manufacture)
    {
        $this->manufacture = $manufacture;
    }

    /**
     * @return mixed
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
    }

    /**
     * @return mixed
     */
    public function getDetRange()
    {
        return $this->det_range;
    }

    /**
     * @param mixed $det_range
     */
    public function setDetRange($det_range)
    {
        $this->det_range = $det_range;
    }


}