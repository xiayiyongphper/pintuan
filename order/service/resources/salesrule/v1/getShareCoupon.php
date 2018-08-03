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
use message\common\UniversalResponse;
use message\order\getNewUserCouponRequest;
use message\order\getNewUserCouponResponse;
use message\order\getShareCouponRequest;
use message\order\getShareCouponResponse;
use message\order\getUserCouponListRequest;
use message\order\getUserCouponListResponse;
use service\resources\Exception;
use service\resources\ResourceAbstract;
use service\tools\salesrule\NewUserCoupon;
use service\tools\salesrule\SendCoupon;
use service\tools\salesrule\ShareCoupon;
use service\tools\Tools;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 * 分享券
 */
class getShareCoupon extends ResourceAbstract
{
    /** @var getShareCouponRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $user_id = $this->request->getUserId();
        $pintuan_id = $this->request->getPintuanId();
        if (!$user_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        //领取过分享优惠券，记录领取到缓存，再次领取时，判断是否领取过,一个拼团只能领取一次
        $count = SalesRuleUserCoupon::getShareCouponReceive($user_id, $pintuan_id);
        if($count == 1){
            Exception::throwException(Exception::SALES_RULE_COUPON_RECEIVE_ALREADY);
        }
        $coupon = new ShareCoupon($user_id);
        $sendCoupon = new SendCoupon($coupon);
        $sendCoupon->sendShareCoupon();
        //领取过分享优惠券，记录领取到缓存，再次领取时，判断是否领取过
        SalesRuleUserCoupon::setShareCouponReceive($user_id, $pintuan_id);

        $response = self::response();
        $response->setCode(0);
        $response->setMessage('领取成功');
        return $response;
    }

    public static function request()
    {
        return new getShareCouponRequest();
    }

    public static function response()
    {
        return new UniversalResponse();
    }

}
