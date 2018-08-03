<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\store\WalletRecordListProxy;

/**
 * Class createOrder
 */
class walletRecordList extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, true, 2);
            $result = (new WalletRecordListProxy('store', 'store.walletRecordList', $this->_request))->sendRequest();
            $this->_result = $result->toArray();
        }
        // 返回的数据数组必须存在
        if (!isset($this->_result['wallet_info'])) {
            $this->_result['wallet_info'] = [];
        }
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['start_date', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['end_date', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['type', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['pagination', validParam::CHECK_TYPE_OPTIONAL, 'pagination'],
            ],
            'pagination' => [
                ['page', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ]
        ];
    }
}