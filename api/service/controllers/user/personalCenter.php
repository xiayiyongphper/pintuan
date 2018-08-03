<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\user\UserProxy;

class personalCenter extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $userInfo = (new UserProxy('user', 'user.getUserInfo', $this->_request))->sendRequest()->toArray();

        $orderStatus = [];

        $result['user_info'] = $userInfo;
        $result['order_status'] = $orderStatus;

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