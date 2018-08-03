<?php

namespace service\callService\order;

use message\common\UniversalResponse;
use message\order\OrderAction;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class OrderDeleteProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'order.orderDelete', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderAction();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new UniversalResponse();
    }
}