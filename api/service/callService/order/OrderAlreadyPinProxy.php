<?php
namespace service\callService\order;

use message\order\orderAlreadyPinReq;
use message\order\orderAlreadyPinRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class OrderAlreadyPinProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new orderAlreadyPinReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new orderAlreadyPinRes();
    }
}