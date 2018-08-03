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
use service\callService\order\WalletRecordSaveProxy;

/**
 * Class createOrder
 */
class cashApply extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, true, 2);
            $result = $walletRecord = (new WalletRecordSaveProxy('store', 'store.walletRecordSave',
                [
                    'store_id' => $params['store_id'],
                    'amount' => (0 - intval($params['amount'] * 100)),
                    'type' => 2,// 提现转出
                    'status' => 1,// 提现转出 待打款
                    'remark' => isset($params['remark']) ? $params['remark'] : '',// 提现备注
                ]))->sendRequest();
            $this->_result = $result->toArray();
        }
        // 返回的数据数组必须存在
        if (!isset($this->_result['wallet_record'])) {
            $this->_result['wallet_record'] = [];
        }
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['amount', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['remark', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}