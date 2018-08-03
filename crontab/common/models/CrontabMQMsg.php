<?php
/**
 * Created by PhpStorm.
 * User: zqy
 * Date: 17-10-12
 * Time: 上午11:31
 */

namespace common\models;

use framework\components\ToolsAbstract;
use framework\db\ActiveRecord;

/**
 * Class CrontabMQMsg
 * @package common\models\common
 * @property integer $entity_id
 * @property string human_msg_id
 * @property integer client_id
 * @property string client_msg_id
 * @property string|array origin_data
 * @property int $consumer 消息的消费者的ip地址
 * @property string $created_at
 * @property string $updated_at
 * @property int $status
 * @property string|array $notes
 *
 */
class CrontabMQMsg extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'crontab_mq_msg';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return \Yii::$app->get('mainDb');
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (!is_scalar($this->origin_data)) {
            $this->origin_data = json_encode($this->origin_data);
        }

        if (!is_scalar($this->notes)) {
            $this->notes = json_encode($this->notes);
        }

        $curDateTime = date('Y-m-d H:i:s');;
        if ($insert) {
            $this->created_at = $curDateTime;
        }
        $this->updated_at = $curDateTime;
        return parent::beforeSave($insert);
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        $this->notes = $this->notes ? json_decode($this->notes, 1) : [];
        $this->origin_data = $this->origin_data ? json_decode($this->origin_data, 1) : [];
        parent::afterFind();
    }

    /**
     * 通过消息ID处理消息
     *
     * @param int $msgId
     * @param array $appendNotes
     * @param int $status 不设置则自动加1
     * @return bool
     */
    public static function trace($msgId, $appendNotes = [], $status = null)
    {
        /*
        if ($msgId && $msg = static::findOne(['entity_id' => $msgId])) {
            $msg->status = $status ? $status : $msg->status + 1;
            $msg->notes = json_encode(array_merge((array)$msg->notes, ['s_' . $msg->status => $appendNotes]));
            return $msg->save();
        }
        return false;*/
        ToolsAbstract::log(sprintf('__msg_id__=%s,appendNotes=%s', $msgId, json_encode($appendNotes, 1)), 'CrontabMQMsg.log');
    }
}