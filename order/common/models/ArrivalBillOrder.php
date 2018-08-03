<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "arrival_bill_order".
 *
 * @property int $id 自增id
 * @property int $arrival_id 对应arrival_bill的id
 * @property int $arrival_detail_id 对应arrival_biil_detail表的id
 * @property int $order_id 对应订单表order的id
 * @property int $number 订单商品数量
 */
class ArrivalBillOrder extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'arrival_bill_order';
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
            [['arrival_id', 'arrival_detail_id', 'order_id', 'number'], 'required'],
            [['arrival_id', 'arrival_detail_id', 'order_id', 'number'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'arrival_id' => 'Arrival ID',
            'arrival_detail_id' => 'Arrival Detail ID',
            'order_id' => 'Order ID',
            'number' => 'Number',
        ];
    }
}
