<?php
/**
 * Created by Jason.
 * Author: Jason Y. Wang
 * Date: 2016/3/3
 * Time: 16:26
 */

namespace service\resources\salesrule\v1;

use common\models\SalesRule;
use common\models\SalesRuleUserCoupon;
use message\common\Coupon;
use message\common\Pagination;
use message\order\getNewUserCouponRequest;
use message\order\getNewUserCouponResponse;
use message\order\getPromotionCouponRequest;
use message\order\getPromotionCouponResponse;
use message\order\getShareCouponRequest;
use message\order\getShareCouponResponse;
use message\order\getUserCouponListRequest;
use message\order\getUserCouponListResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\salesrule\NewUserCoupon;
use service\tools\salesrule\PromotionCoupon;
use service\tools\salesrule\SendCoupon;
use service\tools\salesrule\ShareCoupon;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 * 获取促销券列表
 */
class getPromotionCouponList extends ResourceAbstract
{
    /** @var getPromotionCouponRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $response = self::response();
        $user_id = $this->request->getUserId();
        $rule_id = $this->request->getRuleId();
        if (!$user_id || !$rule_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }

        //是否领取过优惠券
        /** @var SalesRuleUserCoupon $coupons */
        $coupons = SalesRuleUserCoupon::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['rule_id' => $rule_id])
            ->andWhere(['coupon_type' => SalesRule::PROMOTION_TYPE])
            ->all();

        $response->setBackgroundImg('http://assets.lelai.com/assets/coupon/huodong_bg.png');
        $response->setBackgroundColor('#FFBA98');

        //已经领取过优惠券，直接返回优惠券列表
        //已经领取过，显示去使用按钮，未领取显示领去按钮
        if ($coupons) {
            foreach ($coupons as $coupon) {
                $couponData = Tools::formatCoupon($coupon);
                $response->appendCouponList($couponData);
            }
            $response->setType(2); //待使用优惠券
            return $response;
        } else {
            /** @var SalesRule $salesRule */
            $salesRule = SalesRule::find()
                ->where(['id' => $rule_id])
                ->andWhere(['coupon_type' => SalesRule::PROMOTION_TYPE])
                ->andWhere(['status' => SalesRule::STATUS_ENABLE])
                ->andWhere(['del' => SalesRule::NOT_DELETED])
                ->one();
            if (!$salesRule) {
                Exception::throwException(Exception::SALES_RULE_NOT_EXIST);
            }

            $uses_per_coupon = $salesRule->uses_per_coupon;
            $uses_per_coupon_receive = SalesRuleUserCoupon::getSalesRuleReceiveCount($rule_id);
            $receive_out = 0;
            if($uses_per_coupon_receive >= $uses_per_coupon){
                //已经领光
                $receive_out = 1;
            }

            //最多显示和领取10张优惠券
            $uses_per_customer = $salesRule->uses_per_customer;
            if ($uses_per_customer > 10) {
                $uses_per_customer = 10;
            }
            while ($uses_per_customer) {
                $couponData = new Coupon();
                $couponData->setRuleId($salesRule->id);
                $couponData->setTitle($salesRule->title);
                $couponData->setReceiveOut($receive_out);
                $couponData->setDiscountAmount($salesRule->discount_amount / 100);
                $couponData->setCondition($salesRule->condition / 100);
                $couponData->setCouponTag(SalesRuleUserCoupon::$coupon_tag[SalesRule::PROMOTION_TYPE]);
                $response->appendCouponList($couponData);
                $uses_per_customer--;
            }
            $response->setType(1); //待领取优惠券
            return $response;
        }
    }

    public static function request()
    {
        return new getPromotionCouponRequest();
    }

    public static function response()
    {
        return new getPromotionCouponResponse();
    }

}
