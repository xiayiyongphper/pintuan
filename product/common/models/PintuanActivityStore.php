<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pintuan_activity_store".
 *
 * @property int $id
 * @property int $pintuan_activity_id 拼团活动id
 * @property int $store_id
 * @property string $create_at
 * @property int $del
 */
class PintuanActivityStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan_activity_store';
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

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pintuan_activity_id' => 'Pintuan Activity ID',
            'store_id' => 'Store ID',
            'create_at' => 'Create At',
            'del' => 'Del',
        ];
    }
}
