<?php

namespace service\callService\order;

use message\order\OrderListRequest;
use message\order\OrderListResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class OrderListProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'order.orderList', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderListRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new OrderListResponse();
    }
}