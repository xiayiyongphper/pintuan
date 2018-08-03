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
use service\callService\order\CouponListProxy;

/**
 * Class orderList
 */
class couponList extends ApiAbstract
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

        $result = (new CouponListProxy($request))->sendRequest()->toArray();

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