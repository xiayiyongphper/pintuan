<?php
namespace service\callService\store;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\store\Store;
use message\store\StoreDetailReq;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class GetStoreDetailProxy extends CallServiceBase
{
    public function __construct($params)
    {
        parent::__construct('store', 'store.getStoreDetail', $params);
    }

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
        return new Store();
    }
}