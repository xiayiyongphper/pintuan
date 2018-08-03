<?php
namespace service\callService\product;

use message\product\CheckPintuanActivityRequest;
use message\product\PintuanActivity;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class CheckPintuanActivityProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.CheckPintuanActivity', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new CheckPintuanActivityRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanActivity();
    }
}