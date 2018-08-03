<?php
namespace service\callService\user;

use message\user\ShareConfigResponse;
use service\callService\CallServiceBase;

/**
 * Class UserProxy
 */
class ShareConfigProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return true;
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new ShareConfigResponse();
    }
}