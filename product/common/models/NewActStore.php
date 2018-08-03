<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "new_act_store".
 *
 * @property int $id 自增ID
 * @property int $act_id 活动id，new_user_activity表的主键id
 * @property int $store_id 店铺id
 * @property int $del 是否删除：1-正常，2-删除
 */
class NewActStore extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;
    const DELETED = 2;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'new_act_store';
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
            [['act_id', 'store_id', 'del'], 'integer'],
            [['store_id'], 'required'],
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
            'store_id' => '店铺id',
            'del' => '是否删除：1-正常，2-删除',
        ];
    }
}
