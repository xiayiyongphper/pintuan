<?php
namespace service\callService\pay;

use message\pay\WxUnifiedOrderRequest;
use message\pay\WxUnifiedOrderResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class PayProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('pay', 'pay.UnifiedOrder', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new WxUnifiedOrderRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WxUnifiedOrderResponse();
    }
}