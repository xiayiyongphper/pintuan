<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 19:55
 */

namespace service\processes;


use common\models\Crontab;
use framework\components\ToolsAbstract;
use framework\core\ProcessInterface;
use framework\core\SWServer;
use framework\redis\Keys;

/**
 * Class InitProcess
 * @package service\processes
 */
class InitProcess implements ProcessInterface
{
    private $server;
    private $process;

    /**
     * @inheritdoc
     */
    public function run(SWServer $SWServer, \swoole_process $process)
    {
        $this->server = $SWServer;
        $this->process = $process;

        while (1) {
            $this->initGenerateJobData();
            sleep(1);
        }
    }

    /**
     * 初始化生成任务队列数据
     */
    private function initGenerateJobData()
    {
        static $isInitialized = false;
        if ($isInitialized) {
            return;
        }

        $isInitialized = true;
        try {
            $job = Crontab::findOne(['route' => 'taskCommon.generate']);
            if ($job) {
                $time = 3;  // 尝试三次
                while ($time-- > 0) {
                    $redisKey = Keys::CRONTAB_GENERATE_TASK_PRIFIX . $job->entity_id;
                    ToolsAbstract::getRedis()->lPush($redisKey, time());
                    sleep(2);
                }
            }
        } catch (\Exception $e) {
            ToolsAbstract::logException($e);
        } catch (\Error $e) {
            ToolsAbstract::log($e->__toString());
        }
    }
}