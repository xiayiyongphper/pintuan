<?php
namespace service\callService\user;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\user\UserListReq;
use message\user\UserListRes;
use message\user\UserResponse;
use service\callService\CallServiceBase;

/**
 * Class GetUserListProxy
 */
class GetUserListProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('user', 'user.getUserList', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new UserListReq();
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