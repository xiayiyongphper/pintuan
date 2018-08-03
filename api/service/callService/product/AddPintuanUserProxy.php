<?php
namespace service\callService\product;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\common\BoolResponse;
use message\product\AddPintuanUserReq;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class AddPintuanUserProxy extends CallServiceBase
{
    public function __construct($data)
    {
        parent::__construct('product', 'pintuan.AddPintuanUser', $data);
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new AddPintuanUserReq();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new BoolResponse();
    }
}