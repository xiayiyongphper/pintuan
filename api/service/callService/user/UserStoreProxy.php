<?php
namespace service\callService\user;

use message\user\UserStore;
use message\user\UserResponse;
use service\callService\CallServiceBase;

/**
 * Class UserStoreProxy
 */
class UserStoreProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new UserStore();
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