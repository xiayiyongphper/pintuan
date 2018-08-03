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
use service\callService\order\OrderListProxy;

/**
 * Class orderList
 */
class orderList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = [
            'user_id' => $this->_request['user_id'],
            'list_type' => isset($this->_request['list_type']) ? $this->_request['list_type'] : 0,
            'page' => isset($this->_request['page']) ? $this->_request['page'] : 1,
            'page_size' => isset($this->_request['page_size']) ? $this->_request['page_size'] : 20,
        ];
        //获取自提点信息
        $result = (new OrderListProxy($request))->sendRequest()->toArray();
        //为兼容前端，前端用status判断，这里要加一个status的值表示 待分享
        if(!empty($result['order'])){
            foreach ($result['order'] as &$order){
                if($order['status_label'] == '待成团'){
                    $order['status'] = -1;
                }
            }
        }

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['list_type', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['page', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}