<?php
namespace service\callService\user;

use message\user\UserListRes;
use message\user\UserStore;
use service\callService\CallServiceBase;

/**
 * Class GetUserStoreNumListProxy
 */
class GetUserStoreNumListProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('user', 'user.getUserStoreNumList', $data);
    }

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
        return new UserListRes();
    }
}