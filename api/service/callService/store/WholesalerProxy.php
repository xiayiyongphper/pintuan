<?php
namespace service\callService\store;

use message\store\WholesalerRequest;
use message\store\WholesalerResponse;
use service\callService\CallServiceBase;

/**
 * Class Wholesaler
 */
class WholesalerProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new WholesalerRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WholesalerResponse();
    }
}