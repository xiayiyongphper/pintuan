<?php

/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/9/28
 * Time: 15:49
 */

namespace common\models;

use framework\db\ActiveRecord;

/**
 * 优惠触发
 * @package common\models\common
 * @property int $entity_id
 * @property string $from_time 开始时间
 * @property string $to_time 结束时间
 * @property int $trigger_scene 触发场景。1：单次定时任务，2：循环任务，3：操作触发
 * @property int $trigger_type 操作触发类型。0：未知。1：注册，2：登录，3：首页，4：订单创建，5：订单确认收货，6：订单评价
 * @property string $name 名称
 * @property string $notes 备注
 * @property int $status 1：启用，2：禁用
 * @property int $task_id 定时任务id,
 * @property array|string $settings 配置信息，json格式，读出来就是array,
 * @property string $created_at
 * @property string $updated_at
 */
class OfferTrigger extends ActiveRecord
{
    const STATUS_ENABLED = 1;
    const STATUS_DISABLED = 2;

    /** @var int 单次定时任务 */
    const SCENE_TYPE_SINGLE_TIMING = 1;
    /** @var int 循环定时任务 */
    const SCENE_TYPE_MULTI_TIMING = 2;
    /** @var int 操作触发 */
    const SCENE_TYPE_TRIGGER = 3;

    const TRIGGER_TYPE_USER_REGISTER = 1;
    const TRIGGER_TYPE_USER_LOGIN = 2;
    const TRIGGER_TYPE_HOME_PAGE = 3;
    const TRIGGER_TYPE_ORDER_CREATED = 4;
    const TRIGGER_TYPE_ORDER_RECEIPT = 5;
    const TRIGGER_TYPE_ORDER_COMMENT = 6;
    const TRIGGER_TYPE_HOMEPAGE_QMQQ = 7;

    const SCENE_TYPE_MULTI_TYPE_MONTHS = 1;
    const SCENE_TYPE_MULTI_TYPE_WEEKS = 2;

    const USER_TYPE_AREAS_AND_TAGS = 1;
    const USER_TYPE_CITIES_AND_USER_IDS = 2;

    const RESULT_COUPON_GRANT_TYPE_ALL = 1;
    const RESULT_COUPON_GRANT_TYPE_RANDOM = 2;

    const RESULT_PUSH_TYPE_DEFAULT = 1;
    const RESULT_PUSH_TYPE_CUSTOM = 2;

    /* 短信模板 */
    const SMS_TPL_TEST1 = 1;
    const SMS_TPL_TEST2 = 2;

    /**
     * @param bool $isOption 是否option选项
     * @return array
     */
    public static function getSMSTplArray($isOption = false)
    {
        $options = [
            self::SMS_TPL_TEST1 => '测试模板1',
            self::SMS_TPL_TEST2 => '测试模板2',
        ];
        if ($isOption) {
            return array_merge([0 => ''], $options);
        } else {
            return $options;
        }
    }

    /**
     * @return array
     */
    public static function getSceneTypeArray()
    {
        return [
            self::SCENE_TYPE_SINGLE_TIMING => '单次定时任务',
            self::SCENE_TYPE_MULTI_TIMING => '循环任务',
            self::SCENE_TYPE_TRIGGER => '操作触发'
        ];
    }

    /**
     * @return array
     */
    public static function getTriggerTypeArray()
    {
        return [
            self::TRIGGER_TYPE_USER_REGISTER => '用户注册',
            self::TRIGGER_TYPE_USER_LOGIN => '用户登录',
            self::TRIGGER_TYPE_HOME_PAGE => '进入首页',
            self::TRIGGER_TYPE_ORDER_CREATED => '创建订单',
            self::TRIGGER_TYPE_ORDER_RECEIPT => '确认收货',
            self::TRIGGER_TYPE_ORDER_COMMENT => '订单评价',
        ];
    }

    /**
     * @return array
     */
    public static function getSceneMutilTypeArray()
    {
        return [
            self::SCENE_TYPE_MULTI_TYPE_MONTHS => '每月',
            self::SCENE_TYPE_MULTI_TYPE_WEEKS => '每周',
        ];
    }

    /**
     * @return array
     */
    public static function getUserTypeArray()
    {
        return [
            self::USER_TYPE_AREAS_AND_TAGS => '根据区域及分群',
            self::USER_TYPE_CITIES_AND_USER_IDS => '根据城市及用户包',
        ];
    }

    /**
     * @return array
     */
    public static function getCouponGrantTypeArray()
    {
        return [
            self::RESULT_COUPON_GRANT_TYPE_ALL => '全部发放',
            self::RESULT_COUPON_GRANT_TYPE_RANDOM => '随机发放',
        ];
    }

    /**
     * @return array
     */
    public static function getMMSTypeArray()
    {
        return [
            self::RESULT_PUSH_TYPE_DEFAULT => '使用默认通知栏推送',
            self::RESULT_PUSH_TYPE_CUSTOM => '自定义通知栏推送',
        ];
    }

    /**
     *
     */
    public function afterFind()
    {
        $this->settings = $this->settings ? json_decode($this->settings, 1) : [];
        parent::afterFind();
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'lelai_slim_common.le_offer_trigger';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('mainDb');
    }
}