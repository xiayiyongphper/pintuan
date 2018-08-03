<?php

namespace service\callService\order;

use message\order\arrivalBillDetailReq;
use message\order\arrivalBillDetailRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class ArrivalBillDetailProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new arrivalBillDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new arrivalBillDetailRes();
    }
}