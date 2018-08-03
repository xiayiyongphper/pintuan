<?php

namespace service\callService\user;

use message\user\getRandUserRequest;
use message\user\getRandUserResponse;
use service\callService\CallServiceBase;

/**
 * Class UserProxy
 */
class getRandUserProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getRandUserRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new getRandUserResponse();
    }
}