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
use service\callService\order\OrderStatisticsProxy;

/**
 * Class orderStatistics
 * 各个状态订单统计
 */
class orderStatistics extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $this->_result = (new OrderStatisticsProxy('order', 'order.orderNumber', ['user_id' => $this->_request['user_id']]))
            ->sendRequest()->toArray();
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}