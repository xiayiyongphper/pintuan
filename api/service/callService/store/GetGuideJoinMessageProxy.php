<?php

namespace service\callService\store;

use message\store\GuideJoinGroupReq;
use message\store\GuideJoinGroupRes;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class GetGuideJoinMessageProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('store', 'store.getGuideJoinMessage', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new GuideJoinGroupReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new GuideJoinGroupRes();
    }
}