<?php

namespace common\models;

use Yii;
use framework\db\ActiveRecord;

/**
 * This is the model class for table "buy_chains_specification".
 *
 * @property int $id
 * @property int buy_chains_id 接龙活动id
 * @property int $user_id 用户id
 * @property int $store_id 自提点id
 * @property int $buy_number 购买数量
 * @property string $create_at
 */
class BuyChainsUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buy_chains_user';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('productDb');
    }

}
