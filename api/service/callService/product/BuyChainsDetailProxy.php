<?php
namespace service\callService\product;

use message\product\BuyChainsDetailReq;
use message\product\BuyChainsDetailRes;
use service\callService\CallServiceBase;

/**
 * Class BuyChainsDetailProxy
 */
class BuyChainsDetailProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'buychains.buyChainsDetail', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new BuyChainsDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new BuyChainsDetailRes();
    }
}