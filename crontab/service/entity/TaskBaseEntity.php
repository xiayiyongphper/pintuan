<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/9/29
 * Time: 11:49
 */

namespace service\entity;

use common\helper\MessageHelper;
use framework\components\ToolsAbstract;

/**
 * Class TaskBaseEntity
 * @package service\entity
 */
class TaskBaseEntity extends VarienObject
{
    /**
     * @see MessageHelper::packJob()
     * @return int
     */
    public function getTaskId()
    {
        return $this->getData('@task_id');
    }

    /**
     * @see MessageHelper::packJob()
     * @return int
     */
    public function getTaskClient()
    {
        return $this->getData('@from');
    }

    /**
     * @see MessageHelper::packJob()
     * @return int
     */
    public function getTaskScheduledTimestamp()
    {
        return $this->getData('@scheduled_timestamp');
    }

    /**
     * @see MessageHelper::packJob()
     * @return int
     */
    public function getTaskTimestamp()
    {
        return $this->getData('@timestamp');
    }

    /**
     * @see MessageHelper::packJob()
     * @return mixed
     */
    public function getTaskParams()
    {
        return $this->getData('@params');
    }
}