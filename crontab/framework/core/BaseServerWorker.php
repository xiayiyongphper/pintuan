<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/30
 * Time: 10:09
 */

namespace framework\core;


use yii\base\Object;

/**
 * Class BaseServerWorker
 * @package framework\core
 */
class BaseServerWorker extends Object implements ServerWokerInterface
{
    /**
     * @inheritDoc
     */
    public function onWorkerStart(SWServer $server, $workerId)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onWorkerStop(SWServer $server, $workerId)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onConnect(SWServer $server, $fd)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onClose(SWServer $server, $fd)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onReceive(SWServer $server, $fd, $from_id, $data)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function onFinish(SWServer $server, $taskId, $data)
    {
        return true;
    }

}