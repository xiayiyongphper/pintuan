<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 19:55
 */

namespace service\processes;


use common\models\OfferTrigger;
use common\models\Crontab;
use framework\components\crontab\Parser;
use framework\components\ToolsAbstract;
use framework\core\ProcessInterface;
use framework\core\SWServer;
use framework\redis\Keys;

/**
 * Class InitProcess
 * @package service\processes
 */
class GenerateProcess implements ProcessInterface
{
    /**
     * 提前多少天生成将要执行的任务
     * @var int
     */
    const TIME_AHEAD_DAY = 30;

    /**
     * 最多提前生成任务的数量
     * @var int
     */
    const JOBS_MAX_NUM = 30;

    /**
     * 最少提前生成任务的数量
     * @var int
     */
    const JOBS_MIN_NUM = 3;

    /**
     * @inheritdoc
     */
    public function run(SWServer $SWServer, \swoole_process $process)
    {
        while (1) {
            $this->_run();
            sleep(60);
        }
    }

    protected function _run()
    {
        try {
            $curTimestamp = time();
            $curDateTime = date('Y-m-d H:i:s', $curTimestamp);
            ToolsAbstract::log($curDateTime,'generate.log');
            $jobs = Crontab::find()->where(['status' => Crontab::STATUS_ENABLED])
                ->andWhere(['<=', 'from_time', $curDateTime])
                ->andWhere(['>=', 'to_time', $curDateTime])
                ->all();
            /** @var Crontab $job */
            foreach ($jobs as $job) {
                if (!$job->route) {
                    continue;
                }
                /* 如果超过结束时间，则取结束时间 */
                $endTimestamp = $curTimestamp + self::TIME_AHEAD_DAY * 86400;
                $jobToTimestamp = strtotime($job->to_time);
                if ($endTimestamp > $jobToTimestamp) {
                    $endTimestamp = $jobToTimestamp;
                }
                $this->processJob($job, $curTimestamp, $endTimestamp);
            }
        } catch (\Throwable $e) {
            $this->log($e->__toString());
            sleep(1);
        }
    }

    /**
     * 处理计划任务。
     *
     * @param Crontab $job
     * @param int $startTimestamp 开始时间戳
     * @param int $endTimestamp 结束时间戳
     */
    public function processJob($job, $startTimestamp, $endTimestamp)
    {
        $jobRedisKey = Keys::CRONTAB_GENERATE_TASK_PRIFIX . $job->entity_id;
        /* 判断任务数，如果，少于某个数值则生成新的 */
        if (($len = (int)ToolsAbstract::getRedis()->lLen($jobRedisKey)) > self::JOBS_MIN_NUM) {
            $this->log(sprintf('greater than min num[job=%s,len=%s]', $job->entity_id, $len));
            return;
        }

        /* 应该先判断该计划任务是否存在最后那条记录的时间，如果存在并大于开始时间戳，那应该取前者作为开始时间戳 */
        $maxNum = self::JOBS_MAX_NUM - $len;
        $lastTimestampArr = ToolsAbstract::getRedis()->lRange($jobRedisKey, -1, -1);
        $this->log('start process job-' . $job->entity_id);
        if ($lastTimestampArr && isset($lastTimestampArr[0]) && $lastTimestampArr[0] > $startTimestamp) {
            $startTimestamp = $lastTimestampArr[0] + 60;    // 下一分钟
        }

        /* 生成记录 */
        $hit = 0;
        $this->log(sprintf('startTimestamp=%s,endTimestamp=%s,maxNum=%s', $startTimestamp, $endTimestamp, $maxNum));
        for ($timestamp = $startTimestamp; $timestamp < $endTimestamp; $timestamp += 60) {
            $ret = Parser::parse($job->cron_format, $timestamp);
            if ($ret) {
                if (++$hit > $maxNum) {
                    break;
                }

                ToolsAbstract::getRedis()->rPush($jobRedisKey, $timestamp);
                /* 单次任务，后面增加一个大的时间戳，防止重启时有概率执行两次的问题 */
                if ($this->isSingleTask($job)) {
                    $this->log('it is single task!!!!');
                    ToolsAbstract::getRedis()->rPush($jobRedisKey, $timestamp + 86400 * 365 * 20);  // 20年
                    ToolsAbstract::getRedis()->expire($jobRedisKey, 180);   // 3分钟过期足够过期了，因为目前单次任务最多一分钟
                }
                $this->log(sprintf('taskId=%s,timestamp=%s', $job->entity_id, $timestamp));
            }
        }
        $this->log('end process job - ' . $job->entity_id);
    }

    /**
     * 是否单次任务
     *
     * @param Crontab $job
     * @return bool
     */
    private function isSingleTask($job)
    {
        return isset($job->params['offer_trigger_scene'])
            && $job->params['offer_trigger_scene'] == OfferTrigger::SCENE_TYPE_SINGLE_TIMING;
    }

    /**
     * @param mixed $msg
     * @param int $level
     * @param bool $log2ES 是否也保存到ES
     * @param string $fileName 文件名
     */
    protected function log($msg)
    {
        try {
            ToolsAbstract::log($msg, 'GenerateProcess.log');
        } catch (\Exception $e) {
            // nothing
        }
    }
}
