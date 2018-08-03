<?php
namespace service\callService\product;

use message\product\PintuanUserReq;
use message\product\PintuanUserRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class PintuanUserProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.PintuanIdUserDetail', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new PintuanUserReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new PintuanUserRes();
    }
}