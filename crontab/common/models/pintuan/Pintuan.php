<?php

namespace common\models\pintuan;

use framework\components\ToolsAbstract;
use Yii;
use framework\db\ActiveRecord;


/**
 * This is the model class for table "pintuan".
 *
 * @property int $id
 * @property int $pintuan_activity_id 拼团活动id
 * @property int $create_user_id 发起拼团的用户ID
 * @property int $member_num 已参加人数
 * @property int $store_id 自提点id，拼团活动为单点拼团时才有
 * @property string $create_at
 * @property string $end_time 拼团结束时间
 * @property int $become_group_status 成团状态
 * @property string $become_group_time 成团时间
 * @property string $status 是否有效团
 * @property int $del 是否删除：1-正常，2-删除
 */
class Pintuan extends ActiveRecord
{
    const NOT_BECOME_GROUP = 1;//未成团
    const BECOME_GROUP = 2;//已成团

    /**
     * @inheritdoc
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


}
