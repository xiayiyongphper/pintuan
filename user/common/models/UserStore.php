<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user_store".
 *
 * @property int $id
 * @property int $user_id 用户id
 * @property int $store_id 店铺id（自提点）
 * @property int $default_store 是否默认自提点：0-非，1-是
 * @property int $del 是否删除：1-正常，2-删除
 * @property int $has_order 1-下过单,2-未下过单
 * @property string $name 提货人
 * @property string $phone 提货联系号码
 */
class UserStore extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;//未删除的
    const DELETED = 2;

    const FAKE_NUM = 50;//假数据

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
            'user_id' => '用户id',
            'store_id' => '店铺id（自提点）',
            'default_store' => '是否默认自提点：0-非，1-是',
            'del' => '是否删除：1-正常，2-删除',
            'has_order' => '1-下过单,2-未下过单',
            'name' => '提货人',
            'phone' => '提货联系号码',
        ];
    }
}
