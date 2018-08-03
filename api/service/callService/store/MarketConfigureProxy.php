<?php

namespace service\callService\store;

use message\common\Marketconfigure;
use message\common\MarketconfigureReq;
use message\common\MarketconfigureRes;
use service\callService\CallServiceBase;

/**
 * Class Home
 */
class MarketConfigureProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('store', 'store.marketConfigureInfo', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new MarketconfigureReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new MarketconfigureRes();
    }
}