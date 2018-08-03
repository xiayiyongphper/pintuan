<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/31
 * Time: 16:13
 */

namespace framework\core;


use framework\message\Message;
use service\message\common\ResponseHeader;

/**
 * Class TaskResponse
 * @package framework\core
 */
class TaskResponse extends SWResponse
{
    /**
     * @param \swoole_server $server
     * @param $fd
     * @return bool
     */
    public function sendData($server, $fd)
    {
        if ($fd) {
            $data = $this->getData();
            $server->send($fd, $data);
        }
        return true;
    }
}