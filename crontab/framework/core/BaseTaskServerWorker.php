<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 10:09
 */

namespace framework\core;

/**
 * Class BaseTaskServerWorker
 * @package framework\core
 */
class BaseTaskServerWorker extends BaseServerWorker implements TaskServerWorkerInterface
{
    /**
     * @inheritdoc
     */
    public function onTick(TaskServer $taskServer, $workerId, $timerId, $userParam = [])
    {
        return true;
    }
}