<?php

namespace service\callService\product;

use message\product\NewUserActivityReq;
use message\product\NewUserActivityRes;
use service\callService\CallServiceBase;


class GetNewUserActivityProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'product.getNewUserActivity', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new NewUserActivityReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new NewUserActivityRes();
    }
}