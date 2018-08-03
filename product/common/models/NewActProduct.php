<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "new_act_product".
 *
 * @property int $id 自增ID
 * @property int $act_id 活动id，new_user_activity表的主键id
 * @property int $product_id 商品id
 * @property int $spec_id 规格id
 * @property int $price 新人价(分)
 * @property int $wholesaler_id 供应商id
 * @property int $del 是否删除：1-正常，2-删除
 */
class NewActProduct extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;
    const DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_act_product';
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
            [['act_id', 'product_id', 'spec_id', 'price', 'wholesaler_id', 'del'], 'integer'],
            [['product_id', 'spec_id'], 'required'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '自增ID',
            'act_id' => '活动id，new_user_activity表的主键id',
            'product_id' => '商品id',
            'spec_id' => '规格id',
            'price' => '新人价(分)',
            'wholesaler_id' => '供应商id',
            'del' => '是否删除：1-正常，2-删除',
        ];
    }
}
