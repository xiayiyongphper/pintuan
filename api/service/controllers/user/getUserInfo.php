<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\user\UserProxy;

class getUserInfo extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $result = (new UserProxy('user', 'user.getUserInfo', $this->_request))->sendRequest();
        $this->_result = $result->toArray();

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