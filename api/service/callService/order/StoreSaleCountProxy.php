<?php

namespace service\callService\order;

use message\order\storeSaleCountReq;
use message\order\storeSaleCountRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class StoreSaleCountProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new storeSaleCountReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new storeSaleCountRes();
    }
}