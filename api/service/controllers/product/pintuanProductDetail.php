<?php

namespace service\controllers\product;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\order\OrderAlreadyPinProxy;
use service\callService\product\PintuanProductDetailProxy;

class pintuanProductDetail extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params);
            $result = (new PintuanProductDetailProxy('product', 'pintuan.PintuanProductDetail', $this->_request))->sendRequest();
            $this->_result = $result->toArray();
//            Tool::log($result,'pintuan_product_detail.log');
//            Tool::log($this->_result,'pintuan_product_detail.log');
        }
        //  已拼XXX件（系统随机生成（1000-10000）+真实下单数量）调用订单服务
        $this->_result['activity']['already_pin'] = empty($this->_result['activity']['already_pin']) ? 0 : $this->_result['activity']['already_pin'];
        if(!empty($this->_result['pintuan'])){
            // 查询实际订单商品数量
            $pintuanIds = [];
            foreach ($this->_result['pintuan'] as $item) {
                $pintuanIds[] = $item['id'];
            }
            $resultNum = (new OrderAlreadyPinProxy('order', 'order.orderAlreadyPin', ['pintaun_id' => $pintuanIds]))->sendRequest()->toArray();
            $this->_result['activity']['already_pin'] += $resultNum['number'];
        }
//        if (isset($this->_result['activity'])) {
//            // 查询实际订单商品数量
//            $pintuanIds = [];
//            foreach ($this->_result['pintuan'] as $item) {
//                $pintuanIds[] = $item['id'];
//            }
//            $resultNum = (new OrderAlreadyPinProxy('order', 'order.orderAlreadyPin', ['pintaun_id' => $pintuanIds]))->sendRequest()->toArray();
//            $this->_result['activity']['already_pin'] += $resultNum['number'];
//        }
        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['pintuan_activity_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}