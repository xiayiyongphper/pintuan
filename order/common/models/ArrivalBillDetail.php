<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "arrival_bill_detail".
 *
 * @property int $id 自增id
 * @property int $arrival_id 关联的到货单(arrival_bill)id
 * @property int $sku_id 到货商品sku的id
 * @property string $sku_name 到货商品sku名称
 * @property string $images 商品图片，图片可多张，“;”分隔
 * @property int $should_arrival_num 此sku应到商品数量
 * @property int $arrival_num 实际此sku到货数量
 */
class ArrivalBillDetail extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'arrival_bill_detail';
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
            [['arrival_id', 'sku_id', 'should_arrival_num', 'arrival_num'], 'integer'],
            [['sku_id', 'sku_name', 'should_arrival_num', 'arrival_num'], 'required'],
            [['sku_name'], 'string', 'max' => 255],
            [['images'], 'string', 'max' => 1024],
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
            'sku_id' => 'Sku ID',
            'sku_name' => 'Sku Name',
            'images' => 'Images',
            'should_arrival_num' => 'Should Arrival Num',
            'arrival_num' => 'Arrival Num',
        ];
    }
}
