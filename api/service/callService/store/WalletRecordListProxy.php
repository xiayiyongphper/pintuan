<?php

namespace service\callService\store;

use message\order\recordReq;
use message\store\WalletRecordListRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class WalletRecordListProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new recordReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WalletRecordListRes();
    }
}