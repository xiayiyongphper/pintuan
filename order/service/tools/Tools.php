<?php

namespace service\tools;

use common\models\SalesRule;
use common\models\SalesRuleUserCoupon;
use framework\components\ToolsAbstract;
use message\common\Coupon;

class Tools extends ToolsAbstract
{
    public static function getFirstImage($images)
    {
        $image = explode(';', $images);
        return array_shift($image);
    }


    /**
     * @param SalesRuleUserCoupon $coupon
     * @param int $tag
     * @return Coupon
     */
    public static function formatCoupon($coupon, $tag = SalesRuleUserCoupon::CAN_USED)
    {
        $couponData = new Coupon();
        $couponData->setId($coupon->id);
        $couponData->setRuleId($coupon->rule_id);
        $couponData->setExpirationDate($coupon->expiration_date);
        $couponData->setTitle($coupon->title);
        $couponData->setDiscountAmount($coupon->discount_amount / 100);
        $couponData->setCondition($coupon->condition / 100);
        $couponData->setSalesRuleScope($coupon->sales_rule_scope);
        switch ($coupon->coupon_type) {
            case SalesRule::NEW_USER_TYPE:
                $tag_img = SalesRuleUserCoupon::getTag($tag);
                $couponData->setCouponTag($tag_img[SalesRule::NEW_USER_TYPE]);
                break;
            case SalesRule::PROMOTION_TYPE:
                $tag_img = SalesRuleUserCoupon::getTag($tag);
                $couponData->setCouponTag($tag_img[SalesRule::PROMOTION_TYPE]);
                break;
            case SalesRule::SHARE_TYPE:
                $tag_img = SalesRuleUserCoupon::getTag($tag);
                $couponData->setCouponTag($tag_img[SalesRule::SHARE_TYPE]);
                break;
            default:
                break;
        }
        //有效期
        $start_time = date("Y-m-d", strtotime($coupon->created_at));
        $end_time = date("Y-m-d", strtotime($coupon->expiration_date));

        $validity_time = $start_time . " 至 " . $end_time;
        $couponData->setValidityTime($validity_time);
        return $couponData;
    }


}