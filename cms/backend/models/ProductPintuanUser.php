<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pintuan_user".
 *
 * @property int $id
 * @property int $pintuan_id 拼团id，对应pingtuan_product.pintuan表中的id
 * @property int $user_id 用户id
 * @property string $nick_name 微信昵称
 * @property string $avatar_url 微信头像
 * @property string $created_at
 */
class ProductPintuanUser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan_user';
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
            [['pintuan_id', 'user_id', 'nick_name', 'avatar_url', 'created_at'], 'required'],
            [['pintuan_id', 'user_id'], 'integer'],
            [['created_at'], 'safe'],
            [['nick_name'], 'string', 'max' => 128],
            [['avatar_url'], 'string', 'max' => 512],
            [['pintuan_id', 'user_id'], 'unique', 'targetAttribute' => ['pintuan_id', 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pintuan_id' => 'Pintuan ID',
            'user_id' => 'User ID',
            'nick_name' => 'Nick Name',
            'avatar_url' => 'Avatar Url',
            'created_at' => 'Created At',
        ];
    }
}