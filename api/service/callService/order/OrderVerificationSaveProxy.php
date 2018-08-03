<?php

namespace service\callService\order;

use message\order\orderVerificationSaveReq;
use message\order\orderVerificationSaveRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class OrderVerificationSaveProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new orderVerificationSaveReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new orderVerificationSaveRes();
    }
}