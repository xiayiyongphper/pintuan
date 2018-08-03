<?php

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\store\StoreLoginProxy;

class login extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params, false, 2);

        if ($this->_request['code']) {
            $storeInfo = (new StoreLoginProxy('store', 'store.login', ['code' => $this->_request['code']]))->sendRequest()->toArray();
        } else {
            $storeInfo = (new StoreLoginProxy('store', 'store.storeAuthentication',
                ['store_id' => $this->_request['store_id'], 'auth_token' => $this->_request['auth_token']]
            ))->sendRequest();
        }

        return $storeInfo;
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