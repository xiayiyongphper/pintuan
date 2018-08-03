<?php
namespace service\callService\product;

use message\product\PintuanActivityDetailReq;
use message\product\PintuanActivityDetailRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanActivityDetailProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanActivityDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanActivityDetailRes();
    }
}