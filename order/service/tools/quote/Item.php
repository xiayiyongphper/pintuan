<?php

namespace service\tools\quote;

use service\tools\Tools;


/**
 * Class Item
 * @package service\tools\quote
 */
class Item
{

    private $price;
    private $dealPrice;
    private $number;
    private $rowTotal;

    public function calcRowTotal()
    {
        $total = $this->getDealPrice() * $this->getNumber();
        $this->setRowTotal($total); //该商品总价(优惠前)
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * @return mixed
     */
    public function getRowTotal()
    {
        return $this->rowTotal;
    }

    /**
     * @param mixed $rowTotal
     */
    public function setRowTotal($rowTotal)
    {
        $this->rowTotal = $rowTotal;
    }

    /**
     * @return mixed
     */
    public function getDealPrice()
    {
        return $this->dealPrice;
    }

    /**
     * @param mixed $dealPrice
     */
    public function setDealPrice($dealPrice)
    {
        $this->dealPrice = $dealPrice;
    }
}
