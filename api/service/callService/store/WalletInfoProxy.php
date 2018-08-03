<?php

namespace service\callService\store;

use message\store\StoreDetailReq;
use message\store\WalletInfoRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class WalletInfoProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new StoreDetailReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WalletInfoRes();
    }
}