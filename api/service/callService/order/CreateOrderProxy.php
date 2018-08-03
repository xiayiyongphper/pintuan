<?php
namespace service\callService\order;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\order\CreateOrderReq;
use message\order\CreateOrderRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class CreateOrderProxy extends CallServiceBase
{
    /** @var CreateOrderReq $request */
    protected $request;
    public function __construct($data)
    {
        parent::__construct('order', 'order.createOrder', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new CreateOrderReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new CreateOrderRes();
    }
}