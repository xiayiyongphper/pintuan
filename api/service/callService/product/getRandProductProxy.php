<?php

namespace service\callService\product;

use message\user\getRandProductRequest;
use message\user\getRandProductResponse;
use service\callService\CallServiceBase;

/**
 * Class UserProxy
 */
class getRandProductProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getRandProductRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new getRandProductResponse();
    }
}