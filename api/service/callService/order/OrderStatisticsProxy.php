<?php

namespace service\callService\order;

use message\order\OrderNumberRequest;
use message\order\OrderNumberResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class OrderStatisticsProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderNumberRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new OrderNumberResponse();
    }
}