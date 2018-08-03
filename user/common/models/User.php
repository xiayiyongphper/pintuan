<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id 用户ID
 * @property string $auth_token 验证
 * @property string $open_id
 * @property string $union_id
 * @property string $session_key 微信返回的session_key
 * @property string $nick_name 微信昵称
 * @property int $gender 性别,0-未知，1-男，2-女
 * @property string $language 语言
 * @property string $city 城市
 * @property string $province 省份
 * @property string $country 国家
 * @property string $avatar_url 微信头像
 * @property string $phone 手机号
 * @property int $has_order 1-下过单,2-未下过单
 * @property int $own_store_id 拥有的店铺id，为店主是才有
 * @property string $real_name 真实姓名
 * @property int $is_robot 是否是机器人,1-否，2-是
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 * @property int $del 是否删除：1-正常，2-删除
 * @property string $birthday 生日
 * @property string $constellation 星座
 * @property string $signature 个性签名
 */
class User extends \yii\db\ActiveRecord
{
    const NOT_DELETED = 1;//未删除的
    const ROBOT = 2;//机器人

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
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
            [['auth_token', 'open_id', 'session_key'], 'required'],
            [['gender', 'has_order', 'own_store_id', 'is_robot', 'del'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['auth_token'], 'string', 'max' => 100],
            [['open_id', 'union_id', 'session_key'], 'string', 'max' => 255],
            [['nick_name', 'language', 'city', 'province', 'country'], 'string', 'max' => 128],
            [['avatar_url'], 'string', 'max' => 512],
            [['phone'], 'string', 'max' => 24],
            [['real_name', 'signature'], 'string', 'max' => 50],
            [['birthday'], 'string', 'max' => 10],
            [['constellation'], 'string', 'max' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'            => '用户ID',
            'auth_token'    => '验证',
            'open_id'       => 'Open ID',
            'union_id'      => 'Union ID',
            'session_key'   => '微信返回的session_key',
            'nick_name'     => '微信昵称',
            'gender'        => '性别,0-未知，1-男，2-女',
            'language'      => '语言',
            'city'          => '城市',
            'province'      => '省份',
            'country'       => '国家',
            'avatar_url'    => '微信头像',
            'phone'         => '手机号',
            'has_order'     => '1-下过单,2-未下过单',
            'own_store_id'  => '拥有的店铺id，为店主是才有',
            'real_name'     => '真实姓名',
            'is_robot'      => '是否是机器人,1-否，2-是',
            'created_at'    => '创建时间',
            'updated_at'    => '更新时间',
            'del'           => '是否删除：1-正常，2-删除',
            'birthday'      => '生日',
            'constellation' => '星座',
            'signature'     => '个性签名',
        ];
    }

    public function beforeSave($insert)
    {
        $date = date('Y-m-d H:i:s');
        if ($insert) {
            $this->created_at = $date;
        }
        $this->updated_at = $date;

        return parent::beforeSave($insert);
    }

    /**
     * @param $id
     * @return null|static
     */
    public static function findById($id)
    {
        return static::findOne(['id' => $id]);
    }
}