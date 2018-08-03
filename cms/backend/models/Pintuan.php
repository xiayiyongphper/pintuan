<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pintuan".
 *
 * @property int $id
 * @property int $pintuan_activity_id 拼团活动id
 * @property int $create_user_id 发起拼团的用户ID
 * @property int $member_num 已参加人数
 * @property int $store_id 自提点id，拼团活动为单点拼团时才有
 * @property string $create_at
 * @property string $end_time
 * @property int $status 是否有效团 1是 2否
 * @property int $del 是否删除：1-正常，2-删除
 */
class Pintuan extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan';
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
            [['pintuan_activity_id', 'create_user_id', 'member_num', 'create_at'], 'required'],
            [['pintuan_activity_id', 'create_user_id', 'member_num', 'store_id', 'status', 'del'], 'integer'],
            [['create_at','end_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pintuan_activity_id' => '拼团活动ID',
            'create_user_id' => '拼团发起人',
            'member_num' => '拼团人数',
            'end_time' => '拼团结束时间，取开始时间后24小时 和 拼团活动结束时间中最小的一个',
            'store_id' => '自提点ID',
            'create_at' => '创建时间',
            'status' => '是否有效团 1是 2否',
            'del' => '是否有效',
        ];
    }

    public function beforeSave($insert)
    {
        // 拼团的自提点id默认为0
        if($insert){
            $this->store_id = 0;
        }
        return parent::beforeSave($insert); // TODO: Change the autogenerated stub
    }
}
