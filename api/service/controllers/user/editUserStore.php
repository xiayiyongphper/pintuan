<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\store\StoreProxy;
use service\callService\user\UserStoreProxy;

class editUserStore extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        if (isset($this->_request['store_id'])) {
            $storeReq['store_id'] = [$this->_request['store_id']];
            (new StoreProxy('store', 'store.getStoreList', $storeReq))->sendRequest()->toArray();
        }

        $userStoreReq = $this->_request;
        unset($userStoreReq['auth_token']);
        $userStores = (new UserStoreProxy('user', 'user.editUserStore', $userStoreReq))->sendRequest()->toArray();

        return $userStores;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['user_store_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['store_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['default_store', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['del', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['name', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
                ['phone', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_STRING],
            ],
        ];
    }
}