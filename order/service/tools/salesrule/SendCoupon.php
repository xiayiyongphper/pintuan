<?php

namespace service\tools\salesrule;

use common\models\SalesRuleUserCoupon;
use service\resources\Exception;

/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-7-11
 * Time: 下午2:57
 */
class SendCoupon
{
    protected $user_id;
    /** @var  Coupon $coupon */
    protected $coupon;

    /**
     * SendCoupon constructor.
     * @param Coupon $coupon
     */
    public function __construct($coupon)
    {
        if (empty($coupon)) {
            Exception::throwException(Exception::INVALID_REQUEST);
        }
        $this->user_id = $coupon->user_id;
        $this->coupon = $coupon;
    }

    public function sendPromotionCoupon()
    {
        $this->sendCoupon();
    }

    public function sendShareCoupon()
    {
        $this->generateCoupon();
    }

    public function sendNewUserCoupon()
    {
        $this->sendCoupon();
    }

    protected function sendCoupon(){
        $uses_per_customer = $this->coupon->salesRule->uses_per_customer;
        //最多发十张优惠券
        if ($uses_per_customer > 10) {
            $uses_per_customer = 10;
        }

        while ($uses_per_customer) {
            $this->generateCoupon();
            $uses_per_customer--;
        }
    }

    /**
     * @return bool
     */
    private function generateCoupon()
    {
        $salesRule = $this->coupon->salesRule;
        $effective_day = $salesRule->effective_day;
        if ($effective_day <= 0) {
            Exception::throwException(Exception::SALES_RULE_EFFECTIVE_DAY_ERROR);
        }
        $expiration_date = date('Y-m-d H:i:s', strtotime("+ $effective_day days"));
        $user_coupon = new SalesRuleUserCoupon();
        $user_coupon->user_id = $this->user_id;  //用户ID
        $user_coupon->state = SalesRuleUserCoupon::USER_COUPON_UNUSED;  //优惠券状态 1：未使用 2：已使用
        $user_coupon->rule_id = $salesRule->id; //规则ID
        $user_coupon->coupon_type = $salesRule->coupon_type;
        $user_coupon->expiration_date = $expiration_date;  //过期时间
        $user_coupon->source = SalesRuleUserCoupon::USER_RECEIVE;
        $user_coupon->condition = $salesRule->condition;
        $user_coupon->discount_amount = $salesRule->discount_amount;
        $user_coupon->title = $salesRule->title;
        $user_coupon->activity_exclude = $salesRule->activity_exclude; //是否与活动商品互斥1是2否
        $user_coupon->sales_rule_scope = $salesRule->sales_rule_scope; //1：全场通用 2：部分商品可用
        //检测有没有超出领取次数
        $this->validate();
        if (!$user_coupon->validate() || !$user_coupon->save()) {
            Exception::throwException(Exception::SALES_RULE_COUPON_RECEIVE_ERROR);
        }
        //增加缓存中的领取次数
        SalesRuleUserCoupon::setUserReceiveCount($this->coupon->salesRule->id, $this->user_id);
        SalesRuleUserCoupon::setSalesRuleReceiveCount($this->coupon->salesRule->id);
        return $user_coupon->id;
    }

    private function validate()
    {
        //用户领取次数
        $uses_per_customer = $this->coupon->salesRule->uses_per_customer;
        //全局领取次数
        $uses_per_coupon = $this->coupon->salesRule->uses_per_coupon;
        //该用户领取次数
        $couponUserCount = SalesRuleUserCoupon::getUserReceiveCount($this->coupon->salesRule->id, $this->user_id);
        //全局领取次数
        $couponCount = SalesRuleUserCoupon::getSalesRuleReceiveCount($this->coupon->salesRule->id);

        if ($couponUserCount >= $uses_per_customer) {
            Exception::throwException(Exception::SALES_RULE_COUPON_RECEIVE_MAX);
        }

        if ($couponCount >= $uses_per_coupon) {
            Exception::throwException(Exception::SALES_RULE_COUPON_RECEIVE_MAX);
        }
    }
}