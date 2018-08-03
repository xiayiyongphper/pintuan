<?php

namespace service\tools\quote;

use common\models\SalesRuleUserCoupon;
use service\tools\Tools;

/**
 * Class Quote
 *
 */
class Quote
{
    protected $_availableCoupons = [];
    protected $_unavailableCoupons = [];
    protected $_items = [];

    protected $discountAmount;  //优惠金额
    private $amount; //订单金额，优惠前
    private $payable_amount; //订单金额，优惠后

    protected $userId;
    protected $itemsCount;
    protected $couponId;
    protected $ruleIds = [];

    protected $includeNewUserProduct; //是否包含新人专享商品

    /** @var  SalesRuleUserCoupon */
    private $coupon;  //使用的优惠券

    public function getCoupon()
    {
        return $this->coupon;
    }

    public function setCoupon($coupon)
    {
        $this->coupon = $coupon;
    }

    /**
     * @return mixed
     */
    public function getItemsCount()
    {
        return $this->itemsCount;
    }

    /**
     * @param mixed $itemsCount
     */
    public function setItemsCount($itemsCount)
    {
        $this->itemsCount = $itemsCount;
    }

    /**
     * @return mixed
     */
    public function getCouponId()
    {
        return $this->couponId;
    }

    /**
     * @param mixed $couponId
     */
    public function setCouponId($couponId)
    {
        $this->couponId = $couponId;
    }

    /**
     * @return mixed
     */
    public function getDiscountAmount()
    {
        return $this->discountAmount;
    }

    /**
     * @param mixed $discountAmount
     */
    public function setDiscountAmount($discountAmount)
    {
        $this->discountAmount = $discountAmount;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }


    public function addItem(Item $item)
    {
        if (empty($item)) {
            return false;
        }
        $this->_items[] = $item;
        return true;
    }

    public function getItems()
    {
        return $this->_items;
    }

    public function collectTotals()
    {
        /** @var Item $item */
        foreach ($this->getItems() as $item) {
            /** @var $item Item */
            $this->setItemsCount($this->getItemsCount() + 1);
            $item->calcRowTotal();
            $this->setAmount($this->getAmount() + $item->getRowTotal());
        }
        $this->setPayableAmount($this->getAmount()); //实付金额
        $discount = new Discount();
        $discount->collect($this);
        return $this;
    }

    public function init()
    {
        $this->setDiscountAmount(0);
        $this->setItemsCount(0);
        $this->setAmount(0);
        $this->setPayableAmount(0);
        $this->setIncludeNewUserProduct(0);
        return $this;
    }

    /**
     * @param SalesRuleUserCoupon $coupon
     * @return $this
     */
    public function addAvailableCoupons($coupon)
    {
        $this->_availableCoupons[] = Tools::formatCoupon($coupon,SalesRuleUserCoupon::CAN_USED);
        return $this;
    }

    /**
     * @param SalesRuleUserCoupon $coupon
     * @param string $reason
     * @return $this
     */
    public function addUnavailableCoupons($coupon, $reason = SalesRuleUserCoupon::UNAVAILABLE_REASON_7)
    {
        $coupon = Tools::formatCoupon($coupon,SalesRuleUserCoupon::CANNOT_USED);
        $coupon->setUnavailableReason($reason);
        $this->_unavailableCoupons[] = $coupon;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount)
    {
        if ($amount <= 0) {
            $amount = 0;
        }
        $this->amount = $amount;
    }

    /**
     * @return array
     */
    public function getAvailableCoupons()
    {
        return $this->_availableCoupons;
    }

    /**
     * @return array
     */
    public function getUnavailableCoupons()
    {
        return $this->_unavailableCoupons;
    }

    /**
     * @return mixed
     */
    public function getPayableAmount()
    {
        return $this->payable_amount;
    }

    /**
     * @param mixed $payable_amount
     */
    public function setPayableAmount($payable_amount)
    {
        if ($payable_amount <= 0) {
            $payable_amount = 0;
        }
        $this->payable_amount = $payable_amount;
    }

    /**
     * @return mixed
     */
    public function getRuleIds()
    {
        return $this->ruleIds;
    }

    /**
     * @param mixed $ruleIds
     */
    public function setRuleIds($ruleIds)
    {
        $this->ruleIds = $ruleIds;
    }

    /**
     * @return mixed
     */
    public function getIncludeNewUserProduct()
    {
        return $this->includeNewUserProduct;
    }

    /**
     * @param mixed $includeNewUserProduct
     */
    public function setIncludeNewUserProduct($includeNewUserProduct)
    {
        $this->includeNewUserProduct = $includeNewUserProduct;
    }

}
