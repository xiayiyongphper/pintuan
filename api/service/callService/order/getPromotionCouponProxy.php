<?php

namespace service\callService\order;

use message\order\getPromotionCouponRequest;
use message\order\getPromotionCouponResponse;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class getPromotionCouponProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'salesrule.getPromotionCoupon', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getPromotionCouponRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new getPromotionCouponResponse();
    }
}