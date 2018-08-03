<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "order_address".
 *
 * @property int $id Entity Id
 * @property int $order_id 订单id
 * @property string $name 收货人
 * @property string $phone 电话
 * @property string $address 地址
 */
class OrderAddress extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'order_address';
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
            [['order_id'], 'integer'],
            [['name', 'address'], 'required'],
            [['name'], 'string', 'max' => 64],
            [['phone'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 512],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Entity Id',
            'order_id' => '订单id',
            'name' => '收货人',
            'phone' => '电话',
            'address' => '地址',
        ];
    }

    public static function getOrderAddress($id)
    {
        if (($address = OrderAddress::findOne($id)) !== null) {
            return $address;
        }

    }
}
