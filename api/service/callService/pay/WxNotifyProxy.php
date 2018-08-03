<?php

namespace service\callService\pay;

use message\pay\WxNotifyOrderRequest;
use message\pay\WxNotifyOrderResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class WxNotifyProxy extends CallServiceBase
{
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new WxNotifyOrderRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WxNotifyOrderResponse();
    }
}