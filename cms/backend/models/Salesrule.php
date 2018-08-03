<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "salesrule".
 *
 * @property int $id 规则id
 * @property string $title 标题
 * @property string $description Description
 * @property int $uses_per_customer 每张券每用户可领取次数限制
 * @property int $condition 条件金额（分） 0:表示无门槛
 * @property int $discount_amount 优惠金额（分）
 * @property int $coupon_type 1:新人券  2:促销券  3:分享券
 * @property int $uses_per_coupon 每张券全局可被领取次数
 * @property int $status 1:正在生效  2:失效
 * @property string $create_at
 * @property int $del 1:未删除 2:已删除
 * @property string $remark 备注
 * @property int $activity_exclude 是否与活动商品互斥1是2否
 * @property int $effective_day 优惠卷的有效天数
 * @property int $sales_rule_scope 1:全场通用 2:部分商品可用
 */
class Salesrule extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salesrule';
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
            [['uses_per_customer', 'condition', 'discount_amount', 'coupon_type', 'uses_per_coupon', 'status', 'del','activity_exclude','effective_day','sales_rule_scope'], 'integer'],
            [['condition', 'discount_amount', 'create_at', 'del', 'remark','activity_exclude','effective_day','sales_rule_scope'], 'required'],
            [['create_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['description', 'remark'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'description' => 'Description',
            'uses_per_customer' => 'Uses Per Customer',
            'condition' => 'Condition',
            'discount_amount' => 'Discount Amount',
            'coupon_type' => 'Coupon Type',
            'uses_per_coupon' => 'Uses Per Coupon',
            'status' => 'Status',
            'create_at' => 'Create At',
            'del' => 'Del',
            'remark' => 'Remark',
            'activity_exclude' => 'activity_exclude',
            'effective_day' => 'effective_day',
            'sales_rule_scope'=>'sales_rule_scope',
            'qrcode'=>'qrcode',
        ];
    }
}
