<?php

namespace service\callService\order;

use message\order\getShareCouponRequest;
use message\order\getShareCouponResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class getShareCouponCountProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'salesrule.getShareCouponCount', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getShareCouponRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new getShareCouponResponse();
    }
}