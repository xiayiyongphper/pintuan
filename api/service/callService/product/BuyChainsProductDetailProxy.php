<?php
namespace service\callService\product;

use message\product\BuyChainsProductDetailReq;
use message\product\BuyChainsProductDetailRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class BuyChainsProductDetailProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'buychains.productDetail', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new BuyChainsProductDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new BuyChainsProductDetailRes();
    }
}