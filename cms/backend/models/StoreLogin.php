<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "store_login".
 *
 * @property string $id 店铺ID
 * @property string $auth_token 店铺登录临时的token
 * @property string $open_id 用户唯一标识openid 
 * @property string $union_id 商家的union_id
 * @property string $session_key 微信返回的session_key
 * @property string $nick_name 用户微信昵称
 * @property string $avatar_url 用户微信头像
 * @property int $gender 性别,0-未知，1-男，2-女
 * @property string $language 语言
 * @property string $country 国家
 * @property string $store_id 关联store表的id
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $del 是否删除：1-正常，2-删除
 */
class StoreLogin extends \yii\db\ActiveRecord
{

    //获取绑定店主的用户列表（专用展示列表使用）
    public static function getUserNames($store_id)
    {
        $names = '';
        $data = self::getUserList($store_id);
        if ($data) {
            foreach ($data as $key=>$val) {
                $names .= $val['nick_name'] . "<br/>";
            }
        }
        return $names;
    }

    //获取绑定店主的用户列表
    public static function getUserList($store_id)
    {
          $res = [];
          $where = [
              'store_id'=>$store_id,
              'del'=>1
          ];
          $data = self::find()->select('user_id')->where($where)->asArray()->all();
          if ($data) {
              $userModel = new \backend\models\PintuanUser();
              foreach ($data as $key=>$val) {
                  $userInfo = $userModel::findOne($val['user_id']);
                  if ($userInfo) {
                      $res[] = [
                          'id'=>$val['user_id'],
                          'nick_name'=>$userInfo->nick_name
                      ];
                  }
              }
          }
          return $res;
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'store_login';
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
            [['auth_token', 'open_id', 'union_id', 'session_key', 'nick_name', 'avatar_url', 'language', 'country', 'create_at', 'update_at','user_id'], 'required'],
            [['gender', 'store_id', 'del','user_id'], 'integer'],
            [['create_at', 'update_at'], 'safe'],
            [['auth_token'], 'string', 'max' => 100],
            [['open_id', 'union_id', 'session_key'], 'string', 'max' => 255],
            [['nick_name', 'language', 'country'], 'string', 'max' => 128],
            [['avatar_url'], 'string', 'max' => 512],
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
            'union_id' => 'Union ID',
            'session_key' => 'Session Key',
            'nick_name' => 'Nick Name',
            'avatar_url' => 'Avatar Url',
            'gender' => 'Gender',
            'language' => 'Language',
            'country' => 'Country',
            'store_id' => 'Store ID',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
            'user_id'=>'用户uid'
        ];
    }
}
