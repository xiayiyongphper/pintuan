<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "pintuan_task".
 *
 * @property int $id 拼团任务活动id
 * @property int $pintuan_activity_id pintuan_activity表的id
 * @property int $pintuan_id pintuan 表的id
 * @property int $pintuan_members 拼团人数
 * @property int $continue_pintuan 超过可继续拼团 1是 2否
 * @property int $base_members 基础人数是否勾选，1勾选，2不勾选
 * @property int $system_autoadd_members 系统自动增加人数是否勾选，1勾选，2不勾选
 * @property int $promise_group 保证成团是否勾选，1勾选，2不勾选
 * @property string $base_members_aftertime 基础人数开团后的时间
 * @property int $base_members_aftertime_active 基础人数开团后多少分钟时一次增加机器人参团人数
 * @property string $system_autoadd_endtime 系统自动增加人数结束前多少分钟
 * @property int $system_autoadd_endtime_nums 系统自动增加人数每多少分钟增加1人
 * @property string $promise_group_endtime 保证成团结束前多少分钟人数补满
 * @property string $pintuan_end_autoadd_time 拼团自动结束时补齐机器人的时间Y-m-d H:i
 * @property string $pintuan_activity_starttime 拼团活动的开始时间
 * @property string $pintuan_activity_endtime 拼团活动的结束时间
 * @property string $create_at 创建时间
 * @property string $update_at 更新时间
 * @property int $del 是否删除：1-正常，2-删除
 * @property int $promise_group_type 保证成团任务类型 1未执行，2执行中，3已完成
 * @property int $system_autoadd_type 系统自增人数任务类型 1未执行，2执行中，3已完成
 * @property int $base_members_type 基础人数任务类型 1未执行，2执行中，3已完成
 * @property int $status 拼团活动是否结束，1未结束，2已结束，3结束已执行
 * @property int $is_valid 拼团活动是否有效1有效，2无效
 */
class PintuanTask extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pintuan_task';
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
            [['pintuan_activity_id', 'pintuan_members', 'pintuan_activity_starttime', 'pintuan_activity_endtime', 'create_at', 'update_at'], 'required'],
            [['pintuan_activity_id', 'pintuan_id', 'pintuan_members', 'continue_pintuan', 'base_members', 'system_autoadd_members', 'promise_group', 'base_members_aftertime_active', 'system_autoadd_endtime_nums', 'del', 'promise_group_type', 'system_autoadd_type', 'base_members_type', 'status', 'is_valid'], 'integer'],
            [['pintuan_activity_starttime', 'pintuan_activity_endtime', 'create_at', 'update_at'], 'safe'],
            [['base_members_aftertime', 'system_autoadd_endtime', 'promise_group_endtime'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pintuan_activity_id' => 'Pintuan Activity ID',
            'pintuan_id' => 'Pintuan ID',
            'pintuan_members' => 'Pintuan Members',
            'continue_pintuan' => 'Continue Pintuan',
            'base_members' => 'Base Members',
            'system_autoadd_members' => 'System Autoadd Members',
            'promise_group' => 'Promise Group',
            'base_members_aftertime' => 'Base Members Aftertime',
            'base_members_aftertime_active' => 'Base Members Aftertime Active',
            'system_autoadd_endtime' => 'System Autoadd Endtime',
            'system_autoadd_endtime_nums' => 'System Autoadd Endtime Nums',
            'promise_group_endtime' => 'Promise Group Endtime',
            'pintuan_activity_starttime' => 'Pintuan Activity Starttime',
            'pintuan_activity_endtime' => 'Pintuan Activity Endtime',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'del' => 'Del',
            'promise_group_type' => 'Promise Group Type',
            'system_autoadd_type' => 'System Autoadd Type',
            'base_members_type' => 'Base Members Type',
            'status' => 'Status',
            'is_valid' => 'Is Valid',
        ];
    }
}
