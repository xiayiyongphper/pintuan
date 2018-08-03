<?php

namespace common\models;

use Yii;
use framework\db\ActiveRecord;

/**
 * This is the model class for table "buy_chains_store".
 *
 * @property int $id
 * @property int $buy_chains_id 接龙活动id
 * @property int $store_id
 * @property string $create_at
 * @property int $del
 */
class BuyChainsStore extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'buy_chains_store';
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
            [['pintuan_activity_id', 'store_id', 'create_at', 'del'], 'required'],
            [['pintuan_activity_id', 'store_id', 'del'], 'integer'],
            [['create_at'], 'safe'],
        ];
    }
}
