<?php
namespace service\callService\product;

use message\product\PintuanActivityListReq;
use message\product\PintuanActivityListRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanActivityListProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanActivityListReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanActivityListRes();
    }
}