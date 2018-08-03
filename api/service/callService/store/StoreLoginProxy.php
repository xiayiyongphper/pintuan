<?php
namespace service\callService\store;

use message\store\StoreLoginReq;
use message\store\StoreLoginRes;
use service\callService\CallServiceBase;

/**
 * Class UserProxy
 */
class StoreLoginProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new StoreLoginReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new StoreLoginRes();
    }
}