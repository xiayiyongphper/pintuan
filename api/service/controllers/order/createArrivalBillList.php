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
use service\callService\order\CreateArrivalBillListProxy;

/**
 * Class createOrder
 */
class createArrivalBillList extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, true, 2);
            $result = (new CreateArrivalBillListProxy('order', 'store.createArrivalBillList', $this->_request))->sendRequest();
            $this->_result = $result->toArray();
        }
        // 返回的数据数组必须存在
        if (!isset($this->_result['sku_arr'])) {
            $this->_result['sku_arr'] = [];
        }
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['pagination', validParam::CHECK_TYPE_OPTIONAL, 'pagination'],
            ],
            'pagination'=>[
                ['page', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ]
        ];
    }
}