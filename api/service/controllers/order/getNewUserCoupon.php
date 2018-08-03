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
use service\callService\order\getNewUserCouponProxy;

/**
 * Class orderList
 */
class getNewUserCoupon extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = [
            'user_id' => $this->_request['user_id'],
        ];
        //新人优惠券
        $result = (new getNewUserCouponProxy($request))->sendRequest()->toArray();

        return $result;
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