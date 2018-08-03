<?php

namespace service\callService\order;

use message\order\createArrivalBillSaveReq;
use message\order\createArrivalBillSaveRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class CreateArrivalBillSaveProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new createArrivalBillSaveReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new createArrivalBillSaveRes();
    }
}