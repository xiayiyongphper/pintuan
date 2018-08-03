<?php

namespace service\callService\order;

use message\order\getNewUserCouponRequest;
use message\order\getNewUserCouponResponse;
use message\order\getSalesRuleDetailRequest;
use message\order\SalesRule;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class getSalesRuleDetailProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'salesrule.getSalesRuleDetail', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new getSalesRuleDetailRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new SalesRule();
    }
}