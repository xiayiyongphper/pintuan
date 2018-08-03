<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use service\callService\store\StoreProxy;
use service\callService\user\UserStoreProxy;

class getUserStore extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        $userStoreReq = $this->_request;
        unset($userStoreReq['auth_token']);
        $userStores = (new UserStoreProxy('user', 'user.getUserStoreList', $userStoreReq))->sendRequest()->toArray();
        if (isset($userStores['user_store'])) {
            $userStores = $userStores['user_store'];
            $storeReq['store_id'] = array_column($userStores, 'store_id');
            $stores = (new StoreProxy('store', 'store.getStoreList', $storeReq))->sendRequest()->toArray();
            $stores = array_column($stores['stores'], null, 'store_id');
            foreach ($userStores as &$userStore) {
                $userStore['address'] = '';
                $userStore['detail_address'] = '';
                if (isset($stores[$userStore['store_id']])) {
                    $userStore['store_name'] = $stores[$userStore['store_id']]['store_name'];
                    $userStore['address'] = $stores[$userStore['store_id']]['address'];
//                    $userStore['detail_address'] = $stores[$userStore['store_id']]['detail_address'];
                }
            }
        }

        return $userStores;
    }

    protected function getRules()
    {
        return [
            'main' => [
                ['user_id', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_INT],
                ['auth_token', validParam::CHECK_TYPE_REQUIRE, validParam::VALUE_TYPE_STRING],
                ['user_store_id', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
                ['default_store', validParam::CHECK_TYPE_OPTIONAL, validParam::VALUE_TYPE_INT],
            ],
        ];
    }
}