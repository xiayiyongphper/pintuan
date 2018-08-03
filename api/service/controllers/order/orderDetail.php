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
use service\callService\order\OrderDetailProxy;
use service\callService\product\PintuanIdDetailProxy;
use service\callService\product\PintuanUserProxy;

/**
 * Class orderList
 */
class orderDetail extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = [
            'user_id' => $this->_request['user_id'],
//            'auth_token' => $this->_request['auth_token'],
            'order_id' => isset($this->_request['order_id']) ? $this->_request['order_id'] : 0
        ];
        //获取订单和商品信息
        $result_order = (new OrderDetailProxy($request))->sendRequest()->toArray();
        Tool::log("result: ".var_export($result_order,true),"wjqorderDetail.log");

        if(empty($result_order['order_info']['pintuan_id'])){ // 表示普通购买
            $result_order['order_info']['pintuan_info'] = array();
            $result_order['order_info']['pintuan_user'] = array();
            return $result_order['order_info'];
        }

        $result = $result_order['order_info'];

        $request_pintuan['pintuan_id'] = $result['pintuan_id'];
        $request_pintuan['user_id'] = $request['user_id'];

        //由pintuan_id 获取拼团相关信息
        $result_pintuan_info = (new PintuanIdDetailProxy($request_pintuan))->sendRequest()->toArray();
        Tool::log("result_pintuan_info: ".var_export($result_pintuan_info,true),"wjqorderDetail.log");

        $result['pintuan_info']['id'] = $result_pintuan_info['id'];
        $result['pintuan_info']['end_time'] = $result_pintuan_info['end_time'];
        $result['pintuan_info']['pintuan_need_num'] = $result_pintuan_info['pintuan_need_num'];
        // pintuan_id 查询 pintuan_user 表的信息
        $request_pintuan_id['pintuan_id'] = $request_pintuan['pintuan_id'];
        $result_pintuan_user_info = (new PintuanUserProxy($request_pintuan_id))->sendRequest()->toArray();
        Tool::log("result_pintuan_user_info: ".var_export($result_pintuan_user_info,true),"wjqorderDetail.log");
        $result['pintuan_user'] = !empty($result_pintuan_user_info['pintuan_user'])?$result_pintuan_user_info['pintuan_user']:array();
        unset($result['pintuan_id']);

        Tool::log("result order_info: ".var_export($result,true),"wjqorderDetail.log");

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['order_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT]
            ],
        ];
    }
}