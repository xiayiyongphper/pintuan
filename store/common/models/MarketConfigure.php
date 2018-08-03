<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "market_configure".
 *
 * @property int $id 自增ID
 * @property string $custom_nickname 客服昵称
 * @property string $custom_qrcode 客服二维码
 * @property string $solitaire_success_msg 接龙成功文案
 * @property string $invite_btn_msg 邀请按钮文案
 * @property string $invite_colonel_banner 招募团长banner图片
 * @property string $colonel_describe_img 团长说明详情图片
 * @property string $created_at 创建时间
 * @property string $updated_at 更新时间
 */
class MarketConfigure extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'market_configure';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('commonDb');
    }

    /**
     * 支付完成后引导加群
     * @param $user_id
     * @param $store_id
     * @return array
     */
    public static function getGuideJoinMessage($store_id)
    {
        //初始化
        $res = [
            'title'=>'',
            'nick_name'=>'',
            'qrcode'=>'',
            'message'=>'',
        ];

        $storeModel = new \common\models\Store();
        $where = ['id'=>$store_id];
        $storeInfo = $storeModel::find()->select('wx_qrcode,group_nickname,group_num')->where($where)->asArray()->one();

        //异常处理
        if (!$storeInfo) {
            return $res;
        }

         //尚未配置自提点的微信二维码或者已满100人时，读取客服信息，否则读取自提点信息
        if (empty($storeInfo['wx_qrcode']) || empty($storeInfo['group_nickname']) || $storeInfo['group_num'] >= 100) {
            $configInfo = self::find()->select('custom_nickname,custom_qrcode')->asArray()->one();

            //为空处理
            if (empty($configInfo)) {
                return $res;
            }
            if (empty($configInfo['custom_nickname']) || empty($configInfo['custom_qrcode'])) {
                return $res;
            }

            $res['title'] = '客服微信';
            $res['nick_name'] = $configInfo['custom_nickname'];
            $res['qrcode'] = $configInfo['custom_qrcode'];
            $res['message'] = '长按识别二维码，添加客服微信，售后问题及时沟通';
        } else {
            $res['title'] = '群名称';
            $res['nick_name'] = $storeInfo['group_nickname'];
            $res['qrcode'] = $storeInfo['wx_qrcode'];
            $res['message'] = '长按识别二维码，加入邻居团购群，一起省更多';
        }

        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['custom_nickname', 'solitaire_success_msg', 'created_at', 'updated_at'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['custom_nickname'], 'string', 'max' => 20],
            [['custom_qrcode', 'invite_colonel_banner', 'colonel_describe_img'], 'string', 'max' => 255],
            [['solitaire_success_msg'], 'string', 'max' => 100],
            [['invite_btn_msg'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'custom_nickname' => 'Custom Nickname',
            'custom_qrcode' => 'Custom Qrcode',
            'solitaire_success_msg' => 'Solitaire Success Msg',
            'invite_btn_msg' => 'Invite Btn Msg',
            'invite_colonel_banner' => 'Invite Colonel Banner',
            'colonel_describe_img' => 'Colonel Describe Img',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
