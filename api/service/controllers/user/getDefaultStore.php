<?php

namespace service\controllers\user;

use framework\ApiAbstract;
use framework\validParam;
use message\store\Store;
use message\user\UserResponse;
use service\callService\store\GetStoreDetailProxy;
use service\callService\user\getDefaultStoreProxy;

class getDefaultStore extends ApiAbstract
{
    public function run($params)
    {
        $this->doInit($params);
        /** @var UserResponse $user */
        $user = (new getDefaultStoreProxy('user', 'user.getDefaultStore', $this->_request))->sendRequest();

        $store_id = $user->getStoreId();
        $own_store_id = $user->getOwnStoreId();

        if (!$store_id) {
            $response = [
                'store_id' => $store_id,
                'own_store_id' => $own_store_id,
                'store_name' => '',
            ];
            return $response;
        }

        try {
            /** @var Store $store */
            $store = (new GetStoreDetailProxy(['store_id' => $store_id]))->sendRequest();
            $store_name = $store->getStoreName();
        } catch (\Exception $e) {
            $store_name = '';
        } catch (\Error $e) {
            $store_name = '';
        }
        $response = [
            'store_id' => $store_id,
            'own_store_id' => $own_store_id,
            'store_name' => $store_name,
        ];

        return $response;
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