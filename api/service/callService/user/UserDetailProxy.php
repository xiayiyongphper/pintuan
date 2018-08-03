<?php
namespace service\callService\user;

use message\user\UserRequest;
use message\user\UserResponse;
use service\callService\CallServiceBase;

/**
 * Class UserProxy
 */
class UserDetailProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('user', 'user.userDetail', $data);
    }

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