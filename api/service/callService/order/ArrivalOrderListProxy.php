<?php

namespace service\callService\order;

use message\order\arrivalOrderListReq;
use message\order\arrivalOrderListRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class ArrivalOrderListProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new arrivalOrderListReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new arrivalOrderListRes();
    }
}