<?php
/**
 * Created by api.
 * User: Ryan Hong
 * Date: 2018/6/12
 * Time: 15:41
 */

namespace service\controllers\pay;

use framework\ApiAbstract;
use framework\Exception;
use framework\validParam;
use message\common\Order;
use service\callService\order\orderInfoProxy;
use service\callService\pay\PayProxy;
use service\callService\product\CheckPintuanActivityProxy;

/**
 * Class test
 */
class pay extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = [
            'user_id' => $this->_request['user_id'],
            'order_id' => $this->_request['order_id'],
        ];

        //获取自提点信息
        /** @var Order $order */
        $order = (new orderInfoProxy($request))->sendRequest();

        if (!$order->getId() || $order->getStatus() != 1) {
            Exception::throwException(Exception::ORDER_INVALID);
        }

        if ($order->getPintuanActivityId() > 0) {
            //获取拼团信息
            (new CheckPintuanActivityProxy(['pintuan_activity_id' => $order->getPintuanActivityId()]))->sendRequest();
        }

        //调用支付
        $request = [
            'body' => "拼团商品",
            'detail' => '拼团商品',
            'out_trade_no' => $order->getOrderNumber(),
            'total_fee' => $order->getAmount(),
            'openid' => $this->_user->getOpenId(),
        ];

        $this->_result = (new PayProxy($request))->sendRequest()->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['order_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}