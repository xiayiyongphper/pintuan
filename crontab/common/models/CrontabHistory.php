<?php
/**
 * Created by PhpStorm.
 * User: henryzhu
 * Date: 17-8-22
 * Time: 下午7:51
 */

namespace common\models;

use framework\db\ActiveRecord;

/**
 * Class Crontab
 * @package common\models
 * @property integer $entity_id
 * @property string $route
 * @property string $scheduled_at
 * @property string $executed_at
 * @property string $finished_at
 * @property integer $status
 * @property string $messages
 * @property string $created_at
 * @property int $task_id
 * @property string $client_from
 *
 */
class CrontabHistory extends ActiveRecord
{
    /**  @var int */
    const FROM_WORKER = 1;
    /**  @var int */
    const FROM_RPC_INTERNAL = 2;
    /**  @var int */
    const FROM_RPC_REMOTE = 3;
    /**  @var int */
    const FROM_CLI = 4;

    /** @var int */
    const STATUS_OK = 0;
    /** @var int */
    const STATUS_FAILED = 1;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crontab_history';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('mainDb');
    }
}