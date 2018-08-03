<?php
namespace service\callService\store;

use message\store\Store;
use message\store\HomeResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class HomeProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new Store();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new HomeResponse();
    }
}