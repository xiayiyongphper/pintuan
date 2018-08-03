<?php

namespace framework\components\Mathematics;

/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2015/3/7
 * Time: 16:30
 */
class Point
{
    /**
     * @var float
     */
    protected $_x;
    /**
     * @var float
     */
    protected $_y;

    /**
     * @param $x
     * @param $y
     */
    public function __construct($x, $y)
    {
        $this->_x = $x;
        $this->_y = $y;
    }

    /**
     * @return float
     */
    public function getX()
    {
        return $this->_x;
    }

    /**
     * @param float $x
     */
    public function setX($x)
    {
        $this->_x = $x;
    }

    /**
     * @return float
     */
    public function getY()
    {
        return $this->_y;
    }

    /**
     * @param float $y
     */
    public function setY($y)
    {
        $this->_y = $y;
    }

}