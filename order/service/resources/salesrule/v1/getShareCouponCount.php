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
use yii\db\Query;

/**
 * Author: Jason Y. Wang
 * Class orderNumber
 * @package service\resources\order\v1
 * 分享券数量
 */
class getShareCouponCount extends ResourceAbstract
{
    /** @var getShareCouponRequest $request */
    protected $request;

    public function run($data)
    {
        $this->doInit($data);
        $response = self::response();
        $user_id = $this->request->getUserId();
        $pintuan_id = $this->request->getPintuanId();
        if (!$user_id || !$pintuan_id) {
            Exception::throwException(Exception::INVALID_PARAM);
        }
        Tools::log($this->request->toArray(), 'getShareCouponCount.log');
        //判断小团拼团有没有领取过分享优惠券
        //领取过分享优惠券，记录领取到缓存，再次领取时，判断是否领取过
        $count = SalesRuleUserCoupon::getShareCouponReceive($user_id, $pintuan_id);
        Tools::log('领取次数:' . $count, 'getShareCouponCount.log');
        if ($count >= 1) {
            //没有找到可以领取的优惠券'
            Tools::log('无可领优惠券', 'getShareCouponCount.log');
            return $response->setCount(0);
        }
        //只查询最多10个分享活动
        $salesRules = SalesRule::find()
            ->andWhere(['coupon_type' => SalesRule::SHARE_TYPE])
            ->andWhere(['status' => SalesRule::STATUS_ENABLE])
            ->andWhere(['del' => SalesRule::NOT_DELETED])
            ->limit(10)
            ->all();

        /** @var SalesRule $salesRule */
        foreach ($salesRules as $salesRule) {
            $globalCount = SalesRuleUserCoupon::getSalesRuleReceiveCount($salesRule->id);
            if ($globalCount >= $salesRule->uses_per_coupon) {
                continue;
            }
            $perUserCount = SalesRuleUserCoupon::getUserReceiveCount($salesRule->id, $user_id);
            if ($perUserCount >= $salesRule->uses_per_customer) {
                continue;
            }
            //找到可以领取的优惠券
            return $response->setCount(1);
        }
        Tools::log('没有找到可领优惠券', 'getShareCouponCount.log');
        //没有找到可以领取的优惠券
        return $response->setCount(0);
    }

    public static function request()
    {
        return new getShareCouponRequest();
    }

    public static function response()
    {
        return new getShareCouponResponse();
    }

}
