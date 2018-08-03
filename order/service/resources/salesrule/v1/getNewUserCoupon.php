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
use message\order\getNewUserCouponRequest;
use message\order\getNewUserCouponResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\salesrule\NewUserCoupon;
use service\tools\salesrule\SendCoupon;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 * 赠送新人券，发现是新人时请求
 */
class getNewUserCoupon extends ResourceAbstract
{
    /** @var getNewUserCouponRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $response = self::response();
        $user_id = $this->request->getUserId();

        if (!$user_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }

        $coupon = SalesRuleUserCoupon::find()->where(['user_id' => $user_id])
            ->andWhere(['coupon_type' => SalesRule::NEW_USER_TYPE])->exists();
//        Tools::log($coupon,'getNewUserCoupon.log');
        //已经领取过，新人类型的优惠券，无法再次领取
        if ($coupon) {
            return $response;
        }
//        Tools::log('send','getNewUserCoupon.log');
        //领取过程中出现异常
        try {
            /** @var Coupon $salesRule */
            $coupon = new NewUserCoupon($user_id);
            $sendCoupon = new SendCoupon($coupon);
            $sendCoupon->sendNewUserCoupon();
        } catch (\Exception $e) {
            Tools::logException($e);
            return $response;
        } catch (\Error $e) {
            Tools::logError($e);
            return $response;
        }
//        Tools::log('end','getNewUserCoupon.log');
        //返回刚刚领取的优惠券
        $userCoupons = SalesRuleUserCoupon::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['rule_id' => $coupon->salesRule->id])
            ->all();

        $total = 0;
        /** @var SalesRuleUserCoupon $userCoupon */
        foreach ($userCoupons as $userCoupon){
            $couponData = Tools::formatCoupon($userCoupon);
            $response->appendCouponList($couponData);
            $total = $total + $userCoupon->discount_amount;
        }
        //总优惠金额
        $total = $total / 100;
        $response->setDiscountTotal($total);

        return $response;
    }

    public static function request()
    {
        return new getNewUserCouponRequest();
    }

    public static function response()
    {
        return new getNewUserCouponResponse();
    }

}
