<?php
/**
 * Created by PhpStorm.
 * User: ZQY
 * Date: 2017/8/31
 * Time: 16:13
 */

namespace framework\core;


use framework\message\Message;
use message\common\ResponseHeader;

/**
 * Class TaskResponse
 * @package framework\core
 */
class TaskResponse extends SWResponse
{
    /**
     * @inheritdoc
     */
    public function send()
    {
        $fd = $this->getHeader()->getFd();
        if ($fd) {
            $data = $this->getData();
            $responseHeader = new ResponseHeader();
            $responseHeader->setProtocol($this->getHeader()->getProtocol());
            $responseHeader->setCode($this->getStatusCode());
            if ($this->getStatusCode() == SWResponse::STATUS_OK) {
                $msg = Message::pack($responseHeader, $data);
            } else {
                $responseHeader->setMsg($data);
                $msg = Message::pack($responseHeader, []);
            }
            $this->send($fd, $msg);
        }
        return true;
    }
}