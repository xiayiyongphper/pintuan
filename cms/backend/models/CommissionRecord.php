<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "commission_record".
 *
 * @property int $id
 * @property int $order_id 订单id
 * @property int $store_id 店铺id
 * @property int $type 类型：1-自提佣金，2-推广佣金
 * @property string $commission_detail 佣金明细，包含每个商品条目的商品id、佣金值（分）、购买数量，例如："[{"product_id":2,"commission":23,"num":3},{"product_id":5,"commission":113,"num":2}]"
 * @property int $amount 佣金金额
 * @property int $status 1-待获取，2-已获取，3-已转入钱包，4-已撤销
 * @property string $create_at 创建时间，对应订单支付时间
 * @property string $effect_at 生效时间，对应订单确认收货时间
 * @property string $transfer_at 转入钱包时间，系统自动转入
 * @property int $del 是否删除：1-正常，2-删除
 */
class CommissionRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'commission_record';
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
            [['order_id', 'store_id', 'type', 'commission_detail', 'amount', 'create_at'], 'required'],
            [['order_id', 'store_id', 'type', 'amount', 'status', 'del'], 'integer'],
            [['create_at', 'effect_at', 'transfer_at'], 'safe'],
            [['commission_detail'], 'string', 'max' => 256],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => '订单id',
            'store_id' => '店铺id',
            'type' => '类型：1-自提佣金，2-推广佣金',
            'commission_detail' => '佣金明细',
            'amount' => '佣金金额',
            'status' => '佣金状态',
            'create_at' => '创建时间，对应订单支付时间',
            'effect_at' => '生效时间，对应订单确认收货时间',
            'transfer_at' => '转入钱包时间，系统自动转入',
            'del' => '是否删除：1-正常，2-删除',
        ];
    }


    public static function getCommissionDataProvider($store_id)
    {
        $query = self::find()->where(['store_id' => $store_id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $dataProvider;
    }

}
