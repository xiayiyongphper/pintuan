<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "store_login_relay".
 *
 * @property int $id 店铺ID
 * @property string $auth_token 店铺登录临时的token
 * @property string $open_id 用户唯一标识openid
 * @property string $session_key 微信返回的session_key
 * @property int $store_login_id 关联store_login表的id
 */
class StoreLoginRelay extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_login_relay';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('wholesalerDb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['auth_token', 'open_id', 'session_key'], 'required'],
            [['store_login_id'], 'integer'],
            [['auth_token'], 'string', 'max' => 100],
            [['open_id', 'session_key'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_token' => 'Auth Token',
            'open_id' => 'Open ID',
            'session_key' => 'Session Key',
            'store_login_id' => 'Store Login Id',
        ];
    }
}
