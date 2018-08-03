<?php
namespace service\callService\product;

use message\product\PintuanChangeReq;
use message\product\PintuanChangeRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanChangeProxy extends CallServiceBase
{

    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.PintuanChange', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanChangeReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanChangeRes();
    }
}