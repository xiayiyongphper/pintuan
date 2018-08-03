<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "buy_chains".
 *
 * @property string $id
 * @property string $title 标题
 * @property int $weight 权重
 * @property string $product_id
 * @property int $wholesaler_id
 * @property string $start_time
 * @property string $end_time
 * @property string $image
 * @property int $place_type 自提点类型，1同供货商配送范围，2手动选择自提点
 * @property int $status 手动结束，1未结束，2已结束
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class BuyChains extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buy_chains';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('productDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['weight', 'product_id', 'wholesaler_id', 'place_type', 'status', 'del'], 'integer'],
            [['product_id', 'wholesaler_id', 'start_time', 'end_time', 'image', 'create_at', 'update_at', 'del'], 'required'],
            [['start_time', 'end_time', 'create_at', 'update_at'], 'safe'],
            [['title', 'image'], 'string', 'max' => 255],
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
            'weight' => 'Weight',
            'product_id' => 'Product ID',
            'wholesaler_id' => 'Wholesaler ID',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'image' => 'Image',
            'place_type' => 'Place Type',
            'status' => 'Status',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
        ];
    }
}
