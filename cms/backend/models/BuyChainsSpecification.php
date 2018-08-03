<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "buy_chains_specification".
 *
 * @property string $id
 * @property string $buy_chains_id
 * @property string $specification_id
 * @property string $activity_price 活动价（分）
 * @property string $qty
 * @property string $sold_num 销量，（真实的）
 * @property string $fake_sold_base 假销量基数
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class BuyChainsSpecification extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buy_chains_specification';
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
            [['buy_chains_id', 'specification_id', 'activity_price', 'qty', 'create_at', 'update_at'], 'required'],
            [['buy_chains_id', 'specification_id', 'activity_price', 'qty', 'sold_num', 'fake_sold_base', 'del'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'buy_chains_id' => 'Buy Chains ID',
            'specification_id' => 'Specification ID',
            'activity_price' => 'Activity Price',
            'qty' => 'Qty',
            'sold_num' => 'Sold Num',
            'fake_sold_base' => 'Fake Sold Base',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
        ];
    }
}
