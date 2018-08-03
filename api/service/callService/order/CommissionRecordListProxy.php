<?php

namespace service\callService\order;

use message\order\commissionRecordListRes;
use message\order\recordReq;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class CommissionRecordListProxy extends CallServiceBase
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
        return new commissionRecordListRes();
    }
}