<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/15
 * Time: 16:46
 */

namespace service\controllers\order;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\order\OrderVerificationSaveProxy;
use service\callService\order\WalletRecordSaveProxy;

/**
 * Class createOrder
 */
class orderVerificationSave extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, true, 2);
            $result = (new OrderVerificationSaveProxy('order', 'store.orderVerificationSave',
                [
                    'store_id' => $params['store_id'],
                    'pick_code' => $this->_request['pick_code'],
                    'order_id' => $this->_request['order_id']
                ]))->sendRequest();
            $this->_result = $result->toArray();
        }

        // 订单核销以后 直接转入佣金进入商户钱包
        if (isset($this->_result['commission']) && !empty($this->_result['commission'])) {
            $walletRecord = (new WalletRecordSaveProxy('store', 'store.walletRecordSave',
                [
                    'store_id' => $params['store_id'],
                    'amount' => $this->_result['commission']['amount'],
                    'type' => 1,// 佣金转入
                    'status' => 0,// 佣金转入
                    'remark' => isset($this->_result['commission']['commission_detail']) ? $this->_result['commission']['commission_detail'] : '',// 佣金明细写入
                    'commission_id' => $this->_result['commission']['id'],// 佣金明细id写入
                ]))->sendRequest();
            Tool::log($walletRecord->toArray(), 'walletRecordSave.log');
        }

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['pick_code', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['order_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}