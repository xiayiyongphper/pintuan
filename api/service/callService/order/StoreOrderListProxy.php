<?php

namespace service\callService\order;

use message\order\storeOrderListReq;
use message\order\storeOrderListRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class StoreOrderListProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new storeOrderListReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new storeOrderListRes();
    }
}