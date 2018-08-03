<?php

namespace service\callService\product;

use message\common\Topic;
use service\callService\CallServiceBase;

class GetTopicInfoProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'product.getTopicInfo', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new Topic();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new Topic();
    }
}