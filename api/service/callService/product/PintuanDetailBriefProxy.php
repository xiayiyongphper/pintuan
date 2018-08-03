<?php

namespace service\callService\product;

use message\product\Pintuan;
use message\product\PintuanDetailReq;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanDetailBriefProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.PintuanDetailBrief', $data);
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
        return new Pintuan();
    }
}