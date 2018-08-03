<?php
namespace service\callService\product;

use message\product\Pintuan;
use message\product\PintuanStartReq;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanStartProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.PintuanStart', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanStartReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new Pintuan();
    }
}