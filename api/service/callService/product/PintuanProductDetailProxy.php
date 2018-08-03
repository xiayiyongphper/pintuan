<?php
namespace service\callService\product;

use message\product\PintuanProductDetailReq;
use message\product\PintuanProductDetailRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanProductDetailProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanProductDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanProductDetailRes();
    }
}