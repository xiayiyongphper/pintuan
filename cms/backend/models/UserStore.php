<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "user_store".
 *
 * @property string $id
 * @property string $user_id 用户id
 * @property string $store_id 店铺id（自提点）
 * @property int $default_store 是否默认自提点：0-非，1-是
 * @property int $del 是否删除：1-正常，2-删除
 * @property int $has_order 1-下过单,2-未下过单
 * @property string $name 提货人
 * @property string $phone 提货联系号码
 */
class UserStore extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_store';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('userDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'store_id', 'default_store', 'del', 'has_order'], 'integer'],
            [['name', 'phone'], 'string', 'max' => 25],
            [['user_id', 'store_id'], 'unique', 'targetAttribute' => ['user_id', 'store_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'store_id' => 'Store ID',
            'default_store' => 'Default Store',
            'del' => 'Del',
            'has_order' => 'Has Order',
            'name' => 'Name',
            'phone' => 'Phone',
        ];
    }
}
