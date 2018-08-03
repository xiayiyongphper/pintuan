<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use message\store\Store;
use service\callService\store\GetStoreDetailProxy;
use service\callService\user\UserProxy;

class login extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params, false);
        $user = (new UserProxy('user', 'user.login', $this->_request))->sendRequest()->toArray();
        $user['store_name'] = '';
        Tool::log($user,'login.log');

        if (!empty($user['store_id'])) {
            /** @var Store $store */
            $store = (new GetStoreDetailProxy(['store_id' => $user['store_id']]))->sendRequest();
            $user['store_name'] = $store->getStoreName();
        }

        return $user;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['code', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}