<?php
/**
 * Created by PhpStorm.
 * User: wangyang
 * Date: 18-7-11
 * Time: 下午3:03
 */

namespace service\tools\salesrule;


use common\models\SalesRule;
use common\models\SalesRuleUserCoupon;
use service\resources\Exception;
use tests\service\resources\sales\v1\getAreaBrandTest;

class ShareCoupon extends Coupon
{
    public function __construct($user_id)
    {
        //只查询最多10个分享活动
        $salesRules = SalesRule::find()
            ->andWhere(['coupon_type' => SalesRule::SHARE_TYPE])
            ->andWhere(['status' => SalesRule::STATUS_ENABLE])
            ->andWhere(['del' => SalesRule::NOT_DELETED])
            ->limit(10)
            ->all();
        //打乱优惠顺序，模拟随机获取优惠券
        shuffle($salesRules);
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
            $this->salesRule = $salesRule;
            break;
        }
        $this->user_id = $user_id;
    }
}