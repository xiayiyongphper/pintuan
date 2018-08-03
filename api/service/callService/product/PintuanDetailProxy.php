<?php
namespace service\callService\product;

use message\product\PintuanDetailReq;
use message\product\PintuanDetailRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanDetailProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanDetailRes();
    }
}