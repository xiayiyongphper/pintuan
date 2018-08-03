<?php

namespace service\callService\order;

use message\order\getNewUserCouponRequest;
use message\order\getNewUserCouponResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class getNewUserCouponProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'salesrule.getNewUserCoupon', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getNewUserCouponRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new getNewUserCouponResponse();
    }
}