<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "salesrule_user_coupon".
 *
 * @property int $id Id
 * @property int $user_id 用户id
 * @property int $state 1：未使用 2：已使用
 * @property string $rule_id Rule Id
 * @property string $expiration_date 失效时间
 * @property string $created_at 创建时间
 * @property int $source 1：领取   2：系统赠送
 * @property string $used_at 使用时间
 * @property int $del 1:未删除  2:已删除
 */
class SalesruleUserCoupon extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'salesrule_user_coupon';
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
            [['user_id', 'rule_id', 'expiration_date', 'created_at', 'used_at', 'del'], 'required'],
            [['user_id', 'state', 'rule_id', 'source', 'del'], 'integer'],
            [['expiration_date', 'created_at', 'used_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'state' => 'State',
            'rule_id' => 'Rule ID',
            'expiration_date' => 'Expiration Date',
            'created_at' => 'Created At',
            'source' => 'Source',
            'used_at' => 'Used At',
            'del' => 'Del',
        ];
    }
}
