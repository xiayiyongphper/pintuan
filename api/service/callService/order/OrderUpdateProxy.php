<?php

namespace service\callService\order;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\order\OrderPayReq;
use message\order\OrderUpdateRequest;
use message\order\OrderUpdateResponse;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class OrderUpdateProxy extends CallServiceBase
{
    /** @var OrderPayReq $request */
    protected $request;

    public function __construct($data)
    {
        parent::__construct('order', 'order.orderUpdate', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderUpdateRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new OrderUpdateResponse();
    }
}