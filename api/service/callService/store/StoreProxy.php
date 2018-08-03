<?php
namespace service\callService\store;

use message\store\StoreRequest;
use message\store\StoreResponse;
use service\callService\CallServiceBase;

/**
 * Class Store
 */
class StoreProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new StoreRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new StoreResponse();
    }
}