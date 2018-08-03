<?php

namespace service\callService\order;

use message\order\GetPayOrderCountReq;
use message\order\GetPayOrderCountRes;
use service\callService\CallServiceBase;

class GetPayOrderCountProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'order.getPayOrderCount', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new GetPayOrderCountReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new GetPayOrderCountRes();
    }
}