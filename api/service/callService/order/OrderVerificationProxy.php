<?php

namespace service\callService\order;

use message\order\orderVerificationReq;
use message\order\orderVerificationRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class OrderVerificationProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new orderVerificationReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new orderVerificationRes();
    }
}