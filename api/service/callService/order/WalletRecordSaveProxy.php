<?php

namespace service\callService\order;

use message\store\WalletRecord;
use message\store\WalletRecordRes;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class WalletRecordSaveProxy extends CallServiceBase
{

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new WalletRecord();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WalletRecordRes();
    }
}