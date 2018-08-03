<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\user\UserProxy;

class editUserInfo extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $result = (new UserProxy('user', 'user.editUserInfo', $this->_request))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['province', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['city', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['country', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['birthday', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['constellation', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['signature', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}