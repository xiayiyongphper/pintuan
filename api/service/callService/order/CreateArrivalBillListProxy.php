<?php

namespace service\callService\order;

use message\order\createArrivalBillListReq;
use message\order\createArrivalBillListRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class CreateArrivalBillListProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new createArrivalBillListReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new createArrivalBillListRes();
    }
}