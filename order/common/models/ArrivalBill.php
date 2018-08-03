<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "arrival_bill".
 *
 * @property int $id 自增id
 * @property string $arrival_code 到货单唯一编码
 * @property int $sku_num 到货商品sku的种类
 * @property int $should_arrival_total 应到商品总数量
 * @property int $arrival_total 实到商品总数量
 * @property int $order_num 到货订单总数量
 * @property int $store_id 超市的id
 * @property string $remark 备注
 * @property string $create_at 创建时间
 * @property int $del 是否有效 1-有效 2-无效
 */
class ArrivalBill extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'arrival_bill';
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
            [['arrival_code', 'sku_num', 'should_arrival_total', 'arrival_total', 'order_num', 'store_id', 'create_at'], 'required'],
            [['sku_num', 'should_arrival_total', 'arrival_total', 'order_num', 'store_id', 'del'], 'integer'],
            [['create_at'], 'safe'],
            [['arrival_code', 'remark'], 'string', 'max' => 255],
            [['arrival_code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'arrival_code' => 'Arrival Code',
            'sku_num' => 'Sku Num',
            'should_arrival_total' => 'Should Arrival Total',
            'arrival_total' => 'Arrival Total',
            'order_num' => 'Order Num',
            'store_id' => 'Store ID',
            'remark' => 'Remark',
            'create_at' => 'Create At',
            'del' => 'Del',
        ];
    }
}
