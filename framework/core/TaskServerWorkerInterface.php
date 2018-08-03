<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/29
 * Time: 10:00
 */

namespace framework\core;

/**
 * Interface TaskServerWorkerInterface
 * @package framework\core
 */
interface TaskServerWorkerInterface extends ServerWokerInterface
{
    /**
     * 每次定时器到的时候回调函数
     *
     * @param TaskServer $taskServer
     * @param int $workerId
     * @param int $timerId
     * @param array $userParam
     * @return mixed
     */
    public function onTick(TaskServer $taskServer, $workerId, $timerId, $userParam = []);
}