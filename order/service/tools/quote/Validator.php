<?php

namespace service\tools\quote;

use common\models\SalesRuleUserCoupon;
use service\resources\Exception;
use service\tools\Tools;
use yii\base\Object;


/**
 * Class Validator
 * @package service\tools\quote
 *
 */
class Validator
{
    private $_coupons = [];
    private $_quote = null;

    public function init()
    {
        //我的优惠券列表
        $this->_coupons = SalesRuleUserCoupon::find()
            ->where(['state' => SalesRuleUserCoupon::USER_COUPON_UNUSED])
            ->andWhere(['del' => SalesRuleUserCoupon::NOT_DELETED])
            ->andWhere(['>', 'expiration_date', Tools::getDate()])
            ->andWhere(['<', 'created_at', Tools::getDate()])
            ->andWhere(['user_id' => $this->getQuote()->getUserId()])
            ->limit(50)//最多计算50个优惠券
            ->all();

        //使用指定优惠券
        if ($this->getQuote()->getCouponId()) {
            $includeNewUserProduct = $this->getQuote()->getIncludeNewUserProduct();  //1有新人活动商品  0没有新人活动商品
            /** @var SalesRuleUserCoupon $coupon */
            $coupon = SalesRuleUserCoupon::find()
                ->where(['id' => $this->getQuote()->getCouponId(), 'state' => SalesRuleUserCoupon::USER_COUPON_UNUSED])
                ->andWhere(['del' => SalesRuleUserCoupon::NOT_DELETED])
                ->andWhere(['user_id' => $this->getQuote()->getUserId()])
                ->one();

            if (!$coupon) {
                Exception::throwException(Exception::SALES_RULE_COUPON_CANNOT_USE);
            }

            //活动互斥判断
            if ($includeNewUserProduct &&  $coupon->activity_exclude == SalesRuleUserCoupon::ACTIVITY_EXCLUDE) {
                Exception::throwException(Exception::SALES_RULE_COUPON_CANNOT_USE);
                return true;
            }

            //没有享受这个优惠券的商品
            if ($coupon->sales_rule_scope == SalesRuleUserCoupon::COUPON_SCOPE_PRODUCT && !in_array($coupon->rule_id, $this->getQuote()->getRuleIds())) {
                Exception::throwException(Exception::SALES_RULE_COUPON_CANNOT_USE);
            }
            //使用条件验证
            if ($this->getQuote()->getAmount() < $coupon->condition) {
                Exception::throwException(Exception::SALES_RULE_COUPON_CANNOT_USE);
            }

            $this->getQuote()->setCoupon($coupon);

        }

        return $this;
    }

    public function initTotals()
    {
        $this->_initCouponTotals();
        return $this;
    }

    /**
     * 计算优惠券的规则
     * @return $this
     */
    private function _initCouponTotals()
    {
        $quote = $this->getQuote();

        /** @var SalesRuleUserCoupon $coupon */
        foreach ($this->_coupons as $coupon) {
            if (!$coupon || !$coupon->rule_id) {
                //优惠券查询不到对应的活动，跳过
                continue;
            }
            //代金券可用
            $this->processRule($coupon);
        }

        //指定优惠券，则使用
        if ($quote->getCoupon()) {
            $this->useCoupon();
        }

        return $this;
    }

    /**
     * @param SalesRuleUserCoupon $coupon
     * 检测优惠券是否可用
     * @return boolean
     */
    protected function processRule($coupon)
    {
        $quote = $this->getQuote();
        $amount = $quote->getAmount();
        $includeNewUserProduct = $quote->getIncludeNewUserProduct();  //1有新人活动商品  0没有新人活动商品
        //活动互斥判断
        if ($includeNewUserProduct &&  $coupon->activity_exclude == SalesRuleUserCoupon::ACTIVITY_EXCLUDE) {
            $quote->addUnavailableCoupons($coupon, SalesRuleUserCoupon::UNAVAILABLE_REASON_3);
            return true;
        }
        //没有享受这个优惠券的商品
        if ($coupon->sales_rule_scope == SalesRuleUserCoupon::COUPON_SCOPE_PRODUCT && !in_array($coupon->rule_id, $quote->getRuleIds())) {
            $quote->addUnavailableCoupons($coupon, SalesRuleUserCoupon::UNAVAILABLE_REASON_2);
            return true;
        }

        if ($amount < $coupon->condition) {
            $quote->addUnavailableCoupons($coupon, SalesRuleUserCoupon::UNAVAILABLE_REASON_1);
            return true;
        }

        $quote->addAvailableCoupons($coupon);
        return true;
    }


    /**
     * @return $this
     * @internal param SalesRuleUserCoupon $coupon
     */
    protected function useCoupon()
    {
        $quote = $this->getQuote();
//        Tools::log($quote->getAmount(), 'Validator.log');
//        Tools::log($quote->getCoupon()->discount_amount, 'Validator.log');
        //减去优惠金额
        $quote->setPayableAmount($quote->getAmount() - $quote->getCoupon()->discount_amount);
        $quote->setDiscountAmount($quote->getCoupon()->discount_amount);
//        Tools::log('优惠券使用成功:' . $quote->getCoupon()->discount_amount, 'Validator.log');
        return $this;
    }

    /**
     * @param Quote $quote
     * @param $discountAmount
     * @return $this
     */
    protected function setDiscountAmount($quote, $discountAmount)
    {
        $quote->setDiscountAmount($quote->getDiscountAmount() + $discountAmount);
        return $this;
    }

    /**
     * @return Quote
     */
    public function getQuote()
    {
        return $this->_quote;
    }

    /**
     * @param $quote
     * @return Validator
     */
    public function setQuote($quote)
    {
        $this->_quote = $quote;
        return $this;
    }
}
