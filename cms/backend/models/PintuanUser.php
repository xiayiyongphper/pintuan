<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/1 0001
 * Time: 11:38
 */

namespace backend\models;

use yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


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
 */
class PintuanUser extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }


    public static function getDb()
    {
        return Yii::$app->get('userDb');
    }

    public function rules()
    {
        return [
            [['auth_token', 'open_id', 'session_key', 'created_at', 'updated_at'], 'required'],
            [['gender', 'has_order', 'own_store_id', 'is_robot', 'del'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['auth_token'], 'string', 'max' => 100],
            [['open_id', 'union_id', 'session_key'], 'string', 'max' => 255],
            [['nick_name', 'language', 'city', 'province', 'country'], 'string', 'max' => 128],
            [['avatar_url'], 'string', 'max' => 512],
            [['phone'], 'string', 'max' => 24],
            [['real_name'], 'string', 'max' => 50],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'auth_token' => 'Auth Token',
            'open_id' => 'Open ID',
            'union_id' => 'Union ID',
            'session_key' => 'Session Key',
            'nick_name' => 'Nick Name',
            'gender' => 'Gender',
            'language' => 'Language',
            'city' => 'City',
            'province' => 'Province',
            'country' => 'Country',
            'avatar_url' => 'Avatar Url',
            'phone' => 'Phone',
            'has_order' => 'Has Order',
            'own_store_id' => 'Own Store ID',
            'real_name' => 'Real Name',
            'is_robot' => 'Is Robot',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'del' => 'Del',
        ];
    }

    public function findAllUser()
    {
        return static::find()->all();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return bool
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function add($data)
    {
        return Yii::$app->userDb->createCommand()->insert(self::tableName(), $data)->execute();


    }

    public function count()
    {
        $query = Yii::$app->userDb->createCommand()->query(self::tableName())->execute();
    }


}
