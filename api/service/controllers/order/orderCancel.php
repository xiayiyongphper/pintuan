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
use service\callService\order\OrderCancelProxy;

/**
 * Class orderList
 */
class orderCancel extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = [
            'user_id' => $this->_request['user_id'],
            'order_id' => $this->_request['order_id'],
            'reason' => isset($this->_request['reason']) ? $this->_request['reason'] : '',
        ];
        //获取自提点信息
        $result = (new OrderCancelProxy($request))->sendRequest()->toArray();

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['order_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['reason', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}