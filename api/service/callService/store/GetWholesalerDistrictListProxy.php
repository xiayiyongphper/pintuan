<?php
namespace service\callService\store;

/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/13
 * Time: 17:21
 */

use message\store\WholesalerRequest;
use message\store\WholesalerResponse;
use service\callService\CallServiceBase;

/**
 * Class test
 */
class GetWholesalerDistrictListProxy extends CallServiceBase
{
    public function __construct($params)
    {
        parent::__construct('store', 'store.getWholesalerDistrictList', $params);
    }

    public static function getWholesalersByStore($storeId){
        $params = [
            'store_id' => [$storeId]
        ];
        $wholesalersResult = (new self($params))->sendRequest();
        $wholesalersResult = $wholesalersResult->toArray();
//        Tool::log($wholesalersResult,'second_category.log');
        $wholesalerIds = [];

        if(empty($wholesalersResult['wholesalers'])){
            return $wholesalerIds;
        }

        foreach ($wholesalersResult['wholesalers'] as $item){
            $wholesalerIds[] = $item['wholesaler_id'];
        }
        return $wholesalerIds;
    }

    /**
     * 返回请求协议对象
     * @return \framework\protocolbuffers\Message
     */
    function request()
    {
        return new WholesalerRequest();
    }

    /**
     * 返回返回协议对象
     * @return \framework\protocolbuffers\Message
     */
    function response()
    {
        return new WholesalerResponse();
    }
}