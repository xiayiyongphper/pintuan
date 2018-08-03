<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "salesrule_usage".
 *
 * @property int $id 自动编号
 * @property int $rule_id 优惠券规则ID
 * @property int $user_id 用户ID
 * @property string $created_at 创建时间
 * @property string $order_id 订单ID
 * @property int $salesrule_user_coupon_id 优惠券ID
 * @property string $status 1、receive  2、use 3、return
 */
class SalesruleUsage extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salesrule_usage';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('orderDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id', 'user_id', 'salesrule_user_coupon_id', 'status'], 'required'],
            [['rule_id', 'user_id', 'order_id', 'salesrule_user_coupon_id'], 'integer'],
            [['created_at'], 'safe'],
            [['status'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => 'Rule ID',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'order_id' => 'Order ID',
            'salesrule_user_coupon_id' => 'Salesrule User Coupon ID',
            'status' => 'Status',
        ];
    }
}
