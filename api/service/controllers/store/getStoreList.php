<?php

namespace service\controllers\store;

use framework\ApiAbstract;
use framework\Tool;
use framework\validParam;
use service\callService\store\StoreProxy;
use service\callService\user\UserStoreProxy;

class getStoreList extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);

        $userStores = (new UserStoreProxy('user', 'user.getUserStoreList', ['user_id' => $this->_request['user_id']]))
            ->sendRequest()->toArray();
        $storeReq = $this->_request;
        
        isset($userStores['user_store']) && $storeReq['store_id'] = array_column($userStores['user_store'], 'store_id');
        unset($storeReq['user_id'], $storeReq['auth_token']);
        $stores = (new StoreProxy('store', 'store.getStoreList', $storeReq))->sendRequest()->toArray();

        return $stores;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['lat', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['lng', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['page', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['page_size', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}