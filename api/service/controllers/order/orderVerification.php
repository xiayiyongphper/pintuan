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
use service\callService\order\OrderVerificationProxy;

/**
 * Class createOrder
 */
class orderVerification extends ApiAbstract
{
    public function run($params)
    {
        if (!empty($params)) {
            $this->doInit($params, true, 2);
            $result = (new OrderVerificationProxy('order', 'store.orderVerification',
                ['store_id' => $params['store_id'], 'pick_code' => $this->_request['pick_code']]))
                ->sendRequest();
            $this->_result = $result->toArray();
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
            ],
        ];
    }
}