<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\user\GetUserStoreNumListProxy;

class getUserStoreNumList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $request = $this->_request;

        unset($request['auth_token']);
        $result = (new GetUserStoreNumListProxy($request))->sendRequest()->toArray();

        return $result;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['store_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}