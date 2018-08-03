<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/29
 * Time: 11:47
 */

namespace service\workers;


use common\helper\MessageHelper;
use common\models\Crontab;
use framework\components\ToolsAbstract;
use framework\core\BaseTaskServerWorker;
use framework\core\TaskServer;
use framework\message\Message;

/**
 * Class RedisJobWorker
 * @package service\workers
 */
class RedisJobWorker extends BaseTaskServerWorker
{
    /**
     * @inheritdoc
     */
    public function onTick(TaskServer $taskServer, $workerId, $timerId, $userParam = [])
    {

        $curTimestamp = time();
        $curDateTime = date('Y-m-d H:i:s', $curTimestamp);
        $jobs = Crontab::find()->select('entity_id,route,params,sticky')->where([
            'status' => Crontab::STATUS_ENABLED
        ])->andWhere(['<=', 'from_time', $curDateTime])
            ->andWhere(['>=', 'to_time', $curDateTime])
            ->all();
        if ($jobs) {
            ToolsAbstract::log('has jobs!count=' . count($jobs), 'RedisJobWorker.log');
            $newJobs = [];
            /** @var  Crontab $job */
            foreach ($jobs as $job) {
                if (!$job->route) {
                    continue;
                }
                $newJobs[$job->entity_id] = $job;
            }
            $taskIds = array_column($newJobs, 'entity_id');
            if ($taskIds && $timeReachedIds = ToolsAbstract::getTimeReachedJobs($curTimestamp, $taskIds)) {
                foreach ($timeReachedIds as $timeReachedId) {
                    list($jobId, $timestamp) = explode('#', $timeReachedId);
                    $this->runJob($taskServer, $workerId, $newJobs[$jobId], $timestamp);
                }
            }
        } else {
            ToolsAbstract::log('no jobs!', 'RedisJobWorker.log');
        }
        return parent::onTick($taskServer, $workerId, $timerId, $userParam);
    }

    /**
     * @param TaskServer $taskServer
     * @param int $workerId
     * @param Crontab $job
     * @param int $timestamp 计划执行的时间戳
     */
    protected function runJob(TaskServer $taskServer, $workerId, Crontab $job, $timestamp)
    {
        /* 是否粘滞到某一个任务执行器，某些任务可以防重 */
        $swServer = $taskServer->getServer();

        $job->scheduledTimestamp = $timestamp;
        $message = new Message();
        $message->setRoute($job->route);
        $message->setParams($job->params);
//        $data = MessageHelper::packJob($job, MessageHelper::FROM_WORKER);

        if ($job->sticky) {
            $dstWorkerId = $job->entity_id % $swServer->setting['task_worker_num'];
        } else {
            $dstWorkerId = -1;
        }

        $swServer->task($message, $dstWorkerId);

    }
}