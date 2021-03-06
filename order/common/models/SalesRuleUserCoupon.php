<?php

namespace common\models;

use framework\db\ActiveRecord;
use service\resources\Exception;
use service\tools\Tools;
use Yii;

/**
 * Class SalesRuleUserCoupon
 * @package common\models
 * @property int $id id
 * @property int $user_id 用户id
 * @property int $rule_id    Rule Id
 * @property int $state 1：未使用 2：已使用
 * @property string $expiration_date 过期时间
 * @property string $created_at 创建时间
 * @property string $used_at 使用时间
 * @property int $source 1：领取 2：系统赠送
 * @property int $coupon_type 1:新人券 2:促销券 3:分享券
 * @property int $condition 条件金额（分） 0:表示无门槛
 * @property int $discount_amount 优惠金额
 * @property int $sales_rule_scope 优惠券适用范围
 * @property int $activity_exclude 与活动是否互斥  1:是 2:否
 * @property int $title 名称
 *
 */
class SalesRuleUserCoupon extends ActiveRecord
{
    public static $coupon_tag = [
        SalesRule::NEW_USER_TYPE => "http://assets.lelai.com/assets/coupon/xinren.png",
        SalesRule::PROMOTION_TYPE => "http://assets.lelai.com/assets/coupon/huodong.png",
        SalesRule::SHARE_TYPE => "http://assets.lelai.com/assets/coupon/fenxiang.png",
    ];
    public static $coupon_tag_grey = [
        SalesRule::NEW_USER_TYPE => "http://assets.lelai.com/assets/coupon/xinren_grey.png",
        SalesRule::PROMOTION_TYPE => "http://assets.lelai.com/assets/coupon/huodong_grey.png",
        SalesRule::SHARE_TYPE => "http://assets.lelai.com/assets/coupon/fenxiang_grey.png",
    ];

    const CAN_USED = 1;  //可以使用
    const CANNOT_USED = 2; //不可以使用

    const ACTIVITY_EXCLUDE = 1;  //活动互斥
    const ACTIVITY_INCLUDE = 2; //活动不互斥

    const UNAVAILABLE_REASON_1 = '不满使用金额';
    const UNAVAILABLE_REASON_2 = '指定商品可用';
    const UNAVAILABLE_REASON_3 = '该优惠券不用购买新人活动商品';
    const UNAVAILABLE_REASON_4 = '本单不是优惠券指定的供货商';
    const UNAVAILABLE_REASON_5 = '本券不能与其他优惠同享';
    const UNAVAILABLE_REASON_6 = '指定商品不满使用数量';
    const UNAVAILABLE_REASON_7 = '不满足使用条件';
    const MAX_DISCOUNT_AMOUNT = 999999;

    const USER_COUPON_UNUSED = 1;  //未使用
    const USER_COUPON_USED = 2; //已使用

    const NOT_DELETED = 1; //未删除
    const DELETED = 2; //已经删除

    const USER_RECEIVE = 1;  //用户领取
    const SYSTEM_RECEIVE = 2; //系统发放

    const COUPON_SCOPE_ALL = 1;  //1:全场通用
    const COUPON_SCOPE_PRODUCT = 2; // 2:部分商品可用

    //优惠券领取
    const PINTUAN_SALES_RULE_RECEIVE_COUNT = 'pintuan_salesrule_receive_count';  //每个规则全局领取次数
    const PINTUAN_SALES_RULE_USER_RECEIVE_COUNT_PREFIX = 'pintuan_salesrule_user_receive_count_'; //每个规则用户领取次数
    const PINTUAN_SHARE_COUPON_RECEIVE = 'pintuan_share_coupon_receive'; //每个规则用户领取次数

    public static function tableName()
    {
        return 'salesrule_user_coupon';
    }

    public static function getDb()
    {
        return Yii::$app->get('orderDb');
    }

    public function getRule()
    {
        return $this->hasOne(SalesRule::className(), ['id' => 'rule_id']);
    }

    public function beforeSave($insert)
    {
        if ($insert) {
            $this->created_at = Tools::getDate();
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }

    public static function getUserReceiveCount($rule_id, $user_id)
    {
        $redis = Tools::getRedis();
        $key = self::PINTUAN_SALES_RULE_USER_RECEIVE_COUNT_PREFIX . $rule_id;
        $count = $redis->hGet($key, $user_id);
        return $count;
    }

    public static function getSalesRuleReceiveCount($rule_id)
    {
        $redis = Tools::getRedis();
        $count = $redis->hGet(self::PINTUAN_SALES_RULE_RECEIVE_COUNT, $rule_id);
        return $count;
    }

    public static function setUserReceiveCount($rule_id, $user_id)
    {
        $redis = Tools::getRedis();
        $key = self::PINTUAN_SALES_RULE_USER_RECEIVE_COUNT_PREFIX . $rule_id;
        $count = $redis->hIncrBy($key, $user_id, 1);
        return $count;
    }

    public static function setSalesRuleReceiveCount($rule_id)
    {
        $redis = Tools::getRedis();
        $count = $redis->hIncrBy(self::PINTUAN_SALES_RULE_RECEIVE_COUNT, $rule_id, 1);
        return $count;
    }

    public static function setShareCouponReceive($user_id, $pintuan_id)
    {
        $redis = Tools::getRedis();
        $hashkey = $user_id . '-' . $pintuan_id;
        //领取拼团的分享券，记录到缓存
        $count = $redis->hSet(self::PINTUAN_SHARE_COUPON_RECEIVE, $hashkey, 1);
        return $count;
    }

    public static function getShareCouponReceive($user_id, $pintuan_id)
    {
        $redis = Tools::getRedis();
        $hashkey = $user_id . '-' . $pintuan_id;
        //领取拼团的分享券，记录到缓存
        $count = $redis->hGet(self::PINTUAN_SHARE_COUPON_RECEIVE, $hashkey);
        return $count;
    }


    /**
     * @param int $tag 1：可用  2：不可用
     * @return array
     *
     */
    public static function getTag($tag = SalesRuleUserCoupon::CAN_USED)
    {
        if ($tag == SalesRuleUserCoupon::CAN_USED) {
            return SalesRuleUserCoupon::$coupon_tag;
        } else {
            return SalesRuleUserCoupon::$coupon_tag_grey;
        }

    }

}
