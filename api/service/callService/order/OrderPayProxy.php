<?php

namespace service\callService\order;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\order\OrderPayReq;
use message\order\OrderPayRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class OrderPayProxy extends CallServiceBase
{
    /** @var OrderPayReq $request */
    protected $request;

    public function __construct($data)
    {
        parent::__construct('order', 'order.orderPay', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderPayReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new OrderPayRes();
    }
}