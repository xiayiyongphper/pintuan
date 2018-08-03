<?php

namespace backend\models;

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
