<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\order;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\order\CreateArrivalBillSaveProxy;

/**
 * Class createOrder
 */
class createArrivalBillSave extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, true, 2);
            $result = (new CreateArrivalBillSaveProxy('order', 'store.createArrivalBillSave', $this->_request))->sendRequest();
            $this->_result = $result->toArray();
        }
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['remark', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['sku_arr', validParam::CHECK_TYPE_REPEATED_REQUIRE, 'item'],
            ],
            'item' => [
                ['sku_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['sku_name', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['should_arrival_num', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['arrival_num', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ]
        ];
    }
}