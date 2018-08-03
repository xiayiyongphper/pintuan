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
 * 领取促销券
 */
class getPromotionCoupon extends ResourceAbstract
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

        $coupon = SalesRuleUserCoupon::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['rule_id' => $rule_id])
            ->andWhere(['coupon_type' => SalesRule::PROMOTION_TYPE])
            ->exists();

        //已经领取过
        if ($coupon) {
            Exception::throwException(Exception::SALES_RULE_COUPON_RECEIVE_ALREADY);
        }

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

        //领取过程中出现异常
        try {
            /** @var Coupon $coupon */
            $coupon = new PromotionCoupon($user_id, $rule_id);
            $sendCoupon = new SendCoupon($coupon);
            $sendCoupon->sendPromotionCoupon();
        } catch (\Exception $e) {
            Tools::logException($e);
            return $response;
        } catch (\Error $e) {
            Tools::logError($e);
            return $response;
        }

        //领取成功后
        /** @var SalesRuleUserCoupon $userCoupon */
        $userCoupons = SalesRuleUserCoupon::find()
            ->where(['user_id' => $user_id])
            ->andWhere(['rule_id' => $rule_id])
            ->all();
        foreach ($userCoupons as $userCoupon) {
            $couponData = Tools::formatCoupon($userCoupon);
            $response->appendCouponList($couponData);
        }

        return $response;
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
