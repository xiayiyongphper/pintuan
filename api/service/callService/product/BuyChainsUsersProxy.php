<?php
namespace service\callService\product;

use message\product\BuyChainsUsersReq;
use message\product\BuyChainsUsersRes;
use service\callService\CallServiceBase;

/**
 * Class BuyChainsUsersProxy
 */
class BuyChainsUsersProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'buychains.buyChainsUsers', $data);
    }
    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new BuyChainsUsersReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new BuyChainsUsersRes();
    }
}