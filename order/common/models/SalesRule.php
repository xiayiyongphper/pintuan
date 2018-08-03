<?php

namespace common\models;

use framework\db\ActiveRecord;
use Yii;

/**
 * Class SalesRule
 * @package common\models
 * @property int $id 规则id
 * @property string $title 标题
 * @property string $description 描述
 * @property int $uses_per_customer 每张券每用户可领取次数限制
 * @property int $condition 条件金额（分）
 * @property int $discount_amount 优惠金额（分）
 * @property int $type 活动类型 1：订单级 2：商品级
 * @property int $coupon_type 1:新人券 2:促销券 3:分享券
 * @property int $uses_per_coupon  每张券全局可被领取次数
 * @property int $status 1:正在生效 2:失效
 * @property int $effective_day 有效天数
 * @property int $activity_exclude 与活动是否互斥  1:是 2:否
 * @property int $sales_rule_scope 1：全场通用 2：部分商品可用
 *
 */
class SalesRule extends ActiveRecord
{

    const STATUS_ENABLE = 1; //生效
    const STATUS_DISABLE = 2; //失效

    const NEW_USER_TYPE = 1; //新人券
    const PROMOTION_TYPE = 2; //促销券
    const SHARE_TYPE = 3; //分享券

    const NOT_DELETED = 1; //未删除
    const DELETED = 2; //已经删除

    public static function tableName()
    {
        return 'salesrule';
    }

    public static function getDb()
    {
        return Yii::$app->get('orderDb');
    }
}
