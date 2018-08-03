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
use message\order\OrderReviewRequest;
use message\order\OrderReviewResponse;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class OrderReviewProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('order', 'order.orderReview', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new OrderReviewRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new OrderReviewResponse();
    }
}