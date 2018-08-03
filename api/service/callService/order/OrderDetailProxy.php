<?php

namespace service\callService\order;

use message\order\OrderDetailRequest;
use message\order\OrderDetailResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class OrderDetailProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'order.orderDetail', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderDetailRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new OrderDetailResponse();
    }
}