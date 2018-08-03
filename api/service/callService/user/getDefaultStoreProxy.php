<?php
namespace service\callService\user;

use message\user\UserRequest;
use message\user\UserResponse;
use service\callService\CallServiceBase;

/**
 * Class UserProxy
 */
class getDefaultStoreProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new UserRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new UserResponse();
    }
}