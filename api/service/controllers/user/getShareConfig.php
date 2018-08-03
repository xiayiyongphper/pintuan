<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\user\ShareConfigProxy;

class getShareConfig extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $shareConfig = (new ShareConfigProxy('user', 'user.getShareConfig', true))->sendRequest()->toArray();

        return $shareConfig;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING]
            ],
        ];
    }
}