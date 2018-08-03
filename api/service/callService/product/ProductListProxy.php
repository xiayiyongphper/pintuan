<?php
namespace service\callService\product;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\product\ProductListReq;
use message\product\ProductListRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class ProductListProxy extends CallServiceBase
{
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new ProductListReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new ProductListRes();
    }
}