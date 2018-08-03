<?php
namespace service\callService\product;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\product\CategoryRes;
use message\product\SecondCategoryReq;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class GetSecondCategoryProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'product.getSecondCategory', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new SecondCategoryReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new CategoryRes();
    }
}