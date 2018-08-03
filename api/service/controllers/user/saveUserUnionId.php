<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\user\UserProxy;

class saveUserUnionId extends ApiAbstract
{
    public function run($params)
    {
        Tool::log($params, 'saveUserUnionId.log');
        $this->doInit($params);
        $result = (new UserProxy('user', 'user.saveUserUnionId', $this->_request))->sendRequest();
        $this->_result = $result->toArray();

        return $this->_result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['raw_data', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['signature', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['encrypted_data', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['iv', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}