<?php
namespace service\callService\product;

use message\product\PintuanDetailReq;
use message\product\PintuanIdDetailRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanIdDetailProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.PintuanIdDetail', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanIdDetailRes();
    }
}