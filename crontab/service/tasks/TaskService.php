<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/29
 * Time: 13:08
 */

namespace service\tasks;

use common\helper\MessageHelper;
use common\models\CrontabHistory;
use framework\components\log\LogAbstract;
use framework\components\ToolsAbstract;
use framework\core\ServiceAbstract;
use service\entity\TaskBaseEntity;


/**
 * Class TaskService
 * @package framework\core
 */
abstract class TaskService extends ServiceAbstract
{
    /**
     * @var int 执行历史id
     */
    private $historyId;

    /**
     * @param string $route
     * @param mixed $params
     * @return bool
     */
    public function beforeAction($route, $params = [])
    {
        /**
         * @see MessageHelper::packJob()
         */
        if (!empty($params['@task_id'])) {
            $this->saveTaskHistory($route, $params);
        }
        return parent::beforeAction($route);
    }

    /**
     * @param string $route
     * @param array $data
     * @see MessageHelper::packJob()
     */
    private function saveTaskHistory($route, $data)
    {
        $historyObj = new CrontabHistory();
        $historyObj->route = $route;
        $historyObj->created_at = date('Y-m-d H:i:s', $data['@timestamp']);
        $historyObj->scheduled_at = date('Y-m-d H:i:s', $data['@scheduled_timestamp']);
        $historyObj->executed_at = $historyObj->created_at;
        $historyObj->client_from = $data['@from'];
        $historyObj->task_id = $data['@task_id'];
        $historyObj->finished_at = '1970-01-01 08:00:00';
        $historyObj->status = CrontabHistory::STATUS_FAILED;
        $historyObj->messages = '执行异常';
        if ($historyObj->save()) {
            $this->historyId = $historyObj->entity_id;
        } else {
            $this->log($historyObj->getErrors(), LogAbstract::LEVEL_NOTICE, true);
        }
    }

    /**
     * @param string $route
     * @param mixed $params
     */
    public function afterAction($route, $params = [])
    {
        if ($this->historyId && $historyObj = CrontabHistory::findOne(['entity_id' => $this->historyId])) {
            $historyObj->finished_at = date('Y-m-d H:i:s');;
            $historyObj->status = CrontabHistory::STATUS_OK;
            $historyObj->messages = '成功';
            if (!$historyObj->save()) {
                $this->log($historyObj->getErrors(), LogAbstract::LEVEL_NOTICE, true);
            }
        }
        parent::afterAction($route);
    }

    /**
     * @param mixed $params
     * @return TaskBaseEntity
     */
    protected function parseParams($params)
    {
        return new TaskBaseEntity($params);
    }

    /**
     * @param mixed $msg
     * @param int $level
     * @param bool $log2ES 是否也保存到ES
     * @param string $fileName 文件名
     */
    protected function log($msg, $level = LogAbstract::LEVEL_INFO, $log2ES = false, $fileName = null)
    {
        try {
            ToolsAbstract::log($msg, $fileName ? $fileName : (str_replace("\\", '_', get_called_class()) . '.log'));
            if ($log2ES) {
                $this->log2ES($msg, $level);
            }
        } catch (\Exception $e) {
            // nothing
        }
    }

    /**
     * @param mixed $msg
     * @param int $level
     */
    protected function log2ES($msg, $level = LogAbstract::LEVEL_INFO)
    {
        try {
            ESLogger::get()->log($msg, $level);
        } catch (\Exception $e) {
            // nothing
        }
    }

    /**
     * 获取最近n天的时间
     * @param $max
     * @return array
     */
    protected function getPrevDays($max)
    {
        $date = date('Y-m-d H:i:s');;
        $days = [];
        for ($i = 0; $i < $max; $i++) {
            $day = $date->date('Y-m-d', strtotime("- $i day"));
            $days[] = $day;
        }
        return $days;
    }

    /**
     * 获取未来n天的时间
     * @param $max
     * @return array
     */
    protected function getNextDays($max)
    {
        $date = date('Y-m-d H:i:s');;
        $days = [];
        for ($i = 0; $i < $max; $i++) {
            $day = $date->date('Y-m-d', strtotime("+ $i day"));
            $days[] = $day;
        }
        return $days;
    }
}