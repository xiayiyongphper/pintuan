<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "buy_chains_specification".
 *
 * @property int $id
 * @property int buy_chains_id 接龙活动id
 * @property int $specification_id 规格id  sku的id
 * @property int $activity_price 活动价
 * @property int $qty 库存
 * @property int $sold_num 真是销量
 * @property int $fake_sold_base 假销量基数
 * @property int $limit_buy_num 限购的数量，为0时不限购
 * @property string $create_at
 * @property string $update_at
 * @property int $del 是否删除：1-正常，2-删除
 */
class BuyChainsSpecification extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;//未删除的
    const DELETED = 2;
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

}
