<?php

//namespace common\Mathematics\Algorithm;
namespace framework\components\Mathematics\Algorithm;

use framework\components\Mathematics\Point;

/**
 * Created by PhpStorm.
 * User: henry
 * Date: 2015/3/7
 * Time: 16:27
 */
class Polygon
{
    /**
     * how many corners the polygon has
     * @var int
     */
    protected $_polySidesCount;

    /**
     * horizontal coordinates of corners
     * @var array
     */
    protected $_polyX;

    /**
     * vertical coordinates of corners
     * @var array
     */
    protected $_polyY;

    /**
     * point to be tested
     * @var Point
     */
    protected $_point;
    /**
     * @var array
     */
    protected $_pointCollection ;

    public function __construct($_point)
    {
        $this->_point = $_point;
    }

    /**
     * @param Point $point
     * @throws Exception
     */
    public function addPoint($point)
    {
        if (!$this->_pointCollection) {
            $this->_pointCollection = array();
        }
        $this->_pointCollection[] = $point;
    }

    public function pointInPolygon()
    {
        $polySidesCount = $this->getPolySidesCount();
        $j = $polySidesCount - 1;
        $oddNodes = false;
        $x = $this->getPoint()->getX();
        $y = $this->getPoint()->getY();
        $polyY = $this->getPolyY();
        $polyX = $this->getPolyX();

        for ($i = 0; $i < $polySidesCount; $i++) {
            if ( ($polyY[$i] < $y && $polyY[$j] >= $y) || ($polyY[$j] < $y && $polyY[$i] >= $y) ) {
                if ($polyX[$i] + ($y - $polyY[$i]) / ($polyY[$j] - $polyY[$i]) * ($polyX[$j] - $polyX[$i]) < $x) {
                    $oddNodes = !$oddNodes;
                }
            }
            $j = $i;
        }
        
        return $oddNodes;
    }

    /**
     * @return Point
     */
    public function getPoint()
    {
        return $this->_point;
    }

    /**
     * @param Point $point
     */
    public function setPoint($point)
    {
        $this->_point = $point;
    }

    /**
     * @return int
     */
    public function getPolySidesCount()
    {
        if (is_null($this->_polySidesCount)) {
            $this->_polySidesCount = count($this->_pointCollection);
        }
        return $this->_polySidesCount;
    }

    /**
     * @param int $polySidesCount
     */
    public function setPolySidesCount($polySidesCount)
    {
        $this->_polySidesCount = $polySidesCount;
    }

    /**
     * @return array
     */
    public function getPolyX()
    {
        if (!$this->_polyX) {
            $this->_polyX = array();
            foreach ($this->_pointCollection as $point) {
                /* @var $point Point */
                $this->_polyX[] = $point->getX();
            }
        }
        return $this->_polyX;
    }

    /**
     * @param array $polyX
     */
    public function setPolyX($polyX)
    {
        $this->_polyX = $polyX;
    }

    /**
     * @return array
     */
    public function getPolyY()
    {
        if (!$this->_polyY) {
            $this->_polyY = array();
            foreach ($this->_pointCollection as $point) {
                /* @var $point Point */
                $this->_polyY[] = $point->getY();
            }
        }
        return $this->_polyY;
    }

    /**
     * @param array $polyY
     */
    public function setPolyY($polyY)
    {
        $this->_polyY = $polyY;
    }


}