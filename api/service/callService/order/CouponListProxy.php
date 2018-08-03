<?php

namespace service\callService\order;

use message\order\getUserCouponListRequest;
use message\order\getUserCouponListResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class CouponListProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'salesrule.couponList', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getUserCouponListRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new getUserCouponListResponse();
    }
}